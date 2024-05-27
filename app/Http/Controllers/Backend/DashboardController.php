<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Hospital;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Patient;
use App\Models\PatientInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }


    public function index()
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }

 

        return view('backend.pages.dashboard.index'  );
    }


    public function report(Request $request)
    {
     

        $query = [];

        if ($request->invoice != null) {
            array_push($query, ['invoice_id', $request->invoice]);
        }

        if ($request->start_date != null) {
            array_push($query, ['date', '>=', $request->start_date]);
        }
        if ($request->end_date != null) {
            array_push($query, ['date', '<=', $request->end_date]);
        }


        // $patient = Patient::with(['invoice'])->where('partner', 'LIKE', '%"' . $request->partner . '"%')
        //     ->whereHas('invoice', function ($query) use ($request) {
        //         return $query->where('date', '>=', $request->start_date);
        //     })
        //     ->whereHas('invoice', function ($query) use ($request) {
        //         return $query->where('date', '<=', $request->end_date);
        //     })
        //     // ->whereHas('operation', function ($query) use ($request){
        //     //     return $query->where('hospital_id', $request->hospital);
        //     // })
        //     ->get();


        $invoice = PatientInvoice::with(['patient', 'patient.operation', 'doc'])
            ->where($query)

            ->whereHas('patient.operation', function ($query) use ($request) {
                if ($request->hospital) {

                    return $query->where('hospital_id', $request->hospital);
                }
            })
            ->whereHas('patient', function ($query) use ($request) {
                if ($request->partner) {
                    return $query->where('partner', 'LIKE', '%"' . $request->partner . '"%');
                }
            })


            // ->whereHas('operation', function ($query) use ($request){
            //     return $query->where('hospital_id', $request->hospital);
            // })
            ->get();

        $hospital = Hospital::find($request->hospital);
        $partner = Partner::find($request->partner);

        $data = [
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
            'hospital' => $hospital->title ?? null,
            'partner' => $partner->title ?? null
        ];
        $export = [
            'partner' =>  $request->partner,
            'invoice' => $request->invoice[0],
            'start_date' =>  $request->start_date,
            'end_date' =>  $request->end_date,
        ];



        return view('backend.pages.dashboard.report', ['invoice' => $invoice, 'data' => $data, 'export' => $export])->with('tab', 'invoice');
    }


    public function export(Request $request)
    {

        $query = [];
        $date_query = [];
        $invoice_query = [];


        if (explode('-', $request->company)[0] == 'partner') {

            array_push($invoice_query, 3);
            array_push($invoice_query, 2);
        } else {

            array_push($invoice_query, 1);
        }


        if (explode('-', $request->company)[0] == 'partner') {

            $partner = explode('-', $request->company)[1];
            $hospital = null;
            $company = Partner::find(explode('-', $request->company)[1]);
        } else {
            $hospital = explode('-', $request->company)[1];
            $partner = null;
            $company = Hospital::find(explode('-', $request->company)[1]);
        }




        if ($request->start_date != null) {
            array_push($date_query, ['date', '>=', $request->start_date]);
        }
        if ($request->end_date != null) {
            array_push($date_query, ['date', '<=', $request->end_date]);
        }


        $invoice = PatientInvoice::with(['patient', 'patient.operation', 'doc'])
            ->where($query)
            ->whereIn('invoice_id', $invoice_query)

            ->whereHas('patient.operation', function ($query) use ($hospital) {
                if ($hospital) {

                    return $query->where('hospital_id', $hospital);
                }
            })
            ->whereHas('patient', function ($query) use ($partner) {
                if ($partner) {
                    return $query->where('partner', 'LIKE', '%"' . $partner . '"%');
                }
            })
            ->get();


        $income_query = [];

        // if ($request->start_date != null) {
        //     array_push($income_query, ['date', '>=', $request->start_date]);
        // }
        // if ($request->end_date != null) {
        //     array_push($income_query, ['date', '<=', $request->end_date]);
        // }

        array_push($income_query, ['company', '=', explode('-', $request->company)[1]]);
        array_push($income_query, ['company_type', '=', explode('-', $request->company)[0]]);


        $income = Income::where($income_query)->get();
        $total = $invoice->merge($income)->sortBy('date');

        $prev_income = $income->where('date', '<', $request->start_date);
        $prev_invoice = $invoice->where('date', '<', $request->start_date);
        $prev_total = $prev_invoice->merge($prev_income)->sortBy('date');

        $balance = 0;

        foreach ($prev_total as $item) {


            if ($item->type == 1) {
                $balance -= $item->total;
            } elseif ($item->type == 2) {
                $balance += $item->total;
            } elseif ($item->invoice_id == 2) {
                $balance += $item->total;
            } elseif ($item->invoice_id == 3) {
                $balance -= $item->total;
            }
        }


        $selected_income = $income->where('date', '>=', $request->start_date)->where('date', '<=', $request->end_date);
        $selected_invoice = $invoice->where('date', '>=', $request->start_date)->where('date', '<=', $request->end_date);
        // $selected_total = $selected_invoice->merge($selected_income) ;
        $selected_total = $selected_invoice->toBase()->merge($selected_income)->sortBy('date');

        //   dd($selected_income,$selected_invoice,$selected_total);

        // return view('backend.pages.dashboard.muavin', [
        //     'total' => $selected_total,
        //     'balance' => $balance,
        //     'company' => $company,
        //     'company_type' => explode('-', $request->company)[0],
        //     'start_date' => $request->start_date,
        //     'end_date' => $request->end_date,

        // ]);


        if (explode('-', $request->company)[0] == 'partner') {

            $pdf = Pdf::loadView('backend.pages.dashboard.muavin', [
                'total' => $selected_total,
                'balance' => $balance,
                'company' => $company,
                'company_type' => explode('-', $request->company)[0],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,

            ]);
        } else {
            $pdf = Pdf::loadView('backend.pages.dashboard.hospitalmuavin', [
                'total' => $selected_total,
                'balance' => $balance,
                'company' => $company,
                'company_type' => explode('-', $request->company)[0],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,

            ]);
        }
        return $pdf->setPaper('a4', 'landscape')->stream('muavin.pdf');
        // return Excel::download(new ReportExport($partner, $invoice, $start_date, $end_date), 'users.xlsx');
    }
}

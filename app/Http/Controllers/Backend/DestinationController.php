<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Destination;
use Illuminate\Http\Request;
use DataTables;

class DestinationController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Destination::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    $column =  $row->title;
                    return $column;
                })
                ->addColumn('action', function ($row) {

                    $updateBtn = '    <a class="btn btn-primary btn-xs text-white" href="' . route('admin.destination.edit', $row->id) . '">Edit
                                                            </a>';

                    $deleteBtn = ' <button class="btn btn-danger btn-xs text-white delete-button" data-toggle="modal" data-target="#deleteModal"
                                                            data-id="' . $row->id . '">
                                                            Delete
                                                        </button>';

                    $btn = $updateBtn . $deleteBtn;

                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.pages.destination.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.destination.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $destination = new Destination();


        
        $destination->fill([
            'title' => $request->title
        ]);
        $destination->save();

        session()->flash('success', 'Destination has been created.');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {

        // dd($destination->title);
        // dd($destination->translations['title']['en']);
        // dd($destination['tr']->title);
        return view('backend.pages.destination.edit', ['destination' => $destination]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {

        $destination->fill([
            'title' => $request->title
        ]);
        $destination->save();

        session()->flash('success', 'Destination has been updated.');
        return back();
    }


    public function delete($id)
    {


        $destination = Destination::findOrFail($id);
        $destination->delete();
        session()->flash('success', 'Destination has been deleted.');

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        //
    }
}

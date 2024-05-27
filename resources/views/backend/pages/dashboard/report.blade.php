@extends('backend.layouts.master')

@section('title')
    Report Page
@endsection
@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

<style>
    li {
        padding-left: 20px;
        display: table-row;
    }

    li label {
        display: table-cell;
    }

    li label:not(:first-child) {
        padding-left: 40px;
    }
</style>

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">

                @include('backend.layouts.partials.header')

                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Report</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="index.html">Home</a></li>
                        <li><span>Report</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">



        <div class="main-content-inner">

            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <div class="icon"> <i class="bx bxl-mailchimp"></i> </div>
                                <div class="ms-2 c-details">
                                    <h6 class="mb-0">Filter Info</h6>
                                </div>
                            </div>

                        </div>
                        <div class="mt-2">

                            <ul>
                                <li><label>Partner</label><label>: {{ Str::limit($data['partner'], 30) }}</label></li>
                                <li><label>Hospital</label><label>: {{ $data['hospital'] }}</label></li>
                                <li><label>Start Date</label><label>: {{ $data['start_date'] != null ? \Carbon\Carbon::parse($data['start_date'])->format('d.m.Y') : '' }}</label></li>
                                <li><label>End Date</label><label>: {{ $data['end_date'] != null ? \Carbon\Carbon::parse($data['end_date'])->format('d.m.Y') : '' }}</label></li>
                                <li><label>Number of Invoices</label><label>: {{ $invoice->count() }}</label></li>
                            </ul>


                        </div>
                    </div>
                </div>
                @php
                    $currency = [
                        1 => 'TRY (₺)',
                        2 => 'USD ($)',
                        3 => 'EUR (€)',
                        4 => 'GBP (£)',
                    ];
                @endphp
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Total of Invoices</h6>
                        </div>
                        <div class="mt-2">
                            <ul>
                                @foreach ($currency as $key => $item)
                                    <li><label>{{ $item }}</label><label>: {{ number_format($invoice->where('currency', $key)->sum('total'), 2, '.', ',') }}</label></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Total of Comissions</h6>
                        </div>
                        <div class="mt-2">
                            <ul>
                                @foreach ($currency as $key => $item)
                                    <li><label>{{ $item }}</label><label>: {{ number_format($invoice->where('currency', $key)->sum('comission'), 2, '.', ',') }}</label></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Paid Invoices</h6>
                        </div>
                        <div class="mt-2">
                            <ul>
                                @foreach ($currency as $key => $item)
                                    <li><label>{{ $item }}</label><label>:
                                            {{ number_format($invoice->where('currency', $key)->whereNotNull('payment_date')->sum('total') - $invoice->where('currency', $key)->whereNotNull('payment_date')->sum('comission'), 2, '.', ',') }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Unpaid Invoices</h6>
                        </div>
                        <div class="mt-2">
                            <ul>
                                @foreach ($currency as $key => $item)
                                    <li><label>{{ $item }}</label><label>:
                                            {{ number_format($invoice->where('currency', $key)->whereNull('payment_date')->sum('total') - $invoice->where('currency', $key)->whereNull('payment_date')->sum('comission'), 2, '.', ',') }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="card p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Summary</h6>
                        </div>
                        <div class="mt-2">
                            <ul>
                                @foreach ($currency as $key => $item)
                                    <li><label>{{ $item }}</label><label>: {{ number_format($invoice->where('currency', $key)->whereNull('payment_date')->sum('comission') ,2,'.',',') }}</label></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div> --}}




            </div>


            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title float-left">Patient List</h4>
                            <a href="{{ route('report.export',  $export ) }}" class="btn btn-primary btn-sm float-right mb-3">Export Report</a>
                            <div class="clearfix"></div>
                            @include('backend.layouts.partials.messages')

                            <div class="table-responsive datatable">

                                <table class="table table-striped gy-7 gs-7 data-table">

                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-gray-200">
                                            <th width="5%">#</th>
                                            <th>Full Name</th>
                                            <th>Total</th>
                                            <th>C. Rate</th>
                                            <th>Comission</th>
                                            <th>Invioce Date</th>
                                            <th>Payment Date</th>
                                            <th>Note</th>
                                            <th>Files</th>

                                        </tr>
                                    </thead>


                                    <tbody>

                                        @foreach ($invoice as $item)
                                            @php
                                                if ($item->payment_date == null) {
                                                    $style = 'color:red;';
                                                } else {
                                                    $style = '';
                                                }
                                            @endphp
                                            <tr class="fw-bold fs-6 text-gray-800 border-gray-200" style="{{ $style }}font-size:14px">
                                                <th width="1%" style="">{{ $loop->index + 1 }}</th>
                                                <th>
                                                    <a style="{{ $style }}"
                                                        href="{{ route('admin.patient.edit', ['patient' => $item->patient->id]) }}">{{ Str::limit($item->patient->name . ' ' . $item->patient->surname, 20) }}</a>
                                                </th>
                                                <th>{{ number_format($item->total, 2, '.', ',') }}</th>
                                                <th>{{ $item->comission_rate == null ? 0 : $item->comission_rate }}%</th>
                                                <th>{{ number_format($item->comission == null ? 0 : $item->comission, 2, '.', ',') }}</th>
                                                <th>{{ $item->date != null ? \Carbon\Carbon::parse($item->date)->format('d.m.Y') : '' }}</th>
                                                <th>{{ $item->payment_date != null ? \Carbon\Carbon::parse($item->payment_date)->format('d.m.Y') : '' }}</th>
                                                <th>
                                                    @if ($item->note)
                                                        <button type="button" disabled class="btn btn-secondary btn-xs" data-toggle="tooltip" title="{{ $item->note }}">
                                                            Note
                                                        </button>
                                                    @endif
                                                </th>
                                                <th>
                                                    @if ($item->doc)
                                                        @foreach ($item->doc as $doc)
                                                            <a target="_blank" href="{{ asset('uploads/invoice/' . $doc->path) }}" disabled class="btn btn-secondary btn-xs"
                                                                data-toggle="tooltip" title="{{ $doc->path }}">
                                                                File
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </th>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>




                        </div>
                    </div>
                </div>
                <!-- data table end -->

            </div>
        </div>




    </div>
@endsection

@section('scripts')
    <!-- Start datatable js -->
    {{-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script>
        $(function() {

            var table = $('.data-table').DataTable();

        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection

@extends('backend.layouts.master')

@section('title')
    Type
@endsection
 

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                                @include('backend.layouts.partials.header')

                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Type</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><span>All Type</span></li>
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
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title float-left">Type List</h4>
                        <p class="float-right mb-2">
                            @if (Auth::guard('admin')->user()->can('admin.edit'))
                                <a class="btn btn-primary text-white" href="{{ route('admin.type.create') }}">Create New Type</a>
                            @endif
                        </p>
                        <div class="clearfix"></div>
                        @include('backend.layouts.partials.messages')

                        <div class="table-responsive">

                            <table class="table table-striped gy-7 gs-7 data-table">

                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-gray-200">
                                        <th width="5%">#</th>
                                        <th>Name</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                        </div>





                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection


@section('scripts')
 
    <script type="text/javascript">
        $(function() {

            var table = $('.data-table').DataTable({
                processing: true,
                // "oLanguage": {
                //     "sUrl": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Turkish.json"
                // },
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.type.index') }}",


                },

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#committee_id').change(function() {
                table.draw();
            });
        });

        $('#deleteModal').on('show.bs.modal', function(e) {

            var id = $(e.relatedTarget).data('id');

            $('.modalDeleteButton').on('click', function() {

                window.location.href = "{{ route('admin.type.delete') }}/" + id;

            });
        })
    </script>

 
@endsection

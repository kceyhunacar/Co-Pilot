@extends('backend.layouts.master')

@section('title')
    Destination Create
@endsection

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                @include('backend.layouts.partials.header')
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Destination Create</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.destination.index') }}">All Destinations</a></li>
                        <li><span>Create Destination</span></li>
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
                        <h4 class="header-title">Create New Destination</h4>
                        @include('backend.layouts.partials.messages')
 
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach (config('app.locales') as $key => $value)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->index == 0 ? 'show active' : '' }}" id="{{ $key }}-tab" data-toggle="tab" href="#{{ $key }}"
                                        role="tab" aria-controls="{{ $key }}" aria-selected="true">{{ $value }}</a>
                                </li>
                            @endforeach

                        </ul>

                        <form action="{{ route('admin.destination.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <div class="tab-content mt-4" id="myTabContent">
                                @foreach (config('app.locales') as $key => $value)
                                    <div class="tab-pane fade {{ $loop->index == 0 ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel"
                                        aria-labelledby="{{ $key }}-tab">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="name">Title</label>
                                                <input destination="text" class="form-control" name="title[{{ $key }}]">
                                            </div>

                                        </div>
                                    </div>
                                @endforeach

<input type="file" name="image" >
                            </div>
                            <button destination="submit" class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>

                        </form>



                    </div>
                </div>


            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection

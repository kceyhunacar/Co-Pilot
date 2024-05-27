@extends('backend.layouts.master')

@section('title')
    Feature Create
@endsection




@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                @include('backend.layouts.partials.header')
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Feature Create</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.feature.index') }}">All Features</a></li>
                        <li><span>Create Feature</span></li>
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
                        <h4 class="header-title">Create New Feature</h4>
                        @include('backend.layouts.partials.messages')


                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach (config('app.locales') as $key => $value)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->index == 0 ? 'show active' : '' }}" id="{{ $key }}-tab" data-toggle="tab" href="#{{ $key }}"
                                        role="tab" aria-controls="{{ $key }}" aria-selected="true">{{ $value }}</a>
                                </li>
                            @endforeach

                        </ul>

                        <form action="{{ route('admin.feature.store') }}" method="POST">
                            @csrf


                            <div class="tab-content mt-4" id="myTabContent">
                                @foreach (config('app.locales') as $key => $value)
                                    <div class="tab-pane fade {{ $loop->index == 0 ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel"
                                        aria-labelledby="{{ $key }}-tab">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="name">Title</label>
                                                <input feature="text" class="form-control" name="title[{{ $key }}]">
                                            </div>



                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="name">Type</label>
                                        <select feature="text" class="form-control" name="type">
                                            @foreach (config('app.types') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                              
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="name">Category</label>
                                        <select feature="text" class="form-control" name="category">
                                            <option value="">Choose</option>
                                            @foreach ($feature_category as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            </div>
                            <button feature="submit" class="btn btn-primary mt-4 pr-4 pl-4">Submit</button>

                        </form>



                    </div>
                </div>


            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection

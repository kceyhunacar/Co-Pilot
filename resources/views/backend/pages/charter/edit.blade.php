@extends('backend.layouts.master')

@section('title')
    Charter Edit
@endsection

<style>
    .gallery-img {
        padding: 1rem;
        width: 50rem;
        height: 9rem;
        object-fit: contain;
        max-width: -webkit-fill-available;
    }

    .img-delete {
        width: auto !important;

    }

    .place-center {
        place-content: center;

    }

    .upload-container {
        position: relative;
        margin-bottom: 3rem;
    }

    .upload-container input {
        border: 1px solid #92b0b3;
        background: #f1f1f1;
        outline: 2px dashed #009ef7;
        outline-offset: -10px;
        padding: 6rem 3rem 6rem 3rem;
        text-align: center !important;
        width: -webkit-fill-available;

    }

    .upload-container input:hover {
        background: #ddd;
    }

    .upload-container:before {
        position: absolute;
        bottom: 3rem;
        left: 4rem;
        content: " Resimlerinizi bu alana sürükleyip bırakabilirsiniz ";
        color: #009ef7;
        font-weight: 900;
        font-size: 1.2rem;

    }

    .upload-btn {
        margin-left: 300px;
        padding: 7px 20px;
    }
</style>

@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                @include('backend.layouts.partials.header')

                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Charter Edit</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin.charter.index') }}">All Charters</a></li>
                        <li><span>Edit Charter - {{ $charter->title }}</span></li>
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
                        <h4 class="header-title">Edit Charter - {{ $charter->title }}</h4>

                        @include('backend.layouts.partials.messages')



                        <div class="stepwizard col-md-offset-3">
                            <div class="stepwizard-row setup-panel">
                                <div class="stepwizard-step">
                                    <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                    <p>General</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-2" type="button" class="btn btn-secondary btn-circle" disabled="disabled">2</a>
                                    <p>Feature</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-3" type="button" class="btn btn-secondary btn-circle" disabled="disabled">3</a>
                                    <p>Prices</p>
                                </div>
                                <div class="stepwizard-step">
                                    <a href="#step-4" type="button" class="btn btn-secondary btn-circle" disabled="disabled">4</a>
                                    <p>Gallery</p>
                                </div>
                            </div>
                        </div>

                        <form role="form" action="{{ route('admin.charter.update',$charter->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
@method('PUT')





                            <div class="row setup-content" id="step-1">
                                <div class="col-md-12">
                                    <h3 class="my-3">General</h3>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Charter Name</label>
                                            <input type="text" required="required" name="title" value="{{ $charter->title }}" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Destination</label>
                                            <select type="text" required="required" name="destination" class="form-control">
                                                <option value="">Choose...</option>
                                                @foreach ($destination as $item)
                                                    <option {{ $item->id == $charter->destination ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Type</label>
                                            <select type="text" required="required" name="type" class="form-control">
                                                <option value="">Choose...</option>
                                                @foreach ($type as $item)
                                                    <option {{ $item->id == $charter->type ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">Next</button>
                                </div>

                            </div>



                            <div class="row setup-content" id="step-2">
                                <div class="col-md-12">
                                    <h3 class="my-3">Features</h3>
                                    <div class="row">



                                        @foreach ($feature_category as $category)
                                            <div class="col-md-12">
                                                <p class="h4">{{ $item->title }}</p>
                                            </div>
                                            <br>

                                            @foreach ($category->getFeature as $item)
                                                @php
                                                    $value = $charter->getFeature->where('feature', $item->id)->first()->value ?? null;
                                                @endphp
                                                @if ($item->type == 0)
                                                    <div class="form-group col-md-3 align-content-center">
                                                        <label class="control-label">{{ $item->title }}</label>
                                                        <input type="text" name="feature[{{ $item->id }}]" value="{{ $value }}" class="form-control">
                                                    </div>
                                                @elseif($item->type == 1)
                                                    <div class="form-group col-md-3 align-content-center">
                                                        <label class="control-label">{{ $item->title }}</label>
                                                        <input type="number" name="feature[{{ $item->id }}]" value="{{ $value }}" class="form-control">
                                                    </div>
                                                @elseif($item->type == 2)
                                                    <div class="form-check form-group col-md-3 align-content-center">
                                                        <input {{ $value == 1 ? 'checked' : '' }} class="form-check-input" name="feature[{{ $item->id }}]" type="checkbox"
                                                            value="1" id="check{{ $item->id }}">
                                                        <label class="form-check-label" for="check{{ $item->id }}">
                                                            {{ $item->title }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach



                                    </div>
                                    <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">Next</button>
                                </div>

                            </div>


                            <div class="row setup-content" id="step-3">
                                <div class="col-md-12">
                                    <h3 class="my-3">Prices</h3>
                                    <div class="row">


                                        @for ($i = 1; $i < 13; $i++)
                                            <div class="form-group col-md-3">
                                                <label class="control-label">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</label>
                                                <input type="number" name="price[{{ $i }}]" value="{{ $charter->getPrice->$i }}" class="form-control">
                                            </div>
                                        @endfor



                                    </div>
                                    <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">Next</button>
                                </div>

                            </div>


                            <div class="row setup-content" id="step-4">
                                <div class="form-row">
                                    <div class="col-xs-12">
                                        <div class="row ml-2">
                                            @foreach ($charter->getPhotos as $item)
                                                <div class="col-md-3 text-center" id="photo-item-{{ $item->id }}">
                                                    <div class="row place-center">

                                                        <img class="gallery-img" src="{{ asset($item->path) }}" alt="{{ asset($item->path) }}">
                                                        <button type="button" data-id="{{ $item->id }}" class="btn btn-xs btn-danger mt-2 img-delete" data-toggle="modal"
                                                            data-target="#deleteModal">Sil</button>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h3 class="my-3">Gallery</h3>
                                    <div class="row">
                                        <div class="mb-3 mt-5">
                                            {{-- <label for="name">Galerisi</label> --}}
                                            <div class="upload-container">
                                                <input type="file" name="photos[]" id="file_upload" multiple="">
                                            </div>
                                        </div>



                                    </div>
                                    <button class="btn btn-primary prevBtn btn-lg pull-left" type="button">Previous</button>
                                    <button class="btn btn-success btn-lg pull-right" type="submit">Submit</button>
                                </div>

                            </div>


                        </form>




                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection
@section('scripts')
 
@endsection

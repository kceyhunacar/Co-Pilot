<link rel="shortcut icon" type="image/png" href="{{ asset('backend/assets/images/icon/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/metisMenu.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/slicknav.min.css') }}">
<!-- amchart css -->
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<!-- others css -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/typography.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/default-css.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/responsive.css') }}">
<!-- modernizr css -->
<script src="{{ asset('backend/assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>

<link rel="stylesheet" featurecategory="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" featurecategory="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" featurecategory="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" featurecategory="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">

<style>
    .upload-container {
        position: relative
    }

    .upload-container input {
        border: 1px solid #92b0b3;
        background: #f1f1f1;
        outline: 2px dashed #92b0b3;
        outline-offset: -10px;
        padding: 5rem 0 5rem 1rem;
        text-align: center !important;
        width: -webkit-fill-available;
    }

    .form-control {
        /* padding: 0!important */
    }

    .upload-container input:hover {
        background: #ddd
    }

    .upload-container:before {
        position: absolute;
        bottom: 50px;
        left: 1rem;
        content: " (or) Drag and Drop files here. ";
        color: #3f8188;
        font-weight: 900
    }

    .upload-btn {
        margin-left: 300px;
        padding: 7px 20px
    }


    .stepwizard-step p {
        margin-top: 10px;
    }

    .stepwizard-row {
        display: table-row;
    }

    .stepwizard {
        display: table;
        width: -webkit-fill-available;
        position: relative;
    }

    .stepwizard-step button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }

    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-order: 0;
    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }


    .has-error input {
        background-color: #fce4e4;
        border: 1px solid #cc0033;
        outline: none;
    }
    .has-error textarea {
        background-color: #fce4e4;
        border: 1px solid #cc0033;
        outline: none;
    }
</style>

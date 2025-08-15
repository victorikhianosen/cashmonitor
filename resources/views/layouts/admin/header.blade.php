<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CashMatrix | Monitoring</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="coderthemes" name="author">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-icon.png') }}">

    <!-- Gridjs Plugin css -->
    <link href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <!-- Sidenav Menu -->
        <div class="app-menu">

            <!-- Sidenav Brand Logo -->
            <a href="{{ route('home') }}" class="logo-box">
                <!-- Light Brand Logo -->
                <div class="logo-light">
                    <img src="assets/images/logo.png" class="logo-lg h-6" alt="Light logo">
                    <img src="assets/images/logo.png" class="logo-sm" alt="Small logo">
                </div>

                <!-- Dark Brand Logo -->
                <div class="logo-dark">
                    <img src="assets/images/logo.png" class="logo-lg h-6" alt="Dark logo">
                    <img src="assets/images/logo.png" class="logo-sm" alt="Small logo">
                </div>
            </a>

            <!-- Sidenav Menu Toggle Button -->
            <button id="button-hover-toggle" class="absolute top-5 end-2 rounded-full p-1.5">
                <span class="sr-only">Menu Toggle Button</span>
                <i class="mgc_round_line text-xl"></i>
            </button>

            <!--- Menu -->
            <div class="srcollbar" data-simplebar>
             @include('layouts.admin.sidebar')


            </div>
        </div>


        <div class="page-content">

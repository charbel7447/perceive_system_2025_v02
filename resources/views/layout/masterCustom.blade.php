<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon"> <!-- Favicon-->
<title>@yield('title') - {{ config('app.name') }}</title>
<meta name="description" content="@yield('meta_description', config('app.name'))">
<meta name="author" content="@yield('meta_author', config('app.name'))">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

@yield('meta')
@stack('before-styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">    
<link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">    
<!-- <link rel="stylesheet" href="{{ asset('assets/vendor/animate-css/vivify.min.css') }}"> -->

@stack('after-styles')
@if (trim($__env->yieldContent('page-styles')))    
@yield('page-styles')
@endif    

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/mooli.min.css') }}">
</head>

<body data-theme="light">

<div id="body" class="theme-cyan">
    <!-- Theme setting div -->
    <div id="wrapper">
 
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/')}}/css/bootstrap.css">
    <!-- Top Navbar -->
 
    

    <style>
        #main-content {
            width: 100% !important;
        }
           body, html {
        font-family: Lato !important;
    }
    </style>
        <div id="main-contentx">
            <div class="container-fluid">          

                @yield('content')

            </div>
        </div>        
    </div>

    
</div>


<!-- main jquery and bootstrap Scripts -->
@stack('before-scripts')
<script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

<style>
@media screen and (max-width: 1200px) {
  .sidebar {left:0 !important};  
}

    </style>
@stack('after-scripts')

<!-- vendor js file -->
@yield('vendor-script')

<!-- project main Scripts js-->
<script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

<!-- page Scripts ja -->
@yield('page-script')


</body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', config('app.name'))">
    <meta name="author" content="@yield('meta_author', config('app.name'))">

    @yield('meta')
    @stack('before-styles')

    <!-- Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    @stack('after-styles')
    @yield('page-styles')

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/mooli.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/bootstrap.css">

    <style>
        #main-content {
            width: 100% !important;
        }
        @media screen and (max-width: 1200px) {
            .sidebar {left:0 !important;}
        }
    </style>
</head>

<body data-theme="light">
    <div id="body" class="theme-cyanx">
        <div id="wrapper">
            <div id="main-contentx">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('before-scripts')

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap and vendor bundles -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>

    <!-- Project Main Scripts -->
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>

    @stack('after-scripts')
    @yield('vendor-script')
    @yield('page-script')

    <!-- Optional: Safe DOMContentLoaded wrapper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Example: initialize a DataTable if it exists
            if ($('#myTable').length) {
                $('#myTable').DataTable();
            }

            // Your custom event bindings go here
        });
    </script>
</body>
</html>

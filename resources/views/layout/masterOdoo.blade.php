<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Balance Sheet')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Sticky table headers */
        thead th {
            position: sticky;
            top: 0;
            background: #f9fafb;
            z-index: 10;
        }
        /* Scrollable table */
        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>

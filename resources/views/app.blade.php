<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="stylesheet" href="/fonts/lato.min.css"> --}}
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        {{-- <link rel="stylesheet" href="{{ mix('css/adminlte.css') }}"> --}}
        <link rel="stylesheet" href="{{ mix('css/custom.css') }}">
        <link rel="stylesheet" href="/css/zoomOnHover.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        {{-- For Production --}}
        <link rel="stylesheet" href="{{ mix('css/adminlte.min.css') }}">
        {{-- For Production --}}

        <!-- Scripts -->
        @routes
        <script src="/js/jquery.min.js"></script>
        <script src="/js/jquery-ui.min.js"></script>
        
        <script src="/js/moment.min.js"></script>
        <script src="/js/zoomOnHover.js"></script>
        {{-- <script src="{{ mix('js/adminlte.js') }}" defer></script> --}}
        {{-- For Production --}}
        <script src="{{ mix('js/adminlte.min.js') }}" defer></script>
        {{-- For Production --}}
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="bg-color-8-light-red-white hold-transition layout-fixed" style="height:500px;">
        @inertia
    </body>
</html>
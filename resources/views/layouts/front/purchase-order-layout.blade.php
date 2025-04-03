<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titlePage) ? $titlePage . ' | ' : '' }}{{ __('Smart Tools Egypt') }}</title>

    {{-- FavIcons --}}
    {{-- <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logos/smart-tools-logo-fav-only-50.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/smart-tools-logo-fav-only-50.png') }}"> --}}

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no'
        name='viewport' />

    {{-- Fonts --}}
    {{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> --}}

    {{-- <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700;900&display=swap" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Main CSS Files --}}
    {{-- <link href="{{ mix('assets/css/material-dashboard.min.css') }}" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}"> --}}
    {{-- @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{ mix('assets/css/material-dashboard-rtl.css') }}" rel="stylesheet" />
    @endif --}}

    {{-- Custom CSS :: Start --}}
    {{-- @stack('css') --}}
    {{-- Custom CSS :: End --}}
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="{{ $class ?? '' }}">
    @yield('content')

    <!--   Core JS Files   -->
    {{-- <script src="{{ mix('assets/js/app.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/front/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flowbite/dist/flowbite.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/core/bootstrap-material-design.min.js') }}"></script> --}}

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    {{-- <script src="{{ asset('assets/front/js/material-dashboard.min.js') }}" type="text/javascript"></script> --}}

    <!-- Plugin for the momentJs  -->
    {{-- <script src="{{ asset('assets/front/js/plugins/moment.min.js') }}"></script> --}}

    {{-- Custom Js Files --}}
    {{-- @stack('js') --}}
</body>

</html>

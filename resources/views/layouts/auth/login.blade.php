<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}"
    dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{  'Login | '. env('APP_NAME') }}</title>

    {{-- FavIcons --}}
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}">

    <!-- Font Icon -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/auth/fonts/material-icon/css/material-design-iconic-font.min.css') }}"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700;900&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link href="{{ asset('assets/admin/css/all.min.css') }}" rel="stylesheet" />


    <!-- Main css -->
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{ asset('assets/admin/css/material-dashboard-rtl.css') }}" rel="stylesheet" />
    @endif

</head>

<body>

    <div class="main">

        @yield('content')

    </div>

    <!-- JS -->
    <script src="{{ asset('assets/auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/auth/js/main.js') }}"></script>
    <script scr="{{ mix('assets/js/app.js') }}"></script>


    {{-- Font Awesome --}}
    <script src="{{ asset('assets/admin/js/plugins/all.min.js') }}"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

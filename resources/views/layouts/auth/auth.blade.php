<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}"
    dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title . ' | ' . env('APP_NAME') }}</title>

    {{-- FavIcons --}}
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}">

    <!-- Font Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700;900&display=swap" rel="stylesheet">

    <!-- Main css -->
    <link href="{{ mix('assets/css/material-dashboard.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{ mix('assets/css/material-dashboard-rtl.css') }}" rel="stylesheet" />
    @endif

</head>

<body>

    <div class="main">

        @yield('content')

    </div>

    <!-- JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script scr="{{ mix('assets/js/app.js') }}"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

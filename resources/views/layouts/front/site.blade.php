<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titlePage) ? $titlePage . ' | ' : '' }}{{ __('Smart Tools Egypt') }}</title>

    {{-- FavIcons --}}
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logos/smart-tools-logo-fav-only-50.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/smart-tools-logo-fav-only-50.png') }}">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />

    {{-- todo : facebook meta tags --}}
    <meta property="og:url" content='{{ isset($url) ? $url : 'https://smarttoolsegypt.com' }}' />
    <meta property="og:type" content='{{ isset($type) ? $type : 'article' }}' />
    <meta property="og:title" content='{{ isset($titlel) ? $titlel : 'Smart Tools Egypt' }}' />
    <meta property="og:description" content='{{ isset($description) ? $description : 'Smart Tools Egypt' }}' />
    <meta property="og:image" content="{{ isset($thumbnail) ? $thumbnail : asset('assets/img/logos/smart-tools-logos.png') }}" />

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700;900&display=swap" rel="stylesheet">
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap"
            rel="stylesheet">
    @endif

    {{-- Main CSS Files --}}
    <link href="{{ asset('assets/front/css/material-dashboard.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{ asset('assets/front/css/material-dashboard-rtl.css') }}" rel="stylesheet" />
    @endif

    {{-- Splide --}}
    <link href="{{ asset('assets/front/css/splide.min.css') }}" rel="stylesheet" />

    {{-- Livewire Styles :: Start --}}
    @livewireStyles
    {{-- Livewire Styles :: End --}}

    {{-- Custom CSS :: Start --}}
    @stack('css')
    {{-- Custom CSS :: End --}}

</head>

<body class="{{ $class ?? '' }}">
    <div class="wrapper pb-12 lg:pb-0">

        {{-- Top Banner :: Start --}}
        @livewire('front.homepage.top-banner')
        {{-- Top Banner :: End --}}

        {{-- Top Bar : Start --}}
        @include('layouts.front.includes.top_nav')
        {{-- Top Bar : End --}}

        {{-- Header : Start --}}
        @include('layouts.front.includes.header')
        {{-- Header : End --}}

        {{-- Main Body : Start --}}
        <div class="grid grid-cols-1">

            {{-- Content : Start --}}
            @yield('content')
            {{-- Content : End --}}

        </div>
        {{-- Main Body : End --}}

        {{-- Footer : Start --}}
        @include('layouts.front.includes.footer')
        {{-- Footer : End --}}

        {{-- Mobile Header : Start --}}
        @include('layouts.front.includes.mobile_header')
        {{-- Mobile Header : End --}}

    </div>


    <!--   Core JS Files   -->
    <script src="{{ asset('assets/front/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flowbite/flowbite.js') }}"></script>
    <script src="{{ asset('assets/front/js/core/bootstrap-material-design.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/front/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script> --}}

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/front/js/material-dashboard.min.js') }}" type="text/javascript"></script>

    <!--  Plugin for Sweet Alert -->
    <script src="{{ asset('assets/js/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Chartist JS -->
    <script src="{{ asset('assets/front/js/plugins/chartist.min.js') }}"></script>

    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('assets/front/js/plugins/moment.min.js') }}"></script>

    <!-- Plugin for the Splide  -->
    <script src="{{ asset('assets/front/js/plugins/splide.min.js') }}"></script>

    {{-- <!-- Forms Validations Plugin -->
        <script src="{{ asset('assets/front/js/plugins/jquery.validate.min.js') }}"></script>
        <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
        <script src="{{ asset('assets/front/js/plugins/jquery.bootstrap-wizard.js') }}"></script>
        <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
        <script src="{{ asset('assets/front/js/plugins/bootstrap-selectpicker.js') }}"></script>
        <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
        <script src="{{ asset('assets/front/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
        <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
        <script src="{{ asset('assets/front/js/plugins/jquery.dataTables.min.js') }}"></script>
        <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
        <script src="{{ asset('assets/front/js/plugins/bootstrap-tagsinput.js') }}"></script>
        <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
        <script src="{{ asset('assets/front/js/plugins/jasny-bootstrap.min.js') }}"></script>
        <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
        <script src="{{ asset('assets/front/js/plugins/fullcalendar.min.js') }}"></script>
        <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
        <script src="{{ asset('assets/front/js/plugins/jquery-jvectormap.js') }}"></script>
        <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
        <script src="{{ asset('assets/front/js/plugins/nouislider.min.js') }}"></script>
        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <!-- Library for adding dinamically elements -->
        <script src="{{ asset('assets/front/js/plugins/arrive.min.js') }}"></script>
        <!--  Google Maps Plugin    -->
        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE'"></script>
    <!--  Notifications Plugin    -->
        <script src="{{ asset('assets/front/js/plugins/bootstrap-notify.js') }}"></script> --}}

    <script>
        @if (Session::has('success'))
            Swal.fire({
                text: '{{ Session::get('success') }}',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            })
        @elseif (Session::has('error'))
            Swal.fire({
                text: '{{ Session::get('error') }}',
                icon: 'error',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            })
        @endif

        window.addEventListener('swalDone', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                position: 'top-right',
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });

        $(function() {
            $('.remove_banner_button').on('click', function(e) {
                e.stopPropagation();
                $(this).parent().fadeOut();
            })
        })
    </script>

    {{-- Custom Js Files --}}
    @stack('js')

    {{-- Custom Js Files for livewire blade --}}
    @stack('livewire-js')

    <!--  Plugin for the Livewire -->
    @livewireScripts

</body>

</html>

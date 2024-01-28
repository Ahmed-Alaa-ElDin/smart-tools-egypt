<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titlePage . ' | ' . __('Smart Tools Egypt') }}</title>

    {{-- FavIcons --}}
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logos/smart-tools-logo-fav-only-50.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/smart-tools-logo-fav-only-50.png') }}">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700;900&display=swap" rel="stylesheet">
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap"
            rel="stylesheet">
    @endif

    {{-- Main CSS Files --}}
    <link href="{{ mix('assets/css/material-dashboard.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    @if (LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{ mix('assets/css/material-dashboard-rtl.css') }}" rel="stylesheet" />
    @endif
    @livewireStyles

    @stack('css')

</head>

<body class="{{ $class ?? '' }}">
    @auth()
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <div class="wrapper ">
            @include('layouts.admin.includes.sidebar')
            <div class="main-panel">
                @include('layouts.admin.includes.nav')
                @yield('content')
                @include('layouts.admin.includes.footer')
            </div>
        </div>
    @endauth

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/admin/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flowbite/flowbite.js') }}"></script>
    <script src="{{ asset('assets/admin/js/core/bootstrap-material-design.min.js') }}"></script>
    @livewireScripts

    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/admin/js/material-dashboard.min.js') }}" type="text/javascript"></script>

    <!--  Plugin for Sweet Alert -->
    <script src="{{ asset('assets/js/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Chartist JS -->
    <script src="{{ asset('assets/admin/js/plugins/chartist.min.js') }}"></script>

    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('assets/admin/js/plugins/moment.min.js') }}"></script>

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

        // #### Sweetalert ####
        window.addEventListener('swalConfirm', function(e) {

            Swal.fire({
                icon: e.detail.icon,
                text: e.detail.text,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                focusDeny: e.detail.focusDeny,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit(e.detail.method, e.detail.id, e.detail.details || []);
                }
            });
        });

        window.addEventListener('swalDone', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                @if (session('locale' == 'en'))
                    position: 'top-left',
                @else
                    position: 'top-right',
                @endif
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
        // #### Sweetalert ####
    </script>

    {{-- Custom Js Files --}}
    @stack('js')
</body>

</html>

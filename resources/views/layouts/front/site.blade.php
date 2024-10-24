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
    <meta property="og:title" content='{{ isset($title) ? $title : 'Smart Tools Egypt' }}' />
    <meta property="og:description"
        content='{{ isset($description) ? strip_tags($description) : 'Smart Tools Egypt' }}' />
    <meta property="og:image"
        content="{{ isset($thumbnail) ? $thumbnail : asset('assets/img/logos/smart-tools-logos.png') }}" />

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

    {{-- Splide --}}
    <link href="{{ mix('assets/css/splide.min.css') }}" rel="stylesheet" />

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
        <div class="grid grid-cols-1 min-h-screen">

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
    <script src="{{ mix('assets/js/app.js') }}"></script>
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
        @elseif (Session::has('warning'))
            Swal.fire({
                text: '{{ Session::get('warning') }}',
                icon: 'warning',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            })
        @endif

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

        $(function() {
            $('.remove_banner_button').on('click', function(e) {
                e.stopPropagation();
                $(this).parent().fadeOut();
            })

            // Fade out Results on blur
            $('input[name="search"]').on('blur', function(e) {
                $('#typed-search-box').fadeOut();
            })

            // Fade In Results on Focus
            $('input[name="search"]').on('focus', function(e) {
                $('#typed-search-box').fadeIn();
            })

            $('input[name="search"]').on('keydown', function(e) {
                // Esc Button
                if (e.keyCode == 27) {
                    $('#typed-search-box').fadeOut();
                    $('input[name="search"]').blur();
                }
            })

            // Handle Not Found Images
            window.handleNotFoundImages = function(element) {
                const iconSize = element.dataset.placeholderSize;

                if (!element.complete ||
                    typeof element.naturalWidth === "undefined" ||
                    element.naturalWidth === 0) {

                    const parent = element.parentNode;

                    const placeholderHTML = '<div class="flex justify-center items-center bg-gray-100">' +
                        '<span class="block material-icons ' + iconSize + '">construction</span>' +
                        '</div>';

                    element.remove();

                    const placeholderElement = document.createElement('div');
                    placeholderElement.innerHTML = placeholderHTML;

                    parent.appendChild(placeholderElement.firstChild);
                }
            }

            // Handle Not Found Images
            document.querySelectorAll('.construction-placeholder').forEach(function(element) {
                handleNotFoundImages(element);
            });
        })
    </script>

    {{-- Custom Js Files --}}
    @stack('js')

    {{-- Custom Js Files for livewire blade --}}
    @stack('livewire-js')

</body>

</html>

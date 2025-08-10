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

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no' name='viewport' />

    {{-- TODO : facebook domain verification Need to remove after verification --}}
    <meta name="facebook-domain-verification" content="kvbjjv1cjdz1pftztks7joyfsrxq86" />

    {{-- TODO : facebook meta tags --}}
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

    <style>
        .scrollbar-zero {
            scrollbar-width: 0 !important;
            -ms-overflow-style: none !important;
        }

        .scrollbar-zero::-webkit-scrollbar {
            display: none;
        }
    </style>

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1174640216338366');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1174640216338366&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

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
        <div class="grid grid-cols-1 min-h-screen px-2 lg:px-0">

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
    <script src="{{ asset('assets/js/plugins/flowbite/dist/flowbite.min.js') }}"></script>
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
                text: '{{ Session::get('
                            success ') }}',
                icon: 'success',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            })
        @elseif (Session::has('error'))
            Swal.fire({
                text: '{{ Session::get('
                            error ') }}',
                icon: 'error',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
            })
        @elseif (Session::has('warning'))
            Swal.fire({
                text: '{{ Session::get('
                            warning ') }}',
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

        window.addEventListener('swalGetGuestPhone', function(e) {
            Swal.fire({
                title: e.detail.title,
                html: e.detail.html,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                preConfirm: () => {
                    return [
                        document.getElementById('guest_phone').value,
                    ]
                }
            }).then((result) => {
                console.log(result);

                if (result.isConfirmed) {
                    Livewire.dispatch(e.detail.method, {
                        phone: result.value[0],
                    })
                }
            });
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
        })

        // #### Initialize Image Handlers #### (Singleton Pattern)
        let observer; // Keep single observer instance
        let isInitialized = false;

        function initializeImageHandlers() {
            // Only initialize once
            if (isInitialized) return;
            isInitialized = true;

            const lazyLoad = function(image) {

                image.onerror = function() {
                    const iconSize = image.dataset.placeholderSize || 'text-2xl';
                    const placeholderHTML =
                        `<div class="flex justify-center items-center bg-gray-100">
                            <span class="block material-icons ${iconSize}">construction</span>
                        </div>`;

                    const parent = image.parentNode;
                    const placeholderElement = document.createElement('div');
                    placeholderElement.innerHTML = placeholderHTML;

                    image.remove();
                    parent.appendChild(placeholderElement.firstChild);
                };
            };

            // Create single IntersectionObserver instance
            observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        lazyLoad(img);
                        observer.unobserve(img);
                    }
                });
            });

            // Function to observe new images
            function observeNewImages() {
                const lazyImages = document.querySelectorAll('.construction-placeholder:not([data-observed])');

                lazyImages.forEach(img => {
                    img.dataset.observed = true;
                    observer.observe(img);
                });
            }

            // Livewire v3 hook for DOM updates
            Livewire.hook('commit', ({
                component,
                commit,
                respond,
                succeed,
                fail
            }) => {
                succeed(() => {
                    // Wait for DOM to update
                    setTimeout(observeNewImages, 0);
                });
            });

            // Initial observation
            observeNewImages();
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initializeImageHandlers);

        // Re-initialize when Livewire connects (for turbo visits)
        document.addEventListener('livewire:init', () => {
            initializeImageHandlers();
        });
    </script>

    {{-- Custom Js Files --}}
    @stack('js')

    {{-- Custom Js Files for livewire blade --}}
    @stack('livewire-js')

</body>

</html>

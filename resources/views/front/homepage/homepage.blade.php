@extends('layouts.front.site', ['titlePage' => __('front/homePage.Homepage')])

@section('content')
    {{-- Main Slider : Start --}}
    @livewire('front.homepage.main-slider', ['todayDeals' => $today_deals_sections])
    {{-- Main Slider : End --}}

    @foreach ($homepage_sections as $section)
        @if ($section->type == 0)
            @livewire('front.homepage.products-list', ['section' => $section])
        @elseif ($section->type == 1)
            @livewire('front.homepage.offers-products-list', ['section' => $section, 'flash_sale' => false])
        @elseif ($section->type == 2)
            @livewire('front.homepage.offers-products-list', ['section' => $section, 'flash_sale' => true])
        @elseif ($section->type == 3)
            Banner
        @endif
    @endforeach
    {{-- Offer Bar : Start --}}
    @include('layouts.front.includes.offer_bar')
    {{-- Offer Bar : End --}}

    {{-- Top Categories & Brands : Start --}}
    @include('layouts.front.includes.top_categories_brands')
    {{-- Top Categories & Brands : End --}}
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // ####### Main Slider :: Start #######
            var main_slider = new Splide('#main-slider', {
                @if (LaravelLocalization::getCurrentLocale() == 'ar')
                    direction: 'rtl',
                    pagination: 'rtl',
                @else
                    pagination: 'ltr',
                @endif
                autoplay: true,
                type: 'loop',
                keyboard: true,
                cover: true,
                height: "inherit",
            });
            main_slider.mount();
            // ####### Main Slider :: End #######
            // ####### Products Slider :: Start #######
            var splide_options = {
                @if (LaravelLocalization::getCurrentLocale() == 'ar')
                    direction: 'rtl',
                    pagination: 'rtl',
                @else
                    pagination: 'ltr',
                @endif
                perPage: 5,
                perMove: 2,
                drag: 'free',
                breakpoints: {
                    1200: {
                        perPage: 3,
                    },
                    770: {
                        perPage: 2,
                    }
                },
                type: 'slide',
                keyboard: true,
                cover: true,
                gap: 15,
                height: "inherit",
            };

            var product_lists = $('.product_list');

            for (let i = 0; i < product_lists.length; i++) {
                new Splide(product_lists[i], splide_options).mount();
            }
            // ####### Products Slider :: End #######

            // ####### Timer :: Start #######

            $('.timer').each(function() {
                var countDownDate = new Date($(this).data('date')).getTime();

                var x = setInterval(() => {
                    var now = new Date().getTime();

                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $(this).find('.days').text(days);
                    $(this).find('.hours').text(hours);
                    $(this).find('.minutes').text(minutes);
                    $(this).find('.seconds').text(seconds);

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                    }
                }, 1000);
            });
            // ####### Timer :: End #######
        });
    </script>
@endpush

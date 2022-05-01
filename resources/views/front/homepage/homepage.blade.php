@extends('layouts.front.site', ['titlePage' =>
__('front/homePage.Homepage')])

@section('content')
    {{-- Main Slider : Start --}}
    @livewire('front.homepage.main-slider')
    {{-- Main Slider : End --}}

    {{-- Offer Bar : Start --}}
    @include('layouts.front.includes.offer_bar')
    {{-- Offer Bar : End --}}

    {{-- Flash Sale : Start --}}
    @include('layouts.front.includes.flash_sale')
    {{-- Flash Sale : End --}}

    {{-- Top Categories & Brands : Start --}}
    @include('layouts.front.includes.top_categories_brands')
    {{-- Top Categories & Brands : End --}}
@endsection

@push('js')
    <script>
        $(document).ready(function() {
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

            var flash_sale_slider = new Splide('#flash-sale-slider', {
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
                    },
                    500: {
                        perPage: 1,
                    },
                },
                type: 'slide',
                keyboard: true,
                cover: true,
                height: "inherit",
            });
            flash_sale_slider.mount();
        });
    </script>
@endpush

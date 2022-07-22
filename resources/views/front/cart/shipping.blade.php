@extends('layouts.front.site', ['titlePage' => __('front/homePage.Order Shipping Details')])

@section('cart-wishlist-compare')
    <div class="grow text-center font-bold text-primary">
        {{ __('front/homePage.Checkout') }}
    </div>
@endsection

@section('content')
    {{-- Unauthourized customers --}}
    @guest
        <div class="fixed top-0 left-0 w-screen h-screen backdrop-blur-sm z-50">
            @livewire('front.order.register-from-order')
        </div>
    @endguest

    <div class="container p-4">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 order-2 md:col-span-8 md:order-none flex flex-col gap-5 self-start">

                {{-- ############## Order Steps :: Start ############## --}}
                @livewire('front.order.general.order-steps', ['step' => 2])
                {{-- ############## Order Steps :: End ############## --}}

                @auth()
                    {{-- ############## Order Shipping Details :: Start ############## --}}
                    <div class="bg-white rounded overflow-hidden">
                        {{-- ############## Title :: Start ############## --}}
                        <div class="flex justify-between items-center">
                            <h3 class="h5 text-center font-bold p-4 m-0">
                                {{ __('front/homePage.Order Shipping Details') }}
                            </h3>
                        </div>
                        {{-- ############## Title :: End ############## --}}

                        <hr>

                        {{-- ############## Order Shipping Details :: Start ############## --}}
                        @livewire('front.order.order-shipping-details')
                        {{-- ############## Order Shipping Details :: End ############## --}}
                    </div>
                    {{-- ############## Order Shipping Details :: End ############## --}}
                @endauth
            </div>

            {{-- ############## Order Summary :: Start ############## --}}
            <div class="col-span-12 md:col-span-4 md:order-none bg-white rounded overflow-hidden self-start">
                @livewire('front.order.general.order-summary', ['products' => $cart_products, 'step' => 2])
            </div>
            {{-- ############## Order Summary :: End ############## --}}
        </div>

        {{-- todo: Other Product Suggestions (Similar Products, Related Products, etc.) in the Cart Page (if any) --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {
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
                        $('.timer').addClass('hidden');
                        $('.expired').removeClass('hidden');
                    }
                }, 1000);
            });
            // ####### Timer :: End #######
        });
    </script>
@endpush

@extends('layouts.front.site', ['titlePage' => __('front/homePage.Order Billing Details')])

@section('cart-wishlist-compare')
    <div class="grow text-center font-bold text-primary">
        {{ __('front/homePage.Checkout') }}
    </div>
@endsection

@section('content')
    <div class="container p-4">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 order-2 md:col-span-8 md:order-none flex flex-col gap-5 self-start">

                {{-- ############## Order Steps :: Start ############## --}}
                @livewire('front.order.general.order-steps', ['step' => 3])
                {{-- ############## Order Steps :: End ############## --}}

                @auth()
                    {{-- ############## Order Billing Details :: Start ############## --}}
                    <div class="bg-white rounded overflow-hidden">
                        {{-- ############## Title :: Start ############## --}}
                        <div class="flex justify-between items-center">
                            <h3 class="h5 text-center font-bold p-4 m-0">
                                {{ __('front/homePage.Order Billing Details') }}
                            </h3>
                        </div>
                        {{-- ############## Title :: End ############## --}}

                        <hr>

                        {{-- ############## Order Billing Details :: Start ############## --}}
                        @livewire('front.order.order-billing-details')
                        {{-- ############## Order Billing Details :: End ############## --}}
                    </div>
                    {{-- ############## Order Billing Details :: End ############## --}}
                @endauth
            </div>

            {{-- ############## Order Summary :: Start ############## --}}
            <div class="col-span-12 md:col-span-4 md:order-none bg-white rounded overflow-hidden self-start">
                @livewire('front.order.general.order-summary', ['step' => 3])
            </div>
            {{-- ############## Order Summary :: End ############## --}}
        </div>

        {{-- todo: Other Product Suggestions (Similar Products, Related Products, etc.) in the Cart Page (if any) --}}
    </div>
@endsection

{{-- Extra Scripts --}}


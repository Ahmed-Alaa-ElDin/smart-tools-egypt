@extends('layouts.front.site', ['titlePage' => __('front/homePage.Shopping Cart')])

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Main Content Column --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Cart Items Section --}}
                <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="material-icons text-primary">shopping_cart</span>
                            {{ __('front/homePage.Shopping Cart') }}
                        </h3>
                    </div>

                    <div class="p-2 sm:p-6">
                        @livewire('front.cart.cart-products-list', ['items' => $cart_items])
                    </div>
                </div>

                {{-- Wishlist Section --}}
                <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100/50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="material-icons text-primary">favorite_border</span>
                            {{ __('front/homePage.Wishlist') }}
                        </h3>
                    </div>

                    <div class="p-2 sm:p-6 text-gray-400">
                        @livewire('front.cart.cart-wishlist-products-list', ['items' => $wishlist_items])
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="sticky top-24">
                    @livewire('front.cart.cart-summary', ['items' => $cart_items])
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.front.site', ['titlePage' => __('front/homePage.Checkout')])

@section('cart-wishlist-compare')
    <div class="grow text-center font-bold text-primary">
        {{ __('front/homePage.Checkout') }}
    </div>
@endsection

@section('content')
    <div class="py-4">
        @livewire('front.order.order-form.wrapper')
    </div>
@endsection

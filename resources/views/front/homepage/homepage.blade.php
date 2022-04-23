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

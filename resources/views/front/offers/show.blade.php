@extends('layouts.front.site', [
    'titlePage' => $offer->title,
    'url' => route('front.offers.show', ['offer' => $offer->id]),
    'title' => $offer->title,
    'description' => __('front/homePage.General Offer Description'),
    'thumbnail' => $offer->banner ? asset("storage/images/banners/cropped150/{$offer->banner}") : asset('assets/img/logos/smart-tools-logos.png'),
])

@php
    $now = Carbon\Carbon::now('Africa/Cairo');
    $date = $offer->expire_at ? Carbon\Carbon::parse($offer->expire_at, 'Africa/Cairo') : Carbon\Carbon::now('Africa/Cairo');

    $diff = $now->diffInSeconds($date, false);
    $diffDays = floor($diff / (60 * 60 * 24));
    $diffHours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
    $diffMinutes = floor(($diff % (60 * 60)) / 60);
    $diffSeconds = floor($diff % 60);
@endphp

@section('content')
    <div class="container px-4 py-2 ">
        {{-- Breadcrumb :: Start --}}
        <nav aria-label="breadcrumb" role="navigation" class="mb-2">
            <ol class="breadcrumb text-sm">
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.homepage') }}">
                        {{ __('front/homePage.Homepage') }}
                    </a>
                </li>
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.offers.index') }}">
                        {{ __('front/homePage.All Offers') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ $offer->title }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Offer :: Start --}}
        <section class="bg-white rounded shadow-lg p-4">
            {{-- Offer Header :: Start  --}}
            <div class="border-b border-gray-300">
                <div class="flex justify-start items-center gap-4 p-3 border-b-2 border-primary max-w-max">
                    <div>
                        {{-- Banner --}}
                        @if ($offer->banner)
                            <div class="w-24 md:w-64">
                                <img class="rounded-lg  m-0"
                                    src="{{ asset('storage/images/banners/cropped150/' . $offer->banner) }}"
                                    alt="{{ $offer->title }}">
                            </div>
                        @else
                            <div class="w-24 md:w-64">
                                <img class="rounded-lg  m-0"
                                    src="{{ asset('storage/images/banners/original/offer_placeholder.jpg') }}"
                                    alt="{{ $offer->title }}">
                            </div>
                        @endif
                        {{-- Title --}}
                    </div>
                    <span class="inline-bolck text-secondaryDarker text-xl font-bold">
                        {{ $offer->title }}
                    </span>
                </div>
            </div>
            {{-- Offer Header :: End  --}}

            {{-- Offer's Items :: Start --}}
            <div class="p-3">
                @if (count($productsIds) || count($collectionsIds))
                    @livewire('front.products.general-products-list', ['productsIds' => $productsIds, 'collectionsIds' => $collectionsIds])
                @else
                    <div class="mt-5 mb-3 text-center text-lg font-bold text-gray-600">
                        {{ __('front/homePage.No products in this offer') }}
                    </div>
                @endif
            </div>
            {{-- Offer's Items :: End --}}

        </section>
        {{-- Offer :: Start --}}
    </div>
@endsection

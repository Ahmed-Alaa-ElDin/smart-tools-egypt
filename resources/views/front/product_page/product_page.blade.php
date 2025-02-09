@extends('layouts.front.site', [
    'titlePage' => __('front/homePage.Product Page Title', ['product_name' => $product->name]),
    'url' => "https://www.smarttoolsegypt.com/$product->id-$product->slug",
    'title' => $product->name,
    'description' => $product->description,
    'thumbnail' => $product->thumbnail,
])

@section('content')
    <main class="container px-4 py-2 flex flex-col gap-3">
        {{-- Breadcrumb :: Start --}}
        <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb text-sm">
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.homepage') }}">
                        {{ __('front/homePage.Homepage') }}
                    </a>
                </li>
                @if ($product->brand)
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('front.brands.show', $product->brand_id) }}">
                            {{ $product->brand->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ $product->name }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Product :: Start --}}
        <section class="grid grid-cols-12 justify-between items-start gap-3 bg-white rounded shadow-lg p-4">

            {{-- Product Image :: Start --}}
            <div class="col-span-12 md:col-span-4">
                @if ($product->images->count())
                    <div id="main-slider" class="splide mb-3">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($product->images as $image)
                                    <li class="splide__slide zoom">
                                        <img src="{{ asset('storage/images/products/original/' . $image->file_name) }}"
                                            alt="{{ $product->name }}" class="m-auto">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div id="thumbnail-slider" class="splide">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($product->images as $image)
                                    <li class="splide__slide">
                                        <img src="{{ asset('storage/images/products/cropped100/' . $image->file_name) }}"
                                            alt="{{ $product->name }}">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="flex justify-center items-center bg-gray-100">
                        <span class="block material-icons text-[300px]">
                            construction
                        </span>
                    </div>
                @endif
            </div>
            {{-- Product Image :: End --}}

            {{-- Product Info :: Start --}}
            <div class="col-span-12 md:col-span-8 grid grid-cols-12 justify-between items-start gap-3">
                <div class="col-span-12 p-4">
                    {{-- Product Name :: Start --}}
                    <h1 class="text-2xl font-bold mb-2 text-gray-700">
                        {{ $product->name }}
                    </h1>
                    {{-- Product Name :: End --}}

                    {{-- Product Brand & Model :: Start --}}
                    <div class="flex justify-start items-center gap-3">
                        @if ($product->brand)
                            <h2 class="text-gray-800 font-bold">
                                <a href="{{ route('front.brands.show', $product->brand->id) }}">
                                    {{ $product->brand->name }}
                                </a>
                            </h2>
                        @endif
                        <h3 class="text-gray-500 font-bold">
                            {{ $product->model }}
                        </h3>
                    </div>
                    {{-- Product Brand & Model :: End --}}

                    <hr class="my-4">

                    {{-- Product Price :: Start --}}
                    <div class="flex flex-col gap-2">
                        {{-- Base Price :: Start --}}
                        <div class="flex gap-3 items-center">
                            <span class="text-gray-800 font-bold text-md">
                                {{ __('front/homePage.Before Discount: ') }}
                            </span>
                            <del class="flex rtl:flex-row-reverse gap-1 font-bold text-red-400 text-sm">
                                <span>
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-3xl"
                                    dir="ltr">{{ number_format(explode('.', $product->base_price)[0], 0, '.', '\'') }}</span>
                            </del>
                        </div>
                        {{-- Base Price :: End --}}

                        {{-- Discount Price :: Start --}}
                        <div class="flex gap-3 items-center">
                            <span class="text-gray-800 font-bold text-md">
                                {{ __('front/homePage.After Discount: ') }}
                            </span>
                            <span class="flex rtl:flex-row-reverse gap-1 font-bold text-successDark text-sm">
                                <span>
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-2xl"
                                    dir="ltr">{{ number_format(explode('.', $product->final_price)[0], 0, '.', '\'') }}</span>
                                <span class="font-bold text-xs">{{ explode('.', $product->final_price)[1] ?? '00' }}</span>
                            </span>
                        </div>
                        {{-- Discount Price :: End --}}

                        {{-- Disount Amount :: Start --}}
                        <div class="flex gap-3 items-center">
                            <span class="text-gray-800 font-bold text-md">
                                {{ __('front/homePage.You Saved: ') }}
                            </span>
                            <span class="flex rtl:flex-row-reverse gap-1 font-bold text-success text-sm">
                                <span
                                    class="font-bold text-lg">(%{{ number_format((($product->base_price - $product->final_price) * 100) / $product->base_price, 0) ?? '%0' }})</span>
                                &nbsp;
                                <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $product->base_price - $product->final_price)[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', $product->base_price - $product->final_price)[1] ?? '00' }}</span>
                            </span>
                        </div>
                        {{-- Disount Amount :: End --}}

                        {{-- Extra Discount :: Start --}}
                        @if ($productOffer->best_price < $product->final_price)
                            <div class="flex gap-3 items-center">
                                <span class="text-gray-800 font-bold text-md">
                                    {{ __('front/homePage.Extra Discount: ') }}
                                </span>
                                <span class="flex rtl:flex-row-reverse gap-1 font-bold text-success text-sm">
                                    <span
                                        class="font-bold text-lg">(%{{ number_format((($product->final_price - $productOffer->best_price) * 100) / $product->final_price, 0) ?? '%0' }})</span>
                                    &nbsp;
                                    <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                    <span class="font-bold text-xl"
                                        dir="ltr">{{ number_format(explode('.', $product->final_price - $productOffer->best_price)[0], 0, '.', '\'') }}</span>
                                    <span
                                        class="font-bold text-xs">{{ explode('.', $product->final_price - $productOffer->best_price)[1] ?? '00' }}</span>
                                </span>
                            </div>
                        @endif
                        {{-- Extra Discount :: End --}}

                        {{-- Extra Points :: Start --}}
                        @if ($productOffer->best_points)
                            <div class="flex gap-3 items-center">
                                <span class="text-gray-800 font-bold text-md">
                                    {{ __("front/homePage.You'll get: ") }}
                                </span>
                                <span class="flex rtl:flex-row-reverse gap-1 font-bold text-success text-sm">
                                    <span class="font-bold text-lg">
                                        <span dir="ltr">
                                            {{ number_format($productOffer->best_points, 0, '.', '\'') ?? 0 }}
                                        </span>
                                        &nbsp;
                                        {{ trans_choice('front/homePage.Point/Points', $productOffer->best_points, ['points' => $productOffer->best_points]) }}
                                    </span>
                                </span>
                            </div>
                        @endif
                        {{-- Extra Points :: End --}}
                    </div>
                    {{-- Product Price :: End --}}

                    <hr class="my-4">

                    {{-- Banner :: Start --}}
                    {{-- todo : Banner --}}
                    {{-- Banner :: End --}}

                    <hr class="my-4">

                    <div class="grid grid-cols-12 gap-4 items-center justify-around md:justify-between mt-4">
                        {{-- Add Product : Start --}}
                        <div class="col-span-12 md:col-span-8 flex flex-col justify-center gap-3">
                            {{-- Add to cart : Start --}}
                            @livewire(
                                'front.general.cart.add-to-cart-button',
                                [
                                    'item_id' => $product['id'],
                                    'text' => true,
                                    'large' => true,
                                    'type' => 'Product',
                                    'add_buy' => 'add',
                                ],
                                key('add-cart-button-' . Str::random(10))
                            )
                            {{-- Add to cart : End --}}

                            {{-- Go To Payment : Start --}}
                            @livewire(
                                'front.general.cart.add-to-cart-button',
                                [
                                    'item_id' => $product['id'],
                                    'text' => true,
                                    'large' => true,
                                    'type' => 'Product',
                                    'add_buy' => 'pay',
                                ],
                                key('go-to-cart-button-' . Str::random(10))
                            )
                            {{-- Go To Payment : End --}}

                            <div class="flex flex-wrap justify-around gap-2">
                                {{-- Add to compare : Start --}}
                                @livewire(
                                    'front.general.compare.add-to-compare-button',
                                    [
                                        'item_id' => $product['id'],
                                        'text' => true,
                                        'large' => true,
                                        'type' => 'Product',
                                    ],
                                    key('add-compare-button-' . Str::random(10))
                                )
                                {{-- Add to compare : End --}}

                                {{-- Add to wishlist : Start --}}
                                @livewire(
                                    'front.general.wishlist.add-to-wishlist-button',
                                    [
                                        'item_id' => $product['id'],
                                        'text' => true,
                                        'large' => true,
                                        'type' => 'Product',
                                    ],
                                    key('add-wishlist-button-' . Str::random(10))
                                )
                                {{-- Add to wishlist : End --}}
                            </div>
                        </div>
                        {{-- Add Product : End --}}

                        <div class="col-span-12 md:col-span-4 flex md:flex-col gap-3 justify-between items-center">
                            {{-- Cart Amount :: Start --}}
                            <div>
                                @livewire(
                                    'front.general.cart.cart-amount',
                                    [
                                        'item_id' => $product->id,
                                        'unique' => 'item-' . $product->id,
                                        'type' => 'Product',
                                        'title' => false,
                                    ],
                                    key($product->name . '-' . rand())
                                )
                            </div>
                            {{-- Cart Amount :: End --}}

                            {{-- Product Share :: Start --}}
                            <div class="flex justify-center gap-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ 'https://smarttoolsegypt.com/' . session('locale') . "/$product->id-$product->slug" }}&display=popup"
                                    target="_blank" title="{{ __('front/homePage.Share on Facebook') }}"
                                    class="w-9 h-9 bg-facebook rounded-circle flex justify-center items-center text-white shadow transition ease-in-out hover:bg-white hover:text-facebook hover:border hover:border-facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"
                                        class="text-xl">
                                        <path fill="currentColor"
                                            d="M8.46 18h2.93v-7.3h2.45l.37-2.84h-2.82V6.04c0-.82.23-1.38 1.41-1.38h1.51V2.11c-.26-.03-1.15-.11-2.19-.11c-2.18 0-3.66 1.33-3.66 3.76v2.1H6v2.84h2.46V18z" />
                                    </svg>
                                </a>

                                <button type="button" id="copyToClipboard" title="{{ __('front/homePage.Copy link') }}"
                                    class="w-9 h-9 bg-white text-gray-800 border border-gray-200 rounded-circle flex justify-center items-center shadow transition ease-in-out hover:bg-secondary hover:text-white hover:border hover:border-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path
                                                d="M13.544 10.456a4.368 4.368 0 0 0-6.176 0l-3.089 3.088a4.367 4.367 0 1 0 6.177 6.177L12 18.177" />
                                            <path
                                                d="M10.456 13.544a4.368 4.368 0 0 0 6.176 0l3.089-3.088a4.367 4.367 0 1 0-6.177-6.177L12 5.823" />
                                        </g>
                                    </svg>
                                </button>
                            </div>
                            {{-- Product Share :: End --}}
                        </div>
                    </div>

                    @if ($product->description)
                        <hr class="my-4">

                        {{-- Product Description :: Start --}}
                        <div class="text-gray-800 description">
                            <h3 class="text-md font-bold mb-2">
                                {{ __('front/homePage.Why invest') . ' 👇 💪' }}
                            </h3>
                            {!! $product->description !!}
                        </div>
                        {{-- Product Description :: End --}}
                    @endif
                </div>
            </div>
            {{-- Product Info :: End --}}

            {{-- Product Other Info :: Start --}}
            <div class="col-span-12">
                <div class="mb-4 border-b-2 border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap gap-3 justify-around -mb-0.5 text-sm font-medium text-center" id="myTab"
                        data-tabs-toggle="#myTabContent" role="tablist">
                        {{-- Video Header :: Start --}}
                        @if ($product->video)
                            <li role="presentation">
                                <button
                                    class="inline-flex gap-2 items-center p-4 border-b-2 hover:text-gray-600 hover:border-gray-300"
                                    id="video-tab" data-tabs-target="#video" type="button" role="tab"
                                    aria-controls="video" aria-selected="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-lg" aria-hidden="true"
                                        role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 1024 1024">
                                        <path fill="currentColor"
                                            d="M941.3 296.1a112.3 112.3 0 0 0-79.2-79.3C792.2 198 512 198 512 198s-280.2 0-350.1 18.7A112.12 112.12 0 0 0 82.7 296C64 366 64 512 64 512s0 146 18.7 215.9c10.3 38.6 40.7 69 79.2 79.3C231.8 826 512 826 512 826s280.2 0 350.1-18.8c38.6-10.3 68.9-40.7 79.2-79.3C960 658 960 512 960 512s0-146-18.7-215.9zM423 646V378l232 133l-232 135z" />
                                    </svg>

                                    <span class="font-bold">
                                        {{ __('front/homePage.Video') }}
                                    </span>
                                </button>
                            </li>
                        @endif
                        {{-- Video Header :: End --}}

                        {{-- Specifications Header :: Start --}}
                        @if (count($product->specs))
                            <li role="presentation">
                                <button
                                    class="inline-flex gap-2 items-center p-4 border-b-2 hover:text-gray-600 hover:border-gray-300"
                                    id="specs-tab" data-tabs-target="#specs" type="button" role="tab"
                                    aria-controls="specs" aria-selected="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-lg" aria-hidden="true"
                                        role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        class="text-lg" viewBox="0 0 1024 1024">
                                        <path fill="currentColor"
                                            d="M880 112H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656zM492 400h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H492c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8zm0 144h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H492c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8zm0 144h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H492c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8zM340 368a40 40 0 1 0 80 0a40 40 0 1 0-80 0zm0 144a40 40 0 1 0 80 0a40 40 0 1 0-80 0zm0 144a40 40 0 1 0 80 0a40 40 0 1 0-80 0z" />
                                    </svg>

                                    <span class="font-bold">
                                        {{ __('front/homePage.Specifications') }}
                                    </span>
                                </button>
                            </li>
                        @endif
                        {{-- Specifications Header :: End --}}

                        {{-- Reviews Header :: Start --}}
                        <li role="presentation">
                            <button
                                class="inline-flex gap-2 items-center p-4 border-b-2 hover:text-gray-600 hover:border-gray-300"
                                id="reviews-tab" data-tabs-target="#reviews" type="button" role="tab"
                                aria-controls="reviews" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-lg" aria-hidden="true"
                                    role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 1024 1024">
                                    <path fill="currentColor"
                                        d="m908.1 353.1l-253.9-36.9L540.7 86.1c-3.1-6.3-8.2-11.4-14.5-14.5c-15.8-7.8-35-1.3-42.9 14.5L369.8 316.2l-253.9 36.9c-7 1-13.4 4.3-18.3 9.3a32.05 32.05 0 0 0 .6 45.3l183.7 179.1l-43.4 252.9a31.95 31.95 0 0 0 46.4 33.7L512 754l227.1 119.4c6.2 3.3 13.4 4.4 20.3 3.2c17.4-3 29.1-19.5 26.1-36.9l-43.4-252.9l183.7-179.1c5-4.9 8.3-11.3 9.3-18.3c2.7-17.5-9.5-33.7-27-36.3z" />
                                </svg>

                                <span class="font-bold">
                                    {{ __('front/homePage.Reviews') }}
                                </span>
                            </button>
                        </li>
                        {{-- Reviews Header :: End --}}

                        {{-- FAQ Header :: Start --}}
                        <li role="presentation">
                            <button
                                class="inline-flex gap-2 items-center p-4 border-b-2 hover:text-gray-600 hover:border-gray-300"
                                id="faq-tab" data-tabs-target="#faq" type="button" role="tab"
                                aria-controls="faq" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-xl" aria-hidden="true"
                                    role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 26 26">
                                    <path fill="currentColor"
                                        d="M13 0c-1.7 0-3 1.3-3 3v6c0 1.7 1.3 3 3 3h6l4 4v-4c1.7 0 3-1.3 3-3V3c0-1.7-1.3-3-3-3H13zm4.188 3h1.718l1.688 6h-1.5l-.407-1.5h-1.5L16.813 9H15.5l1.688-6zM18 4c-.1.4-.212.888-.313 1.188l-.28 1.312h1.187l-.282-1.313C18.113 4.888 18 4.4 18 4zM3 10c-1.7 0-3 1.3-3 3v6c0 1.7 1.3 3 3 3v4l4-4h6c1.7 0 3-1.3 3-3v-6h-3c-1.9 0-3.406-1.3-3.906-3H3zm4.594 2.906c1.7 0 2.5 1.4 2.5 3c0 1.4-.481 2.288-1.281 2.688c.4.2.874.306 1.374.406l-.374 1c-.7-.2-1.426-.512-2.126-.813c-.1-.1-.275-.093-.375-.093C6.112 18.994 5 18 5 16c0-1.7.994-3.094 2.594-3.094zm0 1.094c-.8 0-1.188.9-1.188 2c0 1.2.388 2 1.188 2c.8 0 1.218-.9 1.218-2s-.418-2-1.218-2z" />
                                </svg>

                                <span class="font-bold">
                                    {{ __('front/homePage.FAQ') }}
                                </span>
                            </button>
                        </li>
                        {{-- FAQ Header :: End --}}

                        {{-- Delivery Cost Header :: Start --}}
                        <li role="presentation">
                            <button
                                class="inline-flex gap-2 items-center p-4 border-b-2 hover:text-gray-600 hover:border-gray-300"
                                id="delivery-cost-tab" data-tabs-target="#delivery-cost" type="button" role="tab"
                                aria-controls="faq" aria-selected="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M19.15 8a2 2 0 0 0-1.72-1H15V5a1 1 0 0 0-1-1H4a2 2 0 0 0-2 2v10a2 2 0 0 0 1 1.73a3.49 3.49 0 0 0 7 .27h3.1a3.48 3.48 0 0 0 6.9 0a2 2 0 0 0 2-2v-3a1.07 1.07 0 0 0-.14-.52zM15 9h2.43l1.8 3H15zM6.5 19A1.5 1.5 0 1 1 8 17.5A1.5 1.5 0 0 1 6.5 19zm10 0a1.5 1.5 0 1 1 1.5-1.5a1.5 1.5 0 0 1-1.5 1.5z" />
                                </svg>

                                <span class="font-bold">
                                    {{ __('front/homePage.Delivery Cost') }}
                                </span>
                            </button>
                        </li>
                        {{-- Delivery Cost Header :: End --}}
                    </ul>
                </div>

                <div id="myTabContent">
                    {{-- Video Body :: Start --}}
                    @if ($product->video)
                        <div class="hidden p-4 rounded-lg flex items-center justify-center" id="video"
                            role="tabpanel" aria-labelledby="video-tab">
                            <div class="w-full md:w-1/2">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://www.youtube.com/embed/{{ $product->video }}"
                                        title="{{ $product->name }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Video Body :: End --}}

                    {{-- Specifications Body :: Start --}}
                    @if ($product->specs)
                        <div class="hidden p-4 rounded-lg flex items-center justify-center relative overflow-x-auto shadow-md sm:rounded-lg"
                            id="specs" role="tabpanel" aria-labelledby="specs-tab">
                            <table class="min-w-[50%] text-sm text-left text-gray-700">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-center font-bold">
                                            {{ __('front/homePage.Specification') }}
                                        </th>
                                        <th class="px-6 py-3 text-center font-bold">
                                            {{ __('front/homePage.Value') }}
                                        </th>
                                    </tr>
                                </thead>
                                @foreach ($product->specs as $spec)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap text-center">
                                            {{ $spec->getTranslation('title', $locale) }}
                                        </th>
                                        <td class="px-6 py-4 text-center">
                                            {{ $spec->getTranslation('value', $locale) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                    {{-- Specifications Body :: End --}}

                    {{-- Reviews Body :: Start --}}
                    <div class="hidden p-4 rounded-lg flex items-center justify-center" id="reviews" role="tabpanel"
                        aria-labelledby="reviews-tab">
                        @livewire('front.product.review.review-block', ['item_id' => $product->id, 'type' => 'Product', 'reviews' => $product->reviews])
                    </div>
                    {{-- Reviews Body :: End --}}

                    {{-- FAQ Body :: Start --}}
                    <div class="hidden p-4 rounded-lg flex items-center justify-center" id="faq" role="tabpanel"
                        aria-labelledby="faq-tab">
                        <div id="accordion-collapse" data-accordion="collapse" class="w-full md:w-1/2">
                            <h2 id="how-to-buy-heading">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium text-left border border-gray-200 rounded-t-xl focus:ring-2 focus:ring-gray-200 hover:bg-gray-100 bg-gray-100 text-gray-900"
                                    data-accordion-target="#how-to-buy-body" aria-expanded="true"
                                    aria-controls="how-to-buy-body">
                                    <span>{!! __('front/homePage.How to buy Q', ['product_name' => $product->name]) !!}</span>
                                    <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </h2>
                            <div id="how-to-buy-body" class="" aria-labelledby="how-to-buy-heading">
                                <div class="p-5 font-light border border-gray-200">
                                    <p class="mb-2 text-gray-500 dark:text-gray-400">
                                        {!! __('front/homePage.How to buy A', [
                                            'product_name' => $product->name,
                                            'icon' =>
                                                '<a href="https://wa.me/+2' .
                                                config('settings.whatsapp_number') .
                                                '" target="_blank" class="inline-flex text-whatsapp"> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                                        ]) !!}
                                    </p>
                                </div>
                            </div>

                            <h2 id="how-much-heading">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium text-left border border-gray-200 focus:ring-2 focus:ring-gray-200 hover:bg-gray-100 bg-gray-100 text-gray-900"
                                    data-accordion-target="#how-much-body" aria-expanded="true"
                                    aria-controls="how-much-body">
                                    <span>{!! __('front/homePage.How much Q', ['product_name' => $product->name]) !!}</span>
                                    <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </h2>
                            <div id="how-much-body" class="" aria-labelledby="how-much-heading">
                                <div class="p-5 font-light border border-gray-200">
                                    <p class="mb-2 text-gray-500 dark:text-gray-400">
                                        {!! __('front/homePage.How much A', ['product_name' => $product->name]) !!}
                                    </p>
                                </div>
                            </div>

                            <h2 id="warranty-heading">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium text-left border border-gray-200 focus:ring-2 focus:ring-gray-200 hover:bg-gray-100 bg-gray-100 text-gray-900"
                                    data-accordion-target="#warranty-body" aria-expanded="false"
                                    aria-controls="warranty-body">
                                    <span>{!! __('front/homePage.warranty Q', ['product_name' => $product->name]) !!}</span>
                                    <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </h2>
                            <div id="warranty-body" class="" aria-labelledby="warranty-heading">
                                <div class="p-5 font-light border border-gray-200">
                                    <p class="mb-2 text-gray-500 dark:text-gray-400">
                                        {!! __('front/homePage.warranty A', ['product_name' => $product->name]) !!}
                                    </p>
                                </div>
                            </div>

                            <h2 id="payment-heading">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-5 font-medium text-left border border-gray-200 focus:ring-2 focus:ring-gray-200 hover:bg-gray-100 bg-gray-100 text-gray-900"
                                    data-accordion-target="#payment-body" aria-expanded="false"
                                    aria-controls="payment-body">
                                    <span>{!! __('front/homePage.payment Q', ['product_name' => $product->name]) !!}</span>
                                    <svg data-accordion-icon="" class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </h2>
                            <div id="payment-body" class="" aria-labelledby="payment-heading">
                                <div class="p-5 font-light border border-gray-200 rounded-b-xl">
                                    <p class="mb-2 text-gray-500 dark:text-gray-400">
                                        {!! __('front/homePage.payment A', [
                                            'product_name' => $product->name,
                                            'icon' =>
                                                '<a href="https://wa.me/+2' .
                                                config('settings.whatsapp_number') .
                                                '" target="_blank" class="inline-flex text-whatsapp"> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                                        ]) !!}
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                    {{-- FAQ Body :: End --}}

                    {{-- Delivery Cost Body :: Start --}}
                    <div class="hidden p-4 rounded-lg flex items-center justify-center" id="delivery-cost"
                        role="tabpanel" aria-labelledby="delivery-cost-tab">
                        @livewire('front.product.delivery.product-delivery', ['free_shipping' => $productOffer->free_shipping, 'product_weight' => $product->weight])
                    </div>
                    {{-- Delivery Cost Body :: End --}}

                </div>
            </div>
            {{-- Product Other Info :: End --}}

        </section>

        {{-- Complementary Products :: Start --}}
        @if (count($complementedItems))
            <section class="col-span-12 flex flex-col gap-2 bg-white rounded shadow-lg p-4">

                <h3 class="text-2xl font-bold text-center text-gray-700 mb-4">
                    {{ __('front/homePage.Complementary Products') }}
                </h3>

                {{-- Slider : Start --}}
                <div class="product_list splide h-full w-full row-span-2 rounded overflow-hidden" wire:ignore>
                    <div class="splide__track">
                        {{-- List of Products : Start --}}
                        <ul class="splide__list">
                            @foreach ($complementedItems as $item)
                                {{-- Product : Start --}}
                                <x-front.product-box-small :item="$item" />
                                {{-- Product : End --}}
                            @endforeach
                        </ul>
                        {{-- List of Products : End --}}
                    </div>
                </div>
                {{-- Slider : End --}}
            </section>
        @endif
        {{-- Complementary Products :: End --}}

        {{-- Related Products :: Start --}}
        @if (count($relatedItems))
            <section class="col-span-12 flex flex-col gap-2 bg-white rounded shadow-lg p-4">

                <h3 class="text-2xl font-bold text-center text-gray-700 mb-4">
                    {{ __('front/homePage.Related Products') }}
                </h3>

                {{-- Slider : Start --}}
                <div class="product_list splide h-full w-full row-span-2 rounded overflow-hidden" wire:ignore>
                    <div class="splide__track">
                        {{-- List of Products : Start --}}
                        <ul class="splide__list">
                            @foreach ($relatedItems as $item)
                                {{-- Product : Start --}}
                                <x-front.product-box-small :item="$item" />
                                {{-- Product : End --}}
                            @endforeach
                        </ul>
                        {{-- List of Products : End --}}
                    </div>
                </div>
                {{-- Slider : End --}}
            </section>
        @endif
        {{-- Related Products :: End --}}
        </div>
    @endsection

    {{-- Extra Scripts --}}
    @push('js')
        <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>

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

                // ####### Product Images Slider :: Start #######
                @if ($product->images->count())
                    {
                        var main = new Splide('#main-slider', {
                            type: 'fade',
                            width: "100%",
                            rewind: true,
                            autoplay: true,
                            @if (LaravelLocalization::getCurrentLocale() == 'ar')
                                direction: 'rtl',
                                pagination: 'rtl',
                            @else
                                pagination: 'ltr',
                            @endif
                        });

                        var thumbnails = new Splide('#thumbnail-slider', {
                            rewind: true,
                            fixedWidth: 100,
                            fixedHeight: 100,
                            isNavigation: true,
                            @if (LaravelLocalization::getCurrentLocale() == 'ar')
                                direction: 'rtl',
                                pagination: 'rtl',
                            @else
                                pagination: 'ltr',
                            @endif
                            padding: 10,
                            focus: 'center',
                            pagination: false,
                            cover: true,
                            arrows: false,
                            dragMinThreshold: {
                                mouse: 4,
                                touch: 10,
                            },
                        });

                        main.sync(thumbnails);
                        main.mount();
                        thumbnails.mount();
                    }
                @endif
                // ####### Product Images Slider :: End #######

                let options = {
                    inline: true,
                    plugins: [
                        'directionality',
                        'lists',
                        'autoresize',
                    ],
                    toolbar: 'ltr rtl | ' +
                        'bold | alignleft ' +
                        'alignright | bullist',
                    statusbar: false,
                    menubar: false,
                    content_style: `
                .mce-content-body[data-mce-placeholder]:not(.mce-visualblocks)::before {
                    text-align: center ; width: 100%
                    }
                    .mce-content-body ul {
                        padding: 0 10px;
                        list-style-type: disc;
                    }
                `,
                    directionality: 'rtl'
                };

                // tinymce for Description
                tinymce.init({
                    ...options,
                    selector: '#comment',
                    setup: function(editor) {
                        editor.on('blur', function(e) {
                            window.livewire.dispatch('updatedComment', tinymce.get(e.target.id)
                                .getContent())
                        });
                    }
                });

                // reinitialize tinymce for Description
                window.addEventListener('tinyMCE', function() {
                    tinymce.init({
                        ...options,
                        selector: '#comment',
                        setup: function(editor) {
                            editor.on('blur', function(e) {
                                window.livewire.dispatch('updatedComment', tinymce.get(e.target
                                        .id)
                                    .getContent())
                            });
                        }
                    });
                })

                $('#copyToClipboard').on('click', function() {
                    navigator.clipboard.writeText(
                        "https://smarttoolsegypt.com/{{ $product->id }}-{{ $product->slug }}");
                    Swal.fire({
                        text: "{{ __('front/homePage.The link has been copied successfully') }}",
                        icon: "success",
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
                })
            });

            // Zoom to Image when hover
            const zoomDivs = document.querySelectorAll('.zoom');

            zoomDivs.forEach(zoomDiv => {
                const img = zoomDiv.querySelector('img');

                const {
                    width,
                    height
                } = img.getBoundingClientRect();

                const scale = 2;
                zoomDiv.addEventListener('mousemove', e => {
                    const {
                        left,
                        top
                    } = zoomDiv.getBoundingClientRect();
                    const x = e.clientX - left;
                    const y = e.clientY - top;
                    const bgPosX = -x * (scale - 1);
                    const bgPosY = -y * (scale - 1);
                    img.style.transform = `scale(${scale})`;
                    img.style.transformOrigin = `${x}px ${y}px`;
                    img.style.backgroundPosition = `${bgPosX}px ${bgPosY}px`;
                });
                zoomDiv.addEventListener('mouseleave', () => {
                    img.style.transform = 'none';
                    img.style.transformOrigin = '50% 50%';
                    img.style.backgroundPosition = '50% 50%';
                });
            });
        </script>
    @endpush

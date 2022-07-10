@extends('layouts.front.site', ['titlePage' => __('front/homePage.Product Page Title', ['product_name' => $product->name])])

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
                    {{-- todo :: brand page --}}
                    <a href="{{ route('admin.users.index') }}">
                        {{ $product->brand->name }}
                    </a>
                </li>
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
                                    <li class="splide__slide">
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
                                        <img src="{{ asset('storage/images/products/original/' . $image->file_name) }}"
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

                <div class="col-span-12 md:col-span-8 p-4">
                    {{-- Product Name :: Start --}}
                    <h1 class="text-2xl font-bold mb-2 text-gray-700">
                        {{ $product->name }}
                    </h1>
                    {{-- Product Name :: End --}}

                    {{-- Product Brand & Model :: Start --}}
                    <div class="flex justify-start items-center gap-3">
                        {{-- todo : Add Brand Link --}}
                        <h2 class="text-gray-800 font-bold">
                            {{ $product->brand->name }}
                        </h2>
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
                            <del class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400 text-sm">
                                <span>
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-3xl" dir="ltr">{{ number_format(explode('.', $product->base_price)[0],0,'.','\'') }}</span>
                            </del>
                        </div>
                        {{-- Base Price :: End --}}

                        {{-- Discount Price :: Start --}}
                        <div class="flex gap-3 items-center">
                            <span class="text-gray-800 font-bold text-md">
                                {{ __('front/homePage.After Discount: ') }}
                            </span>
                            <span class="flex rtl:flex-row-reverse gap-1 font-bold text-success text-sm">
                                <span>
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-2xl" dir="ltr">{{ number_format(explode('.', $product->final_price)[0],0,'.','\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', $product->final_price)[1] ?? '00' }}</span>
                            </span>
                        </div>
                        {{-- Discount Price :: End --}}

                        {{-- Disount Amount :: Start --}}
                        <div class="flex gap-3 items-center">
                            <span class="text-gray-800 font-bold text-md">
                                {{ __('front/homePage.You Saved: ') }}
                            </span>
                            <span class="flex rtl:flex-row-reverse gap-1 font-bold text-primary text-sm">
                                <span
                                    class="font-bold text-xl">(%{{ number_format((($product->base_price - $product->final_price) * 100) / $product->base_price, 2) ?? '%0' }})</span>
                                &nbsp;
                                <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                <span
                                    class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $product->base_price - $product->final_price)[0],0,'.','\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', $product->base_price - $product->final_price)[1] ?? '00' }}</span>
                            </span>
                        </div>
                        {{-- Disount Amount :: End --}}

                        {{-- Extra Discount :: Start --}}
                        @if ($product_offer->best_price < $product->final_price)
                            <div class="flex gap-3 items-center">
                                <span class="text-gray-800 font-bold text-md">
                                    {{ __('front/homePage.Extra Discount: ') }}
                                </span>
                                <span class="flex rtl:flex-row-reverse gap-1 font-bold text-primary text-sm">
                                    <span
                                        class="font-bold text-xl">(%{{ number_format((($product->final_price - $product_offer->best_price) * 100) / $product->final_price, 2) ?? '%0' }})</span>
                                    &nbsp;
                                    <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                    <span
                                        class="font-bold text-xl">{{ explode('.', $product->final_price - $product_offer->best_price)[0] }}</span>
                                    <span
                                        class="font-bold text-xs">{{ explode('.', $product->final_price - $product_offer->best_price)[1] ?? '00' }}</span>
                                </span>
                            </div>
                        @endif
                        {{-- Extra Discount :: End --}}
                    </div>
                    {{-- Product Price :: End --}}

                    <hr class="my-4">

                    {{-- Banner :: Start --}}
                    {{-- todo : Banner --}}
                    Banner
                    {{-- Banner :: End --}}

                    <hr class="my-4">

                    {{-- Product Description :: Start --}}
                    <div class="text-gray-800 description">
                        <h3 class="text-md font-bold mb-2">
                            {{ __('front/homePage.Description') }}
                        </h3>
                        {!! $product->description !!}
                    </div>
                    {{-- Product Description :: End --}}

                    <hr class="my-4">

                    {{-- Cart Amount :: Start --}}
                    <div class="grid grid-cols-12 justify-center">
                        <div class="col-span-6 col-start-4">
                            @livewire('front.general.cart.cart-amount', ['product_id' => $product->id, 'unique' => 'product-' . $product->id, 'title' => true], key($product->name . '-' . rand()))
                        </div>
                    </div>
                    {{-- Cart Amount :: End --}}

                    <div class="grid grid-cols-2 gap-3 items-center mt-4">
                        {{-- Add Product : Start --}}
                        <div class="flex justify-center gap-3">
                            {{-- Add to compare : Start --}}
                            @livewire('front.general.compare.add-to-compare-button', ['product_id' => $product['id']], key('add-compare-button-' . Str::random(10)))
                            {{-- Add to compare : End --}}

                            {{-- Add to wishlist : Start --}}
                            @livewire('front.general.wishlist.add-to-wishlist-button', ['product_id' => $product['id']], key('add-wishlist-button-' . Str::random(10)))
                            {{-- Add to wishlist : End --}}

                            {{-- Add to cart : Start --}}
                            @livewire('front.general.cart.add-to-cart-button', ['product_id' => $product['id'], 'text' => true], key('add-cart-button-' . Str::random(10)))
                            {{-- Add to cart : End --}}
                        </div>
                        {{-- Add Product : End --}}

                        {{-- Product Share :: Start --}}
                        <div class="flex justify-center gap-3">
                            {{-- todo : Complete the facebook share --}}
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.product.show', ['id' => $product->id, 'slug' => $product->slug]) }}&display=popup"
                                target="_blank"
                                class="w-9 h-9 bg-facebook rounded-circle flex justify-center items-center text-white shadow transition ease-in-out hover:bg-white hover:text-facebook hover:border hover:border-facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                    height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20" class="text-xl">
                                    <path fill="currentColor"
                                        d="M8.46 18h2.93v-7.3h2.45l.37-2.84h-2.82V6.04c0-.82.23-1.38 1.41-1.38h1.51V2.11c-.26-.03-1.15-.11-2.19-.11c-2.18 0-3.66 1.33-3.66 3.76v2.1H6v2.84h2.46V18z" />
                                </svg>
                            </a>

                            {{-- todo : Copy link to clipboard --}}
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.product.show', ['id' => $product->id, 'slug' => $product->slug]) }}&display=popup"
                                target="_blank"
                                class="w-9 h-9 bg-white text-gray-800 border border-gray-200 rounded-circle flex justify-center items-center shadow transition ease-in-out hover:bg-secondary hover:text-white hover:border hover:border-white">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                    height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2">
                                        <path
                                            d="M13.544 10.456a4.368 4.368 0 0 0-6.176 0l-3.089 3.088a4.367 4.367 0 1 0 6.177 6.177L12 18.177" />
                                        <path
                                            d="M10.456 13.544a4.368 4.368 0 0 0 6.176 0l3.089-3.088a4.367 4.367 0 1 0-6.177-6.177L12 5.823" />
                                    </g>
                                </svg>
                            </a>
                        </div>

                        {{-- Product Share :: End --}}
                    </div>

                </div>

                {{-- Delivery Details :: Start --}}
                <div class="col-span-12 md:col-span-4">
                    {{-- todo : delivery details --}}
                    @livewire('front.product.delivery.product-delivery',['free_shipping'=>$product_offer->free_shipping,'product_weight'=>$product->weight])
                </div>
                {{-- Delivery Details :: End --}}

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
                        @if ($product->specs)
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
                    </ul>
                </div>

                <div id="myTabContent">

                    {{-- Video Body :: Start --}}
                    @if ($product->video)
                        <div class="hidden p-4 rounded-lg flex items-center justify-center" id="video"
                            role="tabpanel" aria-labelledby="video-tab">
                            <div class="w-full md:w-1/2">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="{{ $product->video }}" title="{{ $product->name }}"
                                        frameborder="0"
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
                                @foreach (json_decode($product->specs) as $spec)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap text-center">
                                            {{ $spec->$locale->title }}
                                        </th>
                                        <td class="px-6 py-4 text-center">
                                            {{ $spec->$locale->value }}
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
                        @livewire('front.product.review.review-block', ['product_id' => $product->id])
                    </div>
                    {{-- Reviews Body :: End --}}

                    {{-- FAQ Body :: Start --}}
                    <div class="hidden p-4 rounded-lg flex items-center justify-center" id="faq" role="tabpanel"
                        aria-labelledby="faq-tab">
                        {{-- todo : FAQ System --}}
                        FAQ
                    </div>
                    {{-- FAQ Body :: End --}}
                </div>
            </div>
            {{-- Product Other Info :: End --}}
        </section>
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script src="{{ asset('assets/js/plugins/flowbite/flowbite.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function() {
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
                        window.livewire.emit('updatedComment', tinymce.get(e.target.id)
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
                            window.livewire.emit('updatedComment', tinymce.get(e.target
                                    .id)
                                .getContent())
                        });
                    }
                });
            })
        });
    </script>
@endpush

<section class="top-slider container mt-4 mb-3 relative overflow-hidden grid grid-cols-12 gap-3 lg:gap-4">

    {{-- All Categories : Start --}}
    <aside class="col-span-2 hidden lg:block z-10 ">
        <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t shadow">
            <span class="font-bold text-sm">
                {{ __('front/homePage.Main Categories') }}
            </span>
        </div>

        <ul class="bg-white rounded-b">
            @foreach ($topSupercategories as $topSupercategory)
                <li class="group" data-id="1">
                    <div
                        class="relative w-full cursor-pointer group-hover:shadow p-1 group-hover:after:block after:hidden after:content-[''] after:w-7 after:h-7 after:rotate-45 ltr:after:border-t-8 ltr:after:border-r-8 rtl:after:border-b-8 rtl:after:border-l-8 after:border-white after:absolute ltr:after:-right-1 rtl:after:-left-1 after:top-2">
                        <a href="{{ route('front.supercategory.products', $topSupercategory->id) }}"
                            class="text-truncate text-reset py-2 px-3 block text-sm flex gap-3 items-center">
                            <span class="material-icons">
                                {!! $topSupercategory->icon ?? 'construction' !!}
                            </span>
                            <span class="cat-name">{{ $topSupercategory->name }}</span>
                        </a>
                    </div>

                    <div
                        class="group-hover:block hidden hover:block absolute max-w-75 bg-white h-full top-0 rtl:right-[16.5%] ltr:left-[16.5%] rounded shadow-lg p-2 loaded overflow-y-scroll scrollbar scrollbar-thin scrollbar-thumb-gray-100 scrollbar-track-white">
                        <div class="card-columns">
                            @foreach ($topSupercategory->categories as $category)
                                <div class="card shadow-none border-0 m-0">
                                    <ul class="list-unstyled my-2 text-center w-full">
                                        <li class="fw-600 border-b font-bold text-sm py-2 my-2">
                                            <a class="text-reset"
                                                href="{{ route('front.category.products', $category->id) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                        @foreach ($category->subcategories as $subcategory)
                                            <li class="mb-2 text-sm">
                                                <a class="text-reset"
                                                    href="{{ route('front.subcategories.show', $subcategory->id) }}">
                                                    {{ $subcategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </li>
            @endforeach

            <li class="group flex justify-center items-center py-2">
                <a href="{{ route('front.supercategories.index') }}"
                    class="btn bg-secondary text-white text-sm py-1 px-2 rounded m-1 font-bold">
                    {{ __('front/homePage.Show All') }}
                </a>
            </li>
        </ul>
    </aside>
    {{-- All Categories : End --}}

    <div class="col-span-12 lg:col-span-8 flex flex-col gap-3">
        {{-- Main Slider & top categories : Start --}}
        <div class="overflow-hidden h-48 md:h-56 lg:h-64">
            {{-- Main Slider : Start --}}
            <div id="main-slider" class="splide h-full w-full rounded overflow-hidden">
                <div class="splide__track">
                    <ul class="splide__list shadow">
                        @foreach ($banners as $banner)
                            <li class="splide__slide">
                                <a href="{{ $banner->banner->link }}">
                                    <img src="{{ asset('storage/images/banners/original/' . $banner->banner->banner_name) }}"
                                        class="" alt="{{ $banner->banner->description }}">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- Main Slider : End --}}
        </div>
        {{-- Main Slider & top categories : End --}}

        {{-- SubSlider Banners : Start --}}
        <div class="grid grid-cols-2 gap-3 justify-between items-center overflow-hidden">
            @foreach ($subsliderBanners as $subsliderBanner)
                <div class="shadow rounded overflow-hidden bg-white text-center">
                    <a href="{{ $subsliderBanner->banner->link }}">
                        <img src="{{ asset('storage/images/banners/original/' . $subsliderBanner->banner->banner_name) }}"
                            class="m-auto " alt="{{ $subsliderBanner->banner->description }}">
                    </a>
                </div>
            @endforeach
        </div>
        {{-- SubSlider Banners : End --}}

        {{-- Subslider Small Banner : Start --}}
        <div class="flex flex-wrap gap-3 justify-around items-center overflow-hidden">
            @foreach ($subsliderSmallBanners as $subsliderSmallBanner)
                <div
                    class="subslider-small-banner flex justify-center items-center shadow rounded overflow-hidden bg-white text-center">
                    <a href="{{ $subsliderSmallBanner->banner->link }}">
                        <img src="{{ asset('storage/images/banners/original/' . $subsliderSmallBanner->banner->banner_name) }}"
                            class="m-auto " alt="{{ $subsliderSmallBanner->banner->description }}">
                    </a>
                </div>
            @endforeach
        </div>
        {{-- Subslider Small Banner : End --}}
    </div>

    {{-- Today's Deal : Start --}}
    <aside class="col-span-12 lg:col-span-2 shadow rounded overflow-hidden lg:max-h-180">
        <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t">
            <span class="text-sm font-bold">
                {{ __("front/homePage.Today's Deal") }}
            </span>
            <span
                class="text-xs font-bold rounded py-0.5 px-1 bg-red-600 text-white">{{ __('front/homePage.Hot') }}</span>
        </div>
        <div
            class="overflow-auto scrollbar scrollbar-thumb-secondary scrollbar-track-primary scrollbar-thin p-2 bg-primary rounded-b h-100">
            <div>

                <ul class="grid grid-cols-2 lg:grid-cols-1 gap-2 ">
                    @foreach ($items as $item)
                        {{-- Product : Start --}}
                        <li
                            class="product overflow-hidden bg-white border border-light rounded hover:shadow-md hover:scale-105 transition cursor-pointer">
                            <div class="carousel-box inline-block w-100">
                                <div class="group mb-2 relative">
                                    {{-- Add Product : Start --}}
                                    <div
                                        class="absolute z-10 top-2 ltr:-right-10 rtl:-left-10 transition-all ease-in-out duration-500 ltr:group-hover:right-2 rtl:group-hover:left-2 flex flex-col gap-1">

                                        {{-- Add to compare : Start --}}
                                        @livewire('front.general.compare.add-to-compare-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-compare-button-' . Str::random(10)))
                                        {{-- Add to compare : End --}}

                                        {{-- Add to wishlist : Start --}}
                                        @livewire('front.general.wishlist.add-to-wishlist-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-wishlist-button-' . Str::random(10)))
                                        {{-- Add to wishlist : End --}}

                                        {{-- Add to cart : Start --}}
                                        @livewire('front.general.cart.add-to-cart-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-cart-button-' . Str::random(10)))
                                        {{-- Add to cart : End --}}
                                    </div>
                                    {{-- Add Product : End --}}
                                    <a class="relative block overflow-hidden flex items-center justify-center hover:text-current"
                                        href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}">

                                        {{-- Base Discount : Start --}}
                                        @if (!$item['under_reviewing'] && $item['final_price'] != $item['base_price'])
                                            <span
                                                class="absolute bg-white flex gap-1 top-2 ltr:left-0 rtl:right-0 flex justify-center items-center shadow p-1 ltr:rounded-r-full rtl:rounded-l-full text-primary text-sm font-bold">
                                                <span>
                                                    {{ __('front/homePage.OFF') }}
                                                </span>
                                                <span class="flex items-center bg-primary text-white rounded-full p-1">
                                                    {{ $item['base_price'] > 0 ? round((($item['base_price'] - $item['final_price']) / $item['base_price']) * 100) : 0 }}%
                                                </span>
                                            </span>
                                        @endif
                                        {{-- Base Discount : End --}}

                                        {{-- Product Image : Start --}}
                                        @if ($item['thumbnail'])
                                            <div class="w-full h-full flex justify-center items-center bg-white">
                                                <img class="img-fit mx-auto lazyloaded construction-placeholder object-cover object-center"
                                                    data-placeholder-size="text-8xl"
                                                    @if ($item['type'] == 'Product') data-src="{{ asset('storage/images/products/cropped250/' . $item['thumbnail']['file_name']) }}"
                                                @elseif ($item['type'] == 'Collection')
                                                data-src="{{ asset('storage/images/collections/cropped250/' . $item['thumbnail']['file_name']) }}" @endif
                                                    alt="{{ $item['name'][session('locale')] . 'image' }}">
                                            </div>
                                        @else
                                            <div class="w-full h-full flex justify-center items-center bg-gray-100">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        @endif
                                        {{-- Product Image : End --}}

                                        {{-- Extra Discount : Start --}}
                                        @if (round((($item['final_price'] - $item['best_price']) * 100) / $item['final_price']))
                                            <span
                                                class="absolute bottom-2 rtl:right-0 ltr:left-0 text-xs font-bold text-white px-2 py-1 bg-primary">
                                                {{ __('front/homePage.Extra Discount') }}
                                                {{ round((($item['final_price'] - $item['best_price']) * 100) / $item['final_price']) }}%
                                            </span>
                                        @endif
                                        {{-- Extra Discount : End --}}
                                    </a>

                                    <a class="block md:p-3 p-2 text-left"
                                        @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}"
                                        @elseif ($item['type'] == 'Collection')
                                        href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @endif>

                                        {{-- Price : Start --}}
                                        <div class="flex flex-wrap justify-center items-center gap-2">

                                            {{-- Final Price : Start --}}
                                            <div class="flex rtl:flex-row-reverse gap-1">
                                                <sup
                                                    class="font-bold text-successDark text-xs">{{ __('front/homePage.EGP') }}</sup>
                                                <span class="font-bold text-successDark text-lg"
                                                    dir="ltr">{{ number_format(explode('.', $item['final_price'])[0], 0, '.', '\'') }}</span>
                                                <sup
                                                    class="font-bold text-successDark text-xs">{{ explode('.', $item['final_price'])[1] }}</sup>
                                            </div>
                                            {{-- Final Price : End --}}

                                            {{-- Base Price : Start --}}
                                            <del class="flex rtl:flex-row-reverse gap-1 font-bold text-red-400 text-sm">
                                                <sup class="text-xs">
                                                    {{ __('front/homePage.EGP') }}
                                                </sup>
                                                <span class="font-bold text-xl"
                                                    dir="ltr">{{ number_format(explode('.', $item['base_price'])[0], 0, '.', '\'') }}</span>
                                            </del>
                                            {{-- Base Price : End --}}

                                        </div>
                                        {{-- Price : End --}}

                                        {{-- Free Shipping: Start --}}
                                        @if ($item['free_shipping'])
                                            <div class="text-center text-success font-bold text-sm">
                                                {{ __('front/homePage.Free Shipping') }}
                                            </div>
                                        @endif
                                        {{-- Free Shipping: End --}}

                                        {{-- Reviews : Start --}}
                                        <div class="my-1 text-center flex justify-center items-center gap-2">
                                            <div class="rating flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="material-icons text-lg  inline-block @if ($i <= ceil($item['avg_rating'])) text-yellow-300 @else text-gray-400 @endif">
                                                        star
                                                    </span>
                                                @endfor
                                            </div>

                                            <span class="text-xs text-gray-600">({{ $item['reviews_count'] }})</span>
                                        </div>
                                        {{-- Reviews : End --}}

                                        {{-- Item's Name : Start --}}
                                        <h3 class="mb-2 text-center">
                                            <span class="block text-gray-800 truncate">
                                                {{ $item['name'][session('locale')] }}
                                            </span>
                                        </h3>
                                        {{-- Item's Name : End --}}

                                        {{-- Points : Start --}}
                                        @if ($item['points'] || $item['best_points'])
                                            <div
                                                class="rounded px-2 my-2 bg-gray-200 border-gray-800 text-black text-sm border flex justify-between items-center">
                                                <span>{{ __('front/homePage.Points') }}</span>
                                                <span
                                                    dir="ltr">{{ $item['best_points'] > $item['points'] ? number_format($item['best_points'], 0, '.', '\'') : number_format($item['points'], 0, '.', '\'') }}</span>
                                            </div>
                                        @endif
                                        {{-- Points : End --}}
                                    </a>

                                    {{-- Cart Amount : Start --}}
                                    <div class="md:px-3 px-2">
                                        @livewire(
                                            'front.general.cart.cart-amount',
                                            [
                                                'item_id' => $item['id'],
                                                'unique' => 'item-' . $item['id'],
                                                'type' => $item['type'],
                                                'small' => true,
                                            ],
                                            key($item['name'][session('locale')] . '-' . rand())
                                        )
                                    </div>
                                    {{-- Cart Amount : End --}}
                                </div>
                            </div>
                        </li>
                        {{-- Product : End --}}
                    @endforeach

                    {{-- See All : Start --}}
                    @if ($todayDeals->count() > 11)
                        {{-- Product : Start --}}
                        <li
                            class="product overflow-hidden bg-white border border-light rounded hover:shadow-md hover:scale-105 transition cursor-pointer">
                            <div class="carousel-box inline-block w-100">
                                <div class="group mb-2">
                                    <div class="relative overflow-hidden h-40 flex items-center justify-center">

                                        {{-- Fake Image : Start --}}
                                        <div class="w-full h-full flex justify-center items-center bg-gray-200">
                                            <div class="flex justify-center items-center">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Fake Image : End --}}

                                    </div>

                                    <div class="md:p-3 p-2 text-left">

                                        {{-- Product Name : Start --}}
                                        <h3 class="mb-0 text-center">
                                            <span class="block text-gray-800">
                                                {{ __('front/homePage.See All') }}
                                            </span>
                                        </h3>
                                        {{-- Product Name : End --}}

                                    </div>
                                </div>
                            </div>
                        </li>
                        {{-- Product : End --}}
                    @endif
                    {{-- See All : End --}}

                </ul>
            </div>
        </div>
    </aside>
    {{-- Today's Deal : End --}}
</section>

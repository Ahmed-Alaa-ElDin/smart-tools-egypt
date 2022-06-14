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
                        <a href="-clothing-fashion"
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
                                            <a class="text-reset" href="#">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                        @foreach ($category->subcategories as $subcategory)
                                            <li class="mb-2 text-sm">
                                                <a class="text-reset" href="#">
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
                <a href="#" class="btn bg-secondary text-white text-sm py-1 px-2 rounded m-1 font-bold">
                    {{ __('front/homePage.Show All') }}
                </a>
            </li>
        </ul>
    </aside>
    {{-- All Categories : End --}}

    {{-- Main Slider & top categories : Start --}}
    <div class="col-span-12 lg:col-span-8 overflow-hidden grid grid-rows-3 gap-3 h-72 md:h-80 lg:h-96">
        {{-- Main Slider : Start --}}
        <div id="main-slider" class="splide h-full w-full row-span-2 rounded overflow-hidden">
            <div class="splide__track">
                <ul class="splide__list shadow">
                    @foreach ($banners as $banner)
                        <li class="splide__slide">
                            <a href="{{ $banner->link }}">
                                <img src="{{ asset('storage/images/banners/original/' . $banner->banner_name) }}"
                                    class="" alt="{{ $banner->description }}">
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- Main Slider : End --}}

        {{-- Top Subcategories : Start --}}
        <div class="row-span-1 grid grid-cols-5 gap-3 justify-between items-center">
            @foreach ($topSubcategories as $topSubcategory)
                <a href="#" class="shadow rounded overflow-hidden bg-white p-1 text-center">
                    @if ($topSubcategory->image_name)
                        <img src="{{ asset('storage/images/subcategories/cropped100/' . $topSubcategory->image_name) }}"
                            class="m-auto w-14 lg:w-20" alt="{{ $topSubcategory->name }}">
                    @else
                        <div class="w-full h-full flex justify-center items-center bg-gray-200">
                            <div class="flex justify-center items-center">
                                <span class="block material-icons text-6xl md:text-7xl">
                                    handyman
                                </span>
                            </div>
                        </div>
                    @endif

                    <span class="text-xs md:text-sm inline-block font-bold mt-1">
                        {{ $topSubcategory->name }}
                    </span>
                </a>
            @endforeach
        </div>
        {{-- Top Subcategories : End --}}

    </div>
    {{-- Main Slider & top categories : End --}}

    {{-- Today's Deal : Start --}}
    <aside class="col-span-12 lg:col-span-2 shadow rounded overflow-hidden">
        <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t">
            <span class="text-sm font-bold">
                {{ __("front/homePage.Today's Deal") }}
            </span>
            <span
                class="text-xs font-bold rounded py-0.5 px-1 bg-red-600 text-white">{{ __('front/homePage.Hot') }}</span>
        </div>
        <div
            class="overflow-auto scrollbar scrollbar-thumb-secondary scrollbar-track-primary scrollbar-thin lg:h-80 p-2 bg-primary rounded-b">
            <div>

                <ul class="grid grid-cols-3 lg:grid-cols-1 gap-2 ">
                    @foreach ($products as $product)
                        {{-- Product : Start --}}
                        <li
                            class="product overflow-hidden bg-white border border-light rounded hover:shadow-md hover:scale-105 transition cursor-pointer">
                            <div class="carousel-box inline-block w-100">
                                <div class="group mb-2">
                                    <div class="relative overflow-hidden h-40 flex items-center justify-center">

                                        {{-- Base Discount : Start --}}
                                        @if (!$product['under_reviewing'] && $product['final_price'] != $product['base_price'])
                                            <span
                                                class="absolute bg-white flex gap-1 top-2 ltr:left-0 rtl:right-0 flex justify-center items-center shadow p-1 ltr:rounded-r-full rtl:rounded-l-full text-primary text-sm font-bold">
                                                <span>
                                                    {{ __('front/homePage.OFF') }}
                                                </span>
                                                <span class="flex items-center bg-primary text-white rounded-full p-1">
                                                    {{ round((($product['base_price'] - $product['final_price']) / $product['base_price']) * 100) }}%
                                                </span>
                                            </span>
                                        @endif
                                        {{-- Base Discount : End --}}

                                        {{-- Product Image : Start --}}
                                        @if ($product['thumbnail'])
                                            <div class="w-full h-full flex justify-center items-center">
                                                <img class="img-fit mx-auto lazyloaded"
                                                    src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
                                                    alt="{{ $product['name'][session('locale')] . 'image' }}">
                                            </div>
                                        @else
                                            <div class="w-full h-full flex justify-center items-center bg-gray-200">
                                                <div class="flex justify-center items-center">
                                                    <span class="block material-icons text-8xl">
                                                        construction
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        {{-- Product Image : End --}}

                                        {{-- Extra Discount : Start --}}
                                        @if (round((($product['final_price'] - $product['best_price']) * 100) / $product['final_price']))
                                            <span
                                                class="absolute bottom-2 rtl:right-0 ltr:left-0 text-xs font-bold text-white px-2 py-1 bg-primary">
                                                {{ __('front/homePage.Extra Discount') }}
                                                {{ round((($product['final_price'] - $product['best_price']) * 100) / $product['final_price']) }}%
                                            </span>
                                        @endif
                                        {{-- Extra Discount : End --}}

                                        {{-- Add Product : Start --}}
                                        <div
                                            class="absolute top-2 ltr:-right-10 rtl:-left-10 transition-all ease-in-out duration-500 ltr:group-hover:right-2 rtl:group-hover:left-2 flex flex-col gap-1">

                                            {{-- Add to compare : Start --}}
                                            @livewire('front.general.compare.add-to-compare-button', ['product_id' => $product['id']], key('add-compare-button-' . Str::random(10)))
                                            {{-- Add to compare : End --}}

                                            {{-- Add to wishlist : Start --}}
                                            @livewire('front.general.wishlist.add-to-wishlist-button', ['product_id' => $product['id']], key('add-wishlist-button-' . Str::random(10)))
                                            {{-- Add to wishlist : End --}}

                                            {{-- Add to cart : Start --}}
                                            @livewire('front.general.cart.add-to-cart-button', ['product_id' => $product['id']], key('add-cart-button-' . Str::random(10)))
                                            {{-- Add to cart : End --}}
                                        </div>
                                        {{-- Add Product : End --}}

                                    </div>
                                    <div class="md:p-3 p-2 text-left">

                                        {{-- Price : Start --}}
                                        <div class="flex flex-wrap justify-center items-center gap-2">

                                            {{-- Final Price : Start --}}
                                            <div class="flex rtl:flex-row-reverse gap-1">
                                                <span
                                                    class="font-bold text-primary text-xs">{{ __('front/homePage.EGP') }}</span>
                                                <span
                                                    class="font-bold text-primary text-xl">{{ explode('.', $product['final_price'])[0] }}</span>
                                                <span
                                                    class="font-bold text-primary text-xs">{{ explode('.', $product['final_price'])[1] }}</span>
                                            </div>
                                            {{-- Final Price : End --}}

                                            {{-- Base Price : Start --}}
                                            <del
                                                class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400 text-sm">
                                                <span class="text-xs">
                                                    {{ __('front/homePage.EGP') }}
                                                </span>
                                                <span
                                                    class="font-bold text-2xl">{{ explode('.', $product['base_price'])[0] }}</span>
                                            </del>
                                            {{-- Base Price : End --}}

                                        </div>
                                        {{-- Price : End --}}

                                        {{-- Free Shipping : Start --}}
                                        @if ($product['free_shipping'])
                                            <div class="text-center text-green-600 font-bold text-sm">
                                                {{ __('front/homePage.Free Shipping') }}
                                            </div>
                                        @endif
                                        {{-- Free Shipping : End --}}

                                        {{-- Reviews : Start --}}
                                        <div class="my-1 text-center flex justify-center items-center gap-1">
                                            {{-- todo --}}
                                            <div class="rating flex">
                                                <span class="material-icons text-yellow-500 text-xl">
                                                    star
                                                </span>

                                                <span class="material-icons text-yellow-500 text-xl">
                                                    star
                                                </span>

                                                <span class="material-icons text-yellow-500 text-xl">
                                                    star
                                                </span>

                                                <span class="material-icons text-yellow-500 text-xl">
                                                    star_border
                                                </span>

                                                <span class="material-icons text-yellow-500 text-xl">
                                                    star_border
                                                </span>
                                            </div>

                                            <span class="text-xs text-gray-600">(100)</span>
                                        </div>
                                        {{-- Reviews : End --}}

                                        {{-- Product Name : Start --}}
                                        <h3 class="mb-2 text-center">
                                            <span class="block text-gray-800 truncate">
                                                {{ $product['name'][session('locale')] }}
                                            </span>
                                        </h3>
                                        {{-- Product Name : End --}}

                                        {{-- Points : Start --}}
                                        @if ($product['points'] || $product['best_points'])
                                            <div
                                                class="rounded px-2 my-2 bg-gray-200 border-gray-800 text-black text-sm border flex justify-between items-center">
                                                <span>{{ __('front/homePage.Points') }}</span>
                                                <span>{{ $product['best_points'] > $product['points'] ? round($product['best_points']) : $product['points'] }}</span>
                                            </div>
                                        @endif
                                        {{-- Points : End --}}

                                        {{-- Cart Amount : Start --}}
                                        @livewire('front.general.cart.cart-amount', ['product_id' => $product['id'], 'unique' => 'product-' . $product['id']], key($product['name'][session('locale')] . '-' . rand()))
                                        {{-- Cart Amount : End --}}

                                    </div>
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

                                        {{-- Product Image : Start --}}
                                        <div class="w-full h-full flex justify-center items-center bg-gray-200">
                                            <div class="flex justify-center items-center">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Product Image : End --}}

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

<section class="offer-bar mb-3">
    <div class="container">
        <div class="px-2 py-4 md:px-4 md:py-3 bg-white shadow rounded">

            {{-- Header : Start --}}
            <div class="flex flex-wrap mb-3 gap-2 justify-between items-baseline border-b text-center">
                {{-- Title : Start --}}
                <h3 class="h5 font-bold mb-0 w-full text-center md:w-auto">
                    <span
                        class="border-b-2 border-primary pb-3 inline-block">{{ __('front/homePage.Flash Sale') }}</span>
                </h3>
                {{-- Title : End --}}

                {{-- Timer : Start --}}
                <div class="flex items-center justify-center content-end gap-2 mt-2 w-full md:w-auto"
                    data-date="2025/01/01 00:00:00">
                    {{-- Day : Start --}}
                    <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                        <span
                            class="inline-block text-white text-center text-xs font-bold px-1">{{ __('front/homePage.Day') }}</span>
                        <span class="inline-block text-black bg-white px-1 rounded">988</span>
                    </div>
                    {{-- Day : End --}}

                    <span class="countdown-separator">:</span>
                    {{-- Hour : Start --}}
                    <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                        <span
                            class="inline-block text-white text-center text-xs font-bold px-1">{{ __('front/homePage.Hour') }}</span>
                        <span class="inline-block text-black bg-white px-1 rounded">12</span>
                    </div>
                    {{-- Hour : End --}}

                    <span class="countdown-separator">:</span>
                    {{-- Minute : Start --}}
                    <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                        <span
                            class="inline-block text-white text-center text-xs font-bold px-1">{{ __('front/homePage.Minute') }}</span>
                        <span class="inline-block text-black bg-white px-1 rounded">26</span>
                    </div>
                    {{-- Minute : End --}}

                    <span class="countdown-separator">:</span>
                    {{-- Second : Start --}}
                    <div class="countdown-item bg-primary flex justify-center items-center p-1 rounded shadow ">
                        <span
                            class="inline-block text-white text-center text-xs font-bold px-1">{{ __('front/homePage.Second') }}</span>
                        <span class="inline-block text-black bg-white px-1 rounded">15</span>
                    </div>
                    {{-- Second : End --}}
                </div>
                {{-- Timer : End --}}

                {{-- View More Button : Start --}}
                <div class="w-full md:w-auto">
                    <a href="#"
                        class="btn bg-secondary btn-sm shadow-md font-bold mb-3 md:mb-auto m-auto">{{ __('front/homePage.View More') }}</a>
                </div>
                {{-- View More Button : End --}}
            </div>
            {{-- Header : End --}}

            {{-- Slider : Start --}}
            <div id="flash-sale-slider" class="splide h-full w-full row-span-2 rounded overflow-hidden">
                <div class="splide__track">
                    {{-- List of Products : Start --}}
                    <ul class="splide__list gap-3">

                        {{-- Product : Start --}}
                        <li class="splide__slide ">
                            <div class="carousel-box" style="width: 100%; display: inline-block;">
                                <div
                                    class="group border border-light rounded hover:shadow-md hover:scale-105 mt-1 mb-2 transition">
                                    <div class="relative overflow-hidden">

                                        {{-- Base Discount : Start --}}
                                        <span
                                            class="absolute bg-white flex gap-1 top-2 ltr:left-0 rtl:right-0 flex justify-center items-center shadow p-1 ltr:rounded-r-full rtl:rounded-l-full text-primary text-sm font-bold">
                                            <span>
                                                {{ __('front/homePage.OFF') }}
                                            </span>
                                            <span class="flex items-center bg-primary text-white rounded-full p-1">
                                                50%
                                            </span>
                                        </span>
                                        {{-- Base Discount : End --}}

                                        {{-- Product Image : Start --}}
                                        <a href="#" class="block">
                                            <img class="img-fit mx-auto h-full md:h-58 lazyloaded"
                                                src="https://static.dezeen.com/uploads/2016/11/i-pace-electric-car-jaguar-design_dezeen_sq-300x300.jpg">
                                        </a>
                                        {{-- Product Image : End --}}

                                        {{-- Extra Discount : Start --}}
                                        <span
                                            class="absolute bottom-2 rtl:right-0 ltr:left-0 text-xs font-bold text-white px-2 py-1 bg-primary">
                                            {{ __('front/homePage.Extra Discount') }} 20%
                                        </span>
                                        {{-- Extra Discount : End --}}

                                        {{-- Add Product : Start --}}
                                        <div
                                            class="absolute top-2 ltr:-right-10 rtl:-left-10 transition-all ease-in-out duration-500 ltr:group-hover:right-2 rtl:group-hover:left-2 flex flex-col gap-1">

                                            {{-- Add to wishlist : Start --}}
                                            <a href="javascript:void(0)" onclick="addToWishList(104)"
                                                data-toggle="tooltip"
                                                data-title="{{ __('front/homePage.Add to wishlist') }}"
                                                data-placement="left">
                                                <span
                                                    class="material-icons bg-white text-lg p-1 rounded-full border border-light w-9 h-9 text-center shadow-sm">
                                                    favorite_border
                                                </span>
                                            </a>
                                            {{-- Add to wishlist : End --}}

                                            {{-- Add to compare : Start --}}
                                            <a href="javascript:void(0)" onclick="addToCompare(104)"
                                                data-toggle="tooltip"
                                                data-title="{{ __('front/homePage.Add to compare') }}"
                                                data-placement="left">
                                                <span
                                                    class="material-icons bg-white text-lg p-1 rounded-full border border-light w-9 h-9 text-center shadow-sm">
                                                    compare_arrows
                                                </span>
                                            </a>
                                            {{-- Add to compare : End --}}

                                            {{-- Add to cart : Start --}}
                                            <a href="javascript:void(0)" onclick="showAddToCartModal(104)"
                                                data-toggle="tooltip"
                                                data-title="{{ __('front/homePage.Add to cart') }}"
                                                data-placement="left">
                                                <span
                                                    class="material-icons text-lg p-1 rounded-full border border-light w-9 h-9 animate-pulse text-center shadow-sm bg-primary text-white hover:bg-secondary">
                                                    shopping_cart
                                                </span>
                                            </a>
                                            {{-- Add to cart : End --}}
                                        </div>
                                        {{-- Add Product : End --}}

                                    </div>
                                    <div class="md:p-3 p-2 text-left">

                                        {{-- Price : Start --}}
                                        <div class="flex justify-center items-center gap-3">

                                            {{-- Final Price : Start --}}
                                            <div class="flex rtl:flex-row-reverse gap-1">
                                                <span
                                                    class="font-bold text-primary text-sm">{{ __('front/homePage.EGP') }}</span>
                                                <span class="font-bold text-primary text-2xl">2550</span>
                                                <span class="font-bold text-primary text-sm">00</span>
                                            </div>
                                            {{-- Final Price : End --}}

                                            {{-- Base Price : Start --}}
                                            <del
                                                class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400 text-sm">
                                                <span>
                                                    {{ __('front/homePage.EGP') }}
                                                </span>
                                                <span>
                                                    2440.00
                                                </span>
                                            </del>
                                            {{-- Base Price : End --}}

                                        </div>
                                        {{-- Price : End --}}

                                        {{-- Free Shipping : Start --}}
                                        <div class="text-center text-success font-bold text-sm">
                                            {{ __('front/homePage.Free Shipping') }}
                                        </div>
                                        {{-- Free Shipping : End --}}

                                        {{-- Reviews : Start --}}
                                        <div class="my-1 text-center flex justify-center items-center gap-2">
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

                                            <span class="text-sm text-gray-600">(100)</span>
                                        </div>
                                        {{-- Reviews : End --}}

                                        {{-- Product Name : Start --}}
                                        <h3 class="text-sm mb-0 h-16 rtl:text-right">
                                            <a href="#" class="block text-gray-700">
                                                سيارة جامدة زوحليقة XDD يسبسيبسي
                                            </a>
                                        </h3>
                                        {{-- Product Name : End --}}

                                        {{-- Points : Start --}}
                                        <div
                                            class="rounded px-2 mt-2 bg-gray-200 border-gray-800 text-black text-sm border flex justify-between items-center">
                                            <span>{{ __('front/homePage.Points') }}</span>
                                            <span>100</span>
                                        </div>
                                        {{-- Points : End --}}

                                    </div>
                                </div>
                            </div>
                        </li>
                        {{-- Product : End --}}

                    </ul>
                    {{-- List of Products : End --}}

                </div>
            </div>
            {{-- Slider : End --}}

        </div>
    </div>
</section>

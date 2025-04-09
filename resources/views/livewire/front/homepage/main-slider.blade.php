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
                                <a
                                    href="{{ str_starts_with($banner->banner->link, 'http') ? $banner->banner->link : env('APP_URL') . $banner->banner->link }}">
                                    <img loading="lazy"
                                        src="{{ asset('storage/images/banners/cropped1000/' . $banner->banner->banner_name) }}"
                                        class="w-[1000px]" alt="{{ $banner->banner->description }}">
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
                    <a
                        href="{{ str_starts_with($subsliderBanner->banner->link, 'http') ? $subsliderBanner->banner->link : env('APP_URL') . $subsliderBanner->banner->link }}">
                        <img src="{{ asset('storage/images/banners/cropped500/' . $subsliderBanner->banner->banner_name) }}"
                            class="m-auto w-[500px]" alt="{{ $subsliderBanner->banner->description }}">
                    </a>
                </div>
            @endforeach
        </div>
        {{-- SubSlider Banners : End --}}

        {{-- Subslider Small Banner : Start --}}
        <div class="flex flex-wrap gap-3 justify-between items-center overflow-hidden">
            @foreach ($subsliderSmallBanners as $subsliderSmallBanner)
                <div
                    class="max-w-[75px] max-h-[75px] md:max-w-[100px] md:max-h-[100px] lg:max-w-[150px] lg:max-h-[150px] flex justify-center items-center shadow rounded overflow-hidden bg-white text-center">
                    <a
                        href="{{ str_starts_with($subsliderSmallBanner->banner->link, 'http') ? $subsliderSmallBanner->banner->link : env('APP_URL') . $subsliderSmallBanner->banner->link }}">
                        <img loading="lazy"
                            src="{{ asset('storage/images/banners/cropped150/' . $subsliderSmallBanner->banner->banner_name) }}"
                            srcset="{{ asset('storage/images/banners/cropped75/' . $subsliderSmallBanner->banner->banner_name) }} 75w,
                                {{ asset('storage/images/banners/cropped150/' . $subsliderSmallBanner->banner->banner_name) }} 150w"
                            sizes="(max-width: 768px) 75px, 150px"
                            class="m-auto w-[75px] h-[75px] md:w-[100px] md:h-[100px] lg:w-[150px] lg:h-[150px]"
                            alt="{{ $subsliderSmallBanner->banner->description }}">
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
                        <x-front.product-box-small :item="$item" />
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

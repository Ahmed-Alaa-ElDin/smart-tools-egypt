<section class="top-slider container mt-4 mb-3 relative grid grid-cols-12 gap-3 lg:gap-4 items-stretch">

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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 justify-between items-center overflow-hidden">
            @foreach ($subsliderBanners as $subsliderBanner)
                <div class="overflow-hidden text-center">
                    <a
                        href="{{ str_starts_with($subsliderBanner->banner->link, 'http') ? $subsliderBanner->banner->link : env('APP_URL') . $subsliderBanner->banner->link }}">
                        <img loading="lazy"
                            src="{{ asset('storage/images/banners/cropped500/' . $subsliderBanner->banner->banner_name) }}"
                            class="shadow rounded m-auto w-[500px]" alt="{{ $subsliderBanner->banner->description }}">
                    </a>
                </div>
            @endforeach
        </div>
        {{-- SubSlider Banners : End --}}

        {{-- Subslider Small Banner : Start --}}
        <div class="flex flex-wrap gap-2 justify-between items-center overflow-hidden">
            @foreach ($subsliderSmallBanners as $subsliderSmallBanner)
                <div
                    class="w-[100px] h-[100px] lg:w-[150px] lg:h-[150px] flex justify-center items-center shadow rounded overflow-hidden bg-white text-center">
                    <a
                        href="{{ str_starts_with($subsliderSmallBanner->banner->link, 'http') ? $subsliderSmallBanner->banner->link : env('APP_URL') . $subsliderSmallBanner->banner->link }}">
                        <img loading="lazy"
                            src="{{ asset('storage/images/banners/cropped150/' . $subsliderSmallBanner->banner->banner_name) }}"
                            srcset="{{ asset('storage/images/banners/cropped75/' . $subsliderSmallBanner->banner->banner_name) }} 75w,
                                {{ asset('storage/images/banners/cropped150/' . $subsliderSmallBanner->banner->banner_name) }} 150w"
                            sizes="(max-width: 768px) 75px, 150px"
                            class="m-auto w-[100px] h-[100px] lg:w-[150px] lg:h-[150px]"
                            alt="{{ $subsliderSmallBanner->banner->description }}">
                    </a>
                </div>
            @endforeach
        </div>
        {{-- Subslider Small Banner : End --}}
    </div>

    {{-- Today's Deal : Start --}}
    @livewire('front.homepage.todays-deal', ['section' => $todayDeals])
    {{-- Today's Deal : End --}}
</section>

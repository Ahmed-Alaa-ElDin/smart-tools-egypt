<section class="top-slider container mt-4 mb-3 relative overflow-hidden">
    <div class="grid grid-cols-12 gap-3 lg:gap-4">

        {{-- All Categories : Start --}}
        <aside class="col-span-3 hidden lg:block z-10 h-64 shadow">
            <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t">
                <span class="font-bold text-sm">
                    {{ __('front/homePage.Main Categories') }}
                </span>
                <a href="#" class="font-bold text-xs">
                    {{ __('front/homePage.Show All') }}
                </a>

            </div>
            <ul class="bg-white rounded-b">
                @foreach ($topsupercategories as $topsupercategory)
                    <li class="group" data-id="1">
                        <div
                            class="relative w-full cursor-pointer group-hover:shadow p-1 group-hover:after:block after:hidden after:content-[''] after:w-7 after:h-7 after:rotate-45 ltr:after:border-t-8 ltr:after:border-r-8 rtl:after:border-b-8 rtl:after:border-l-8 after:border-white after:absolute ltr:after:-right-1 rtl:after:-left-1 after:top-2">
                            <a href="-clothing-fashion"
                                class="text-truncate text-reset py-2 px-3 block text-sm flex gap-3 items-center">
                                <span class="material-icons">
                                    {{ $topsupercategory->icon }}
                                </span>
                                <span class="cat-name">{{ $topsupercategory->name }}</span>
                            </a>
                        </div>

                        <div
                            class="group-hover:block hidden hover:block absolute max-w-75 bg-white h-full top-0 rtl:right-1/4 ltr:left-1/4 rounded shadow-lg p-2 loaded overflow-y-scroll scrollbar scrollbar-thin scrollbar-thumb-gray-100 scrollbar-track-white">
                            <div class="card-columns">
                                @foreach ($topsupercategory->categories as $category)
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

        <div class="col-span-12 lg:col-span-7 overflow-hidden grid grid-rows-3 gap-3 h-72 md:h-80 lg:h-96">
            {{-- Main Slider : Start --}}
            <div id="main-slider" class="splide h-full w-full row-span-2 shadow rounded overflow-hidden">
                <div class="splide__track">
                    <ul class="splide__list">
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

            {{-- Top Categories : Start --}}
            <div class="row-span-1 grid grid-cols-5 gap-3 justify-between items-center">
                <a href="#" class="shadow rounded overflow-hidden bg-white p-1">
                    <img src="https://mobiserve-eg.com/wp-content/uploads/2021/05/Air-Filters-Change-100x100.png"
                        class="m-auto">
                </a>
                <a href="#" class="shadow rounded overflow-hidden bg-white p-1">
                    <img src="https://mobiserve-eg.com/wp-content/uploads/2021/05/Air-Filters-Change-100x100.png"
                        class="m-auto">
                </a>
                <a href="#" class="shadow rounded overflow-hidden bg-white p-1">
                    <img src="https://mobiserve-eg.com/wp-content/uploads/2021/05/Air-Filters-Change-100x100.png"
                        class="m-auto">
                </a>
                <a href="#" class="shadow rounded overflow-hidden bg-white p-1">
                    <img src="https://mobiserve-eg.com/wp-content/uploads/2021/05/Air-Filters-Change-100x100.png"
                        class="m-auto">
                </a>
                <a href="#" class="shadow rounded overflow-hidden bg-white p-1">
                    <img src="https://mobiserve-eg.com/wp-content/uploads/2021/05/Air-Filters-Change-100x100.png"
                        class="m-auto">
                </a>
            </div>
            {{-- Top Categories : End --}}

        </div>

        {{-- Today's Deal : Start --}}
        <div class="col-span-12 lg:col-span-2 shadow rounded overflow-hidden">
            <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t">
                <span class="text-sm font-bold">
                    {{ __("front/homePage.Today's Deal") }}
                </span>
                <span
                    class="text-xs font-bold rounded py-0.5 px-1 bg-red-600 text-white">{{ __('front/homePage.Hot') }}</span>
            </div>
            <div
                class="overflow-auto scrollbar scrollbar-thumb-secondary scrollbar-track-primary scrollbar-thin lg:h-80 p-2 bg-primary rounded-b">
                <div class="grid grid-cols-4 lg:grid-cols-1 gap-2">
                    <div class="col-span-1">
                        <a href="#" class="block p-2 text-reset bg-white h-100 rounded">
                            <div class="items-center">
                                <div class="">
                                    <div class="img">
                                        <img class="img-fit lazyloaded w-24 m-auto"
                                            src="https://p.turbosquid.com/ts-thumb/Np/o1LCqU/rn/searchimageedited/png/1638461647/300x300/sharp_fit_q85/bd40184297d31c4363ab072cc773700b99df856d/searchimageedited.jpg"
                                            data-src="#" alt="Microsoft Windows 10 pro">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="text-center">
                                        <span class="block text-primary fw-600">$35.000</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-span-1">
                        <a href="#" class="block p-2 text-reset bg-white h-100 rounded">
                            <div class="items-center">
                                <div class="">
                                    <div class="img">
                                        <img class="img-fit lazyloaded w-24 m-auto"
                                            src="https://p.turbosquid.com/ts-thumb/Np/o1LCqU/rn/searchimageedited/png/1638461647/300x300/sharp_fit_q85/bd40184297d31c4363ab072cc773700b99df856d/searchimageedited.jpg"
                                            data-src="#"
                                            alt="SUNGAIT Ultra Lightweight Rectangular Polarized Sunglasses UV400 Protection">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="text-center">
                                        <span class="block text-primary fw-600">$14.000</span>
                                        <del class="block opacity-70">$25.000</del>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-span-1">
                        <a href="#" class="block p-2 text-reset bg-white h-100 rounded">
                            <div class="items-center">
                                <div class="">
                                    <div class="img">
                                        <img class="img-fit lazyloaded w-24 m-auto"
                                            src="https://p.turbosquid.com/ts-thumb/Np/o1LCqU/rn/searchimageedited/png/1638461647/300x300/sharp_fit_q85/bd40184297d31c4363ab072cc773700b99df856d/searchimageedited.jpg"
                                            data-src="#" alt="Aurora 13-inch Bonnie Teddy Bear">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="text-center">
                                        <span class="block text-primary fw-600">$50.000</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-span-1">
                        <a href="#" class="block p-2 text-reset bg-white h-100 rounded">
                            <div class="items-center">
                                <div class="">
                                    <div class="img">
                                        <img class="img-fit lazyloaded w-24 m-auto"
                                            src="https://p.turbosquid.com/ts-thumb/Np/o1LCqU/rn/searchimageedited/png/1638461647/300x300/sharp_fit_q85/bd40184297d31c4363ab072cc773700b99df856d/searchimageedited.jpg"
                                            data-src="#" alt="Apple - iPhone 12 Pro Max 5G 256GB">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="text-center">
                                        <span class="block text-primary fw-600">$1,200.000</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-span-1">
                        <a href="#" class="block p-2 text-reset bg-white h-100 rounded">
                            <div class="items-center">
                                <div class="">
                                    <div class="img">
                                        <img class="img-fit lazyloaded w-24 m-auto"
                                            src="https://p.turbosquid.com/ts-thumb/Np/o1LCqU/rn/searchimageedited/png/1638461647/300x300/sharp_fit_q85/bd40184297d31c4363ab072cc773700b99df856d/searchimageedited.jpg"
                                            data-src="#"
                                            alt="SUNGAIT Ultra Lightweight Rectangular Polarized Sunglasses UV400 Protection">
                                    </div>
                                </div>
                                <div class="">
                                    <div class="text-center">
                                        <span class="block text-primary fw-600">$99.400</span>
                                        <del class="block opacity-70">$140.000</del>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- Today's Deal : End --}}
    </div>
</section>

<header class="sticky top-0 bg-white shadow border-b z-40">

    {{-- Middle Bar : Start --}}
    <div class="relative logo-bar-area py-3">
        <div class="container">
            <div class="flex items-center justify-between gap-4">

                {{-- Logo : Start --}}
                <div class="col-auto xl:col-3 pl-0 pr-3 flex items-center flex-col ">
                    <a href="{{ route('front.homepage') }}"
                        class="flex items-center gap-2 simple-text logo-normal uppercase font-bold hover:text-current">
                        <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                            alt="Smart Tools Egypt Logo">
                        <span class="hidden md:block">
                            {{ __('front/homePage.Smart Tools Egypt') }}
                        </span>
                    </a>
                </div>
                {{-- Logo : End --}}

                {{-- Search : Start --}}
                <div class="grow front-header-search flex items-center bg-white ">
                    <div class="relative grow">
                        <label class="relative block m-0">
                            <span class="sr-only">Search</span>
                            <span class="absolute inset-y-0 rtl:left-1.5 ltr:right-1.5 flex items-center pl-2">
                                <span class="material-icons">
                                    search
                                </span>
                            </span>
                            <input
                                class="placeholder:italic placeholder:text-slate-400 text-gray-800 block bg-white w-full border border-slate-300 rounded-md py-2 ltr:pr-10 ltr:pl-3 rtl:pl-10 rtl:pr-3 shadow-sm focus:outline-none focus:border-gray-600 focus:ring-gray-600 focus:ring-1 sm:text-xs md:text-sm font-bold"
                                placeholder="{{ __("front/homePage.I'm Shopping for ...") }}" type="text"
                                name="search" />
                        </label>
                        <div class="typed-search-box stop-propagation document-click-hidden hidden bg-white rounded shadow-lg absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing hidden p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>
                {{-- Search : End --}}

                {{-- Compare : Start --}}
                @livewire('front.general.compare.compare-drop-down')

                {{-- Compare : End --}}

                {{-- Wishlist : Start --}}
                @livewire('front.general.wishlist.wishlist-drop-down')
                {{-- Wishlist : End --}}

                {{-- Cart :: Start --}}
                @livewire('front.general.cart.cart-drop-down')
                {{-- Cart :: End --}}

            </div>
        </div>
    </div>
    {{-- Middle Bar : End --}}

    {{-- Lower Bar : Start --}}
    <div class="bg-white border-top border-gray-200 py-1 scrollbar scrollbar-hidden-x">
        <div class="container">
            <ul class="flex flex-nowrap justify-between lg:justify-center items-center gap-2 text-center">
                <li class="min-w-max">
                    <a href="{{ route('front.homepage') }}"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.Home') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="#"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.Offers') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="#"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.Power tools') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="#"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.Hand tools') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="#"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.All Brands') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="#"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.All Categories') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="#"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.Contact us') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
    {{-- Lower Bar : End --}}

</header>

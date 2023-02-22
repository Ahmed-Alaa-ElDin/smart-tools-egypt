<header class="sticky top-0 bg-white shadow border-b z-40">

    {{-- Middle Bar : Start --}}
    <div class="relative logo-bar-area py-3">
        <div class="container">
            <div class="flex items-center justify-between gap-4">

                {{-- Logo : Start --}}
                <div class="col-auto xl:col-3 pl-0 pr-3 flex items-center flex-col ">
                    <a href="{{ route('front.homepage') }}"
                        class="flex items-center gap-2 simple-text logo-normal uppercase font-bold hover:text-current">
                        <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" alt="Smart Tools Egypt Logo">
                        <span class="hidden md:block">
                            {{ __('front/homePage.Smart Tools Egypt') }}
                        </span>
                    </a>
                </div>
                {{-- Logo : End --}}

                {{-- Search : Start --}}
                @livewire('front.homepage.header-search-box')
                {{-- Search : End --}}

                @section('cart-wishlist-compare')
                    {{-- Compare : Start --}}
                    @livewire('front.general.compare.compare-drop-down')

                    {{-- Compare : End --}}

                    {{-- Wishlist : Start --}}
                    @livewire('front.general.wishlist.wishlist-drop-down')
                    {{-- Wishlist : End --}}

                    {{-- Cart :: Start --}}
                    @livewire('front.general.cart.cart-drop-down')
                    {{-- Cart :: End --}}
                @show

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
                    <a href="{{ route('front.brand.index') }}"
                        class="opacity-60 text-xs md:text-sm font-bold px-3 py-2 inline-block fw-600 hover:opacity-100 hover:text-gray-900 text-reset">
                        {{ __('front/homePage.All Brands') }}
                    </a>
                </li>
                <li class="min-w-max">
                    <a href="{{ route('front.supercategory.index') }}"
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

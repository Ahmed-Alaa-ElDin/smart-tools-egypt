<header class="sticky top-0 py-4 bg-white shadow border-b z-40">
    <div class="relative logo-bar-area">
        <div class="container">
            <div class="flex items-center justify-between gap-3">

                <div class="col-auto xl:col-3 pl-0 pr-3 flex items-center flex-col">
                    <a href="{{ route('front.homepage') }}" class="flex items-center gap-2 simple-text logo-normal uppercase font-bold">
                        <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" alt="Smart Tools Egypt Logo">
                        {{ __('Smart Tools Egypt') }}
                    </a>
                </div>

                <div class="grow front-header-search flex items-center bg-white">
                    <div class="relative grow">
                        <label class="relative block">
                            <span class="sr-only">Search</span>
                            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                                <span class="material-icons">
                                    search
                                </span>
                            </span>
                            <input
                                class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
                                placeholder="Search for anything..." type="text" name="search" />
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

                <div class="hidden lg:block ml-3 mr-0">
                    <div class="" id="compare">
                        <a href="https://demo.activeitzone.com/ecommerce/compare"
                            class="flex items-center text-reset">
                            <i class="la la-refresh la-2x opacity-80"></i>
                            <span class="grow ml-1">
                                <span class="badge badge-primary badge-inline badge-pill">0</span>
                                <span class="nav-box-text hidden d-xl-block opacity-70">قارن</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block ml-3 mr-0">
                    <div class="" id="wishlist">
                        <a href="https://demo.activeitzone.com/ecommerce/wishlists"
                            class="flex items-center text-reset">
                            <i class="la la-heart-o la-2x opacity-80"></i>
                            <span class="grow ml-1">
                                <span class="badge badge-primary badge-inline badge-pill">0</span>
                                <span class="nav-box-text hidden d-xl-block opacity-70">قائمة
                                    الرغبات</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block  align-self-stretch ml-3 mr-0" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items">
                        <a href="javascript:void(0)" class="flex items-center text-reset h-100"
                            data-toggle="dropdown" data-display="static">
                            <i class="la la-shopping-cart la-2x opacity-80"></i>
                            <span class="grow ml-1">
                                <span class="badge badge-primary badge-inline badge-pill cart-count">0</span>
                                <span class="nav-box-text hidden d-xl-block opacity-70">عربة
                                    التسوق</span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg p-0 stop-propagation">

                            <div class="text-center p-3">
                                <i class="las la-frown la-3x opacity-60 mb-3"></i>
                                <h3 class="h6 fw-700">عربة التسوق فارغة</h3>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>

<div class="sidebar" data-color="red" data-background-color="white"
    data-image="{{ asset('assets/img/logos/smart-tools-logo-only-400.png') }}">
    <div class="logo">
        <a href="{{ route('admin.dashboard') }}" class="simple-text logo-normal">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" alt="Smart Tools Egypt Logo" width="50px">
            {{ 'Smart Tools' }}
        </a>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-thin scrollbar-thumb-red-100 scrollbar-track-gray-100">

        {{-- Nav Menu for mobile --}}
        <ul class="nav navbar-nav nav-mobile-menu ">

            <li class="nav-item mt-2 flex justify-around ">
                @foreach (LaravelLocalization::getSupportedLocales() as $lg => $lang)
                    <a href="{{ LaravelLocalization::getLocalizedURL($lg) }}"
                        class="@if ($lang['name'] == LaravelLocalization::getCurrentLocaleName()) bg-primary
                        text-white
                        font-bold
                        @else
                        bg-gray-100
                        text-black @endif
                        shadow
                    ">{{ $lang['native'] }}</a>
                @endforeach
            </li>

            {{-- Notifications --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#notifications" aria-expanded="false">
                    <i class="material-icons">notifications</i>
                    <span class="notification">5</span>
                    <p class="d-lg-none d-md-block">
                        Some Actions
                    </p>
                </a>

                <div class="collapse" id="notifications">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="dropdown-item nav-item" href="#">Mike John responded to your email</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Profile --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#profile" aria-expanded="false">
                    @if (auth()->user()->profile_photo_path)
                        <img class="h-10 w-10 rounded-full"
                            src="{{ asset('storage/images/profiles/cropped100/' . auth()->user()->profile_photo_path) }}"
                            alt="{{ auth()->user()->f_name . ' ' . auth()->user()->l_name . 'profile image' }}">
                    @else
                        <span class="material-icons">
                            person
                        </span>
                    @endif
                    <span class="ltr:ml-2 rtl:mr-2">{{ auth()->user()->f_name }} <b class="caret"></b></span>
                </a>

                <div class="collapse" id="profile">
                    <ul class="nav">
                        <li class="nav-item w-50">
                            <a class="ml-10 dropdown-item"
                                href="{{ route('admin.dashboard') }}">{{ __('admin/master.Profile') }}</a>
                        </li>
                        <li class="nav-item w-50">
                            <a class="ml-10 dropdown-item" href="#">{{ __('admin/master.Settings') }}</a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item w-50">
                            <a class="ml-10 dropdown-item" href="{{ route('admin.logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('admin/master.Log out') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
            <div class="dropdown-divider border-gray-300"></div>
        </ul>

        <ul class="nav">

            {{-- Home Page --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('front.homepage') }}">
                    <span class="material-icons">
                        home
                    </span>
                    <span>{{ __('admin/master.HomePage') }}</span>
                </a>
            </li>

            {{-- Dashboard --}}
            <li class="nav-item {{ $activeSection == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <span class="material-icons">
                        dashboard
                    </span>
                    <span>{{ __('admin/master.dashboard') }}</span>
                </a>
            </li>

            {{-- Orders --}}
            <li class="nav-item {{ $activeSection == 'Orders' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#orders"
                    aria-expanded="{{ $activeSection == 'Orders' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2">
                                <path stroke-linecap="round"
                                    d="M11.029 2.54a2 2 0 0 1 1.942 0l7.515 4.174a1 1 0 0 1 .514.874v8.235a2 2 0 0 1-1.029 1.748l-7 3.89a2 2 0 0 1-1.942 0l-7-3.89A2 2 0 0 1 3 15.824V7.588a1 1 0 0 1 .514-.874L11.03 2.54Z" />
                                <path stroke-linecap="round" d="m7.5 4.5l9 5V13M6 12.328L9 14" />
                                <path d="m3 7l9 5m0 0l9-5m-9 5v10" />
                            </g>
                        </svg> </span>
                    <span>{{ __('admin/master.Orders') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Orders' ? ' show' : '' }}" id="orders">
                    <ul class="nav">
                        {{-- Add Order --}}
                        <li class="nav-item {{ $activePage == 'Add Order' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.create') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M12.5 17H6V3H4" />
                                            <path d="m6 5l14 1l-.86 6.017M16.5 13H6m10 6h6m-3-3v6" />
                                        </g>
                                    </svg> </span>
                                <span>{{ __('admin/master.Add Order') }} </span>
                            </a>
                        </li>

                        {{-- See All Orders --}}
                        <li class="nav-item {{ $activePage == 'All Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path
                                                d="M4 19a2 2 0 1 0 4 0a2 2 0 1 0-4 0m11 0a2 2 0 1 0 4 0a2 2 0 1 0-4 0" />
                                            <path d="M17 17H6V3H4" />
                                            <path d="m6 5l14 1l-1 7H6" />
                                        </g>
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.All Orders') }} </span>
                            </a>
                        </li>

                        {{-- See New Orders --}}
                        <li class="nav-item {{ $activePage == 'New Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.new-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M12.5 17H6V3H4" />
                                            <path d="m6 5l14 1l-.859 6.011M16.5 13H6m13 3v6m3-3l-3 3l-3-3" />
                                        </g>
                                    </svg> </span>
                                <span>{{ __('admin/master.New Orders') }} </span>
                            </a>
                        </li>

                        {{-- See Approved Orders --}}
                        <li class="nav-item {{ $activePage == 'Approved Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.approved-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M11.5 17H6V3H4" />
                                            <path d="m6 5l14 1l-1 7H6m9 6l2 2l4-4" />
                                        </g>
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Approved Orders') }} </span>
                            </a>
                        </li>

                        {{-- See Edited Orders --}}
                        <li class="nav-item {{ $activePage == 'Edited Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.edited-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M12 17H6V3H4" />
                                            <path
                                                d="m6 5l14 1l-.79 5.526M16 13H6m11.001 6a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
                                        </g>
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Edited Orders') }} </span>
                            </a>
                        </li>

                        {{-- See Ready for Shipping Orders --}}
                        <li class="nav-item {{ $activePage == 'Ready for Shipping Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.ready-for-shipping-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M9.5 17H6V3H4" />
                                            <path
                                                d="m6 5l14 1l-.615 4.302M12.5 13H6m11.8 7.817l-2.172 1.138a.392.392 0 0 1-.568-.41l.415-2.411l-1.757-1.707a.389.389 0 0 1 .217-.665l2.428-.352l1.086-2.193a.392.392 0 0 1 .702 0l1.086 2.193l2.428.352a.39.39 0 0 1 .217.665l-1.757 1.707l.414 2.41a.39.39 0 0 1-.567.411z" />
                                        </g>
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Ready for Shipping Orders') }} </span>
                            </a>
                        </li>

                        {{-- See Shipped Orders --}}
                        <li class="nav-item {{ $activePage == 'Shipped Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.shipped-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M13.5 17H6V3H4" />
                                            <path d="m6 5l14 1l-.858 6.004M16.5 13H6m13 3l-2 3h4l-2 3" />
                                        </g>
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Shipped Orders') }} </span>
                            </a>
                        </li>

                        {{-- See Delivered Orders --}}
                        <li class="nav-item {{ $activePage == 'Delivered Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.delivered-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M12.5 17H6V3H4" />
                                            <path d="m6 5l14 1l-.854 5.977M16.5 13H6m13 9v-6m3 3l-3-3l-3 3" />
                                        </g>
                                    </svg> </span>
                                <span>{{ __('admin/master.Delivered Orders') }} </span>
                            </a>
                        </li>

                        {{-- See Suspended Orders --}}
                        <li class="nav-item {{ $activePage == 'Suspended Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.suspended-orders') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M15 17H6V3H4" />
                                            <path d="m6 5l14 1l-.854 5.976M16.5 13H6m13 3v3m0 3v.01" />
                                        </g>
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Suspended Orders') }} </span>
                            </a>
                        </li>

                        {{-- Deleted Orders --}}
                        <li class="nav-item {{ $activePage == 'Deleted Orders' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.orders.softDeletedOrders') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        viewBox="0 0 24 24">
                                        <g fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                            <path d="M13 17H6V3H4" />
                                            <path d="m6 5l14 1l-1 7H6m16 9l-5-5m0 5l5-5" />
                                        </g>
                                    </svg> </span>
                                <span>{{ __('admin/master.Archived Orders') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            {{-- Products --}}
            <li class="nav-item {{ $activeSection == 'Products' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#products"
                    aria-expanded="{{ $activeSection == 'Products' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32">
                            <path fill="currentColor"
                                d="M20.394 13.675A2.329 2.329 0 0 1 18.069 16h-4.394v-4.65h4.394a2.329 2.329 0 0 1 2.325 2.325zM31.5 16c0 8.563-6.938 15.5-15.5 15.5S.5 24.562.5 16C.5 7.437 7.438.5 16 .5S31.5 7.438 31.5 16zm-8.006-2.325a5.428 5.428 0 0 0-5.425-5.425h-7.494v15.5h3.1V19.1h4.394a5.428 5.428 0 0 0 5.425-5.425z" />
                        </svg>
                    </span>
                    <span>{{ __('admin/master.Products') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Products' ? ' show' : '' }}" id="products">
                    <ul class="nav">

                        {{-- See All Products --}}
                        <li class="nav-item {{ $activePage == 'All Products' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.products.index') }}">
                                <span class="material-icons">
                                    construction
                                </span>
                                <span>{{ __('admin/master.All Products') }} </span>
                            </a>
                        </li>

                        {{-- Add Product --}}
                        <li class="nav-item {{ $activePage == 'Add Product' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.products.create') }}">
                                <span class="material-icons">
                                    control_point
                                </span>
                                <span>{{ __('admin/master.Add Product') }} </span>
                            </a>
                        </li>

                        {{-- Deleted Users --}}
                        <li class="nav-item {{ $activePage == 'Deleted Products' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.products.softDeletedProducts') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    auto_delete
                                </span>
                                <span>{{ __('admin/master.Deleted Products') }} </span>
                            </a>
                        </li>

                        {{-- Brands --}}
                        <li class="nav-item {{ $activePage == 'Brands' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.brands.index') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 64 64">
                                        <path fill="currentColor"
                                            d="M36.604 23.043c-.623-.342-1.559-.512-2.805-.512h-6.693v7.795h6.525c1.295 0 2.268-.156 2.916-.473c1.146-.551 1.721-1.639 1.721-3.268c0-1.757-.555-2.939-1.664-3.542" />
                                        <path fill="currentColor"
                                            d="M32.002 2C15.434 2 2 15.432 2 32s13.434 30 30.002 30s30-13.432 30-30s-13.432-30-30-30m12.82 44.508h-6.693a20.582 20.582 0 0 1-.393-1.555a14.126 14.126 0 0 1-.256-2.5l-.041-2.697c-.023-1.85-.344-3.084-.959-3.701c-.613-.615-1.766-.924-3.453-.924h-5.922v11.377H21.18V17.492h13.879c1.984.039 3.51.289 4.578.748s1.975 1.135 2.717 2.027a9.07 9.07 0 0 1 1.459 2.441c.357.893.537 1.908.537 3.051c0 1.379-.348 2.732-1.043 4.064s-1.844 2.273-3.445 2.826c1.338.537 2.287 1.303 2.844 2.293c.559.99.838 2.504.838 4.537v1.949c0 1.324.053 2.225.16 2.697c.16.748.533 1.299 1.119 1.652v.731z" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Brands') }} </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- Collections --}}
            <li class="nav-item {{ $activeSection == 'Collections' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#collections"
                    aria-expanded="{{ $activeSection == 'Collections' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10" />
                        </svg>
                    </span>
                    <span>{{ __('admin/master.Collections') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Collections' ? ' show' : '' }}" id="collections">
                    <ul class="nav">

                        {{-- See All Collections --}}
                        <li class="nav-item {{ $activePage == 'All Collections' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.collections.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.All Collections') }} </span>
                            </a>
                        </li>

                        {{-- Add Collection --}}
                        <li class="nav-item {{ $activePage == 'Add Collection' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.collections.create') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="m11.066 8.004l.184-.005h7.5a3.25 3.25 0 0 1 3.245 3.065l.005.185v1.56a6.518 6.518 0 0 0-1.5-1.077v-.483a1.75 1.75 0 0 0-1.75-1.75h-7.5a1.75 1.75 0 0 0-1.744 1.606l-.006.144v7.5a1.75 1.75 0 0 0 1.607 1.744l.143.006h.482c.287.55.65 1.055 1.076 1.5H11.25a3.25 3.25 0 0 1-3.245-3.066L8 18.75v-7.5a3.25 3.25 0 0 1 3.066-3.245Zm4.516-3.771l.052.177l.693 2.588h-1.553l-.588-2.2a1.75 1.75 0 0 0-2.144-1.238L4.798 5.502a1.75 1.75 0 0 0-1.27 1.995l.032.148l1.942 7.244A1.75 1.75 0 0 0 7 16.176v1.506a3.252 3.252 0 0 1-2.895-2.228l-.052-.176l-1.941-7.245a3.25 3.25 0 0 1 2.12-3.928l.178-.052l7.244-1.941a3.25 3.25 0 0 1 3.928 2.12ZM23 17.5a5.5 5.5 0 1 0-11 0a5.5 5.5 0 0 0 11 0Zm-5.59-3.493L17.5 14l.09.008a.5.5 0 0 1 .402.402l.008.09V17l2.505.001l.09.008a.5.5 0 0 1 .402.402l.008.09l-.008.09a.5.5 0 0 1-.403.402l-.09.008h-2.503v2.503l-.008.09a.5.5 0 0 1-.402.402l-.09.008l-.09-.008a.5.5 0 0 1-.402-.402l-.008-.09V18h-2.503l-.09-.008a.5.5 0 0 1-.402-.402l-.008-.09l.008-.09a.5.5 0 0 1 .402-.402l.09-.008H17v-2.5l.008-.09a.5.5 0 0 1 .402-.403Z" />
                                    </svg> </span>
                                <span>{{ __('admin/master.Add Collection') }} </span>
                            </a>
                        </li>

                        {{-- Deleted Collections --}}
                        <li class="nav-item {{ $activePage == 'Deleted Collections' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.collections.softDeletedCollections') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    auto_delete
                                </span>
                                <span>{{ __('admin/master.Deleted Collections') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Offers --}}
            <li class="nav-item {{ $activeSection == 'Offers' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#offers"
                    aria-expanded="{{ $activeSection == 'Offers' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m20.749 12l1.104-1.908a1 1 0 0 0-.365-1.366l-1.91-1.104v-2.2a1 1 0 0 0-1-1h-2.199l-1.103-1.909a1.008 1.008 0 0 0-.607-.466a.993.993 0 0 0-.759.1L12 3.251l-1.91-1.105a1 1 0 0 0-1.366.366L7.62 4.422H5.421a1 1 0 0 0-1 1v2.199l-1.91 1.104a.998.998 0 0 0-.365 1.367L3.25 12l-1.104 1.908a1.004 1.004 0 0 0 .364 1.367l1.91 1.104v2.199a1 1 0 0 0 1 1h2.2l1.104 1.91a1.01 1.01 0 0 0 .866.5c.174 0 .347-.046.501-.135l1.908-1.104l1.91 1.104a1.001 1.001 0 0 0 1.366-.365l1.103-1.91h2.199a1 1 0 0 0 1-1v-2.199l1.91-1.104a1 1 0 0 0 .365-1.367L20.749 12zM9.499 6.99a1.5 1.5 0 1 1-.001 3.001a1.5 1.5 0 0 1 .001-3.001zm.3 9.6l-1.6-1.199l6-8l1.6 1.199l-6 8zm4.7.4a1.5 1.5 0 1 1 .001-3.001a1.5 1.5 0 0 1-.001 3.001z" />
                        </svg>
                    </span>
                    <span>{{ __('admin/master.Offers') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Offers' ? ' show' : '' }}" id="offers">
                    <ul class="nav">

                        {{-- See All Offers --}}
                        <li class="nav-item {{ $activePage == 'All Offers' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.offers.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 48 48">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="4"
                                            d="m6 12l36 4v24L6 36V12Zm32 3.555V8L6 12" />
                                    </svg> </span>
                                <span>{{ __('admin/master.All Offers') }} </span>
                            </a>
                        </li>

                        {{-- Add Offer --}}
                        <li class="nav-item {{ $activePage == 'Add Offer' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.offers.create') }}">
                                <span class="material-icons">
                                    control_point
                                </span>
                                <span>{{ __('admin/master.Add Offer') }} </span>
                            </a>
                        </li>

                        {{-- All Coupons --}}
                        <li class="nav-item {{ $activePage == 'All Coupons' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.coupons.index') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M21 5H3a1 1 0 0 0-1 1v4h.893c.996 0 1.92.681 2.08 1.664A2.001 2.001 0 0 1 3 14H2v4a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-4h-1a2.001 2.001 0 0 1-1.973-2.336c.16-.983 1.084-1.664 2.08-1.664H22V6a1 1 0 0 0-1-1zM11 17H9v-2h2v2zm0-4H9v-2h2v2zm0-4H9V7h2v2z" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.All Coupons') }} </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- Categories --}}
            <li class="nav-item {{ $activeSection == 'Categories System' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#categories"
                    aria-expanded="{{ $activeSection == 'Categories' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        category
                    </span>
                    <span>{{ __('admin/master.Categories System') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Categories System' ? ' show' : '' }}" id="categories">
                    <ul class="nav">

                        {{-- See Supercategories --}}
                        <li class="nav-item {{ $activePage == 'Supercategories' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.supercategories.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 32 32" class="inline-block">
                                        <path fill="currentColor" d="M30 30h-8V4h8zm-10 0h-8V12h8zm-10 0H2V18h8z" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Supercategories') }} </span>
                            </a>
                        </li>

                        {{-- See Categories --}}
                        <li class="nav-item {{ $activePage == 'Categories' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 32 32" class="inline-block">
                                        <path fill="currentColor"
                                            d="M30 30h-8V4h8zm-6-2h4V6h-4zm-4 2h-8V12h8zm-10 0H2V18h8z" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Categories') }} </span>
                            </a>
                        </li>

                        {{-- See Sub Categories --}}
                        <li class="nav-item {{ $activePage == 'Subcategories' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.subcategories.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 32 32" class="inline-block">
                                        <path fill="currentColor"
                                            d="M30 30h-8V4h8zm-6-2h4V6h-4zm-4 2h-8V12h8zm-6-2h4V14h-4zm-4 2H2V18h8z" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.Sub Categories') }} </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- Users --}}
            <li class="nav-item {{ $activeSection == 'Users' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#users"
                    aria-expanded="{{ $activeSection == 'Users' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        manage_accounts
                    </span>
                    <span>{{ __('admin/master.users') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Users' ? ' show' : '' }}" id="users">
                    <ul class="nav">

                        {{-- See All Users --}}
                        <li class="nav-item {{ $activePage == 'All Users' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <span class="material-icons">
                                    people
                                </span>
                                <span>{{ __('admin/master.All Users') }} </span>
                            </a>
                        </li>

                        {{-- Add New User --}}
                        <li class="nav-item {{ $activePage == 'Add User' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users.create') }}">
                                <span class="material-icons">
                                    person_add
                                </span>
                                <span>{{ __('admin/master.add user') }} </span>
                            </a>
                        </li>

                        {{-- Deleted Users --}}
                        <li class="nav-item {{ $activePage == 'Deleted Users' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users.softDeletedUsers') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    person_off
                                </span>
                                <span>{{ __('admin/master.Deleted Users') }} </span>
                            </a>
                        </li>

                        {{-- Manage Roles --}}
                        <li class="nav-item {{ $activePage == 'Roles Management' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                <span class="material-icons">
                                    admin_panel_settings
                                </span>
                                <span>{{ __('admin/master.Roles Management') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Customers --}}
            <li class="nav-item {{ $activeSection == 'Customers' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#customers"
                    aria-expanded="{{ $activeSection == 'Customers' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48">
                            <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                <path
                                    d="M30.492 17.142a1 1 0 0 1 1.213.698a8 8 0 1 1-15.41 0a1 1 0 0 1 1.213-.698c3.722.96 9.262.96 12.984 0ZM18.04 19.314a6 6 0 1 0 11.923 0c-3.617.73-8.307.73-11.923 0Z" />
                                <path
                                    d="M19 28.49C13.013 29.535 6 32.203 6 36v6h36v-6c0-3.797-7.013-6.465-13-7.51V33H19v-4.51ZM13 42v-9h2v9h-2Zm21-9v9h2v-9h-2ZM25 6.058v4.422a1 1 0 0 1-2 0V6a8.928 8.928 0 0 0-3 .69v5.79a1 1 0 0 1-2 0V7.935c-1.37 1.178-2.16 2.706-2.411 4.045a5.572 5.572 0 0 0-.024.14l-.02.02c-.089.629-.04 1.147.088 1.503a1 1 0 0 1-1.882.675a3.629 3.629 0 0 1-.048-.14c-5.807 7.582 26.433 7.578 20.584-.013a4.292 4.292 0 0 1-.048.146a1 1 0 0 1-1.887-.661c.144-.414.186-.93.108-1.5a1.058 1.058 0 0 0-.018-.013C32.226 10.785 31.404 9.29 30 8.112v4.368a1 1 0 0 1-2 0V6.862a9.454 9.454 0 0 0-1.203-.445A10.686 10.686 0 0 0 25 6.057Z" />
                            </g>
                        </svg>
                    </span>
                    <span>{{ __('admin/master.Customers') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Customers' ? ' show' : '' }}" id="customers">
                    <ul class="nav">

                        {{-- See All Customers --}}
                        <li class="nav-item {{ $activePage == 'All Customers' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.customers.index') }}">
                                <span class="material-icons">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                        width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 36 36">
                                        <path fill="currentColor"
                                            d="M12 16.14h-.87a8.67 8.67 0 0 0-6.43 2.52l-.24.28v8.28h4.08v-4.7l.55-.62l.25-.29a11 11 0 0 1 4.71-2.86A6.59 6.59 0 0 1 12 16.14Z"
                                            class="clr-i-solid clr-i-solid-path-1" />
                                        <path fill="currentColor"
                                            d="M31.34 18.63a8.67 8.67 0 0 0-6.43-2.52a10.47 10.47 0 0 0-1.09.06a6.59 6.59 0 0 1-2 2.45a10.91 10.91 0 0 1 5 3l.25.28l.54.62v4.71h3.94v-8.32Z"
                                            class="clr-i-solid clr-i-solid-path-2" />
                                        <path fill="currentColor"
                                            d="M11.1 14.19h.31a6.45 6.45 0 0 1 3.11-6.29a4.09 4.09 0 1 0-3.42 6.33Z"
                                            class="clr-i-solid clr-i-solid-path-3" />
                                        <path fill="currentColor"
                                            d="M24.43 13.44a6.54 6.54 0 0 1 0 .69a4.09 4.09 0 0 0 .58.05h.19A4.09 4.09 0 1 0 21.47 8a6.53 6.53 0 0 1 2.96 5.44Z"
                                            class="clr-i-solid clr-i-solid-path-4" />
                                        <circle cx="17.87" cy="13.45" r="4.47" fill="currentColor"
                                            class="clr-i-solid clr-i-solid-path-5" />
                                        <path fill="currentColor"
                                            d="M18.11 20.3A9.69 9.69 0 0 0 11 23l-.25.28v6.33a1.57 1.57 0 0 0 1.6 1.54h11.49a1.57 1.57 0 0 0 1.6-1.54V23.3l-.24-.3a9.58 9.58 0 0 0-7.09-2.7Z"
                                            class="clr-i-solid clr-i-solid-path-6" />
                                        <path fill="none" d="M0 0h36v36H0z" />
                                    </svg>
                                </span>
                                <span>{{ __('admin/master.All Customers') }} </span>
                            </a>
                        </li>

                        {{-- Add New Customer --}}
                        <li class="nav-item {{ $activePage == 'Add Customer' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.customers.create') }}">
                                <span class="material-icons">
                                    person_add
                                </span>
                                <span>{{ __('admin/master.Add Customer') }} </span>
                            </a>
                        </li>

                        {{-- Deleted Customers --}}
                        <li class="nav-item {{ $activePage == 'Deleted Customers' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.customers.softDeletedUsers') }}">
                                <span class="material-icons rtl:ml-2 ltr:mr-2">
                                    person_off
                                </span>
                                <span>{{ __('admin/master.Deleted Customers') }} </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- Delivery System --}}
            <li class="nav-item {{ $activeSection == 'Delivery System' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#delivery"
                    aria-expanded="{{ $activeSection == 'Delivery System' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        local_shipping
                    </span>
                    <span>{{ __('admin/master.Delivery System') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Delivery System' ? ' show' : '' }}" id="delivery">
                    <ul class="nav">

                        {{-- Delivery Companies --}}
                        <li class="nav-item {{ $activePage == 'Delivery Companies' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.deliveries.index') }}">
                                <span class="material-icons">
                                    business
                                </span>
                                <span>{{ __('admin/master.Delivery Companies') }} </span>
                            </a>
                        </li>

                        {{-- Countries --}}
                        <li class="nav-item {{ $activePage == 'Countries' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.countries.index') }}">
                                <span class="material-icons">
                                    public
                                </span>
                                <span>{{ __('admin/master.Countries') }}</span>
                            </a>
                        </li>

                        {{-- Governorates --}}
                        <li class="nav-item {{ $activePage == 'Governorates' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.governorates.index') }}">
                                <span class="material-icons">
                                    travel_explore
                                </span>
                                <span>{{ __('admin/master.Governorates') }}</span>
                            </a>
                        </li>

                        {{-- Cities --}}
                        <li class="nav-item {{ $activePage == 'Cities' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.cities.index') }}">
                                <span class="material-icons">
                                    location_city
                                </span>
                                <span>{{ __('admin/master.Cities') }}</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- Website Control --}}
            <li class="nav-item {{ $activeSection == 'Site Control' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#site"
                    aria-expanded="{{ $activeSection == 'Site Control' ? 'true' : 'false' }}">
                    <span class="material-icons">
                        settings
                    </span>
                    <span>{{ __('admin/master.Website Control') }}
                        <b class="caret"></b>
                    </span>
                </a>

                <div class="collapse {{ $activeSection == 'Site Control' ? ' show' : '' }}" id="site">
                    <ul class="nav">

                        {{-- General --}}
                        <li class="nav-item {{ $activePage == 'general' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.setting.general') }}">
                                <span class="material-icons">
                                    settings_suggest
                                </span>
                                <span>{{ __('admin/master.General Settings') }} </span>
                            </a>
                        </li>

                        {{-- HomePage --}}
                        <li class="nav-item {{ $activePage == 'HomePage' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.setting.homepage') }}">
                                <span class="material-icons">
                                    space_dashboard
                                </span>
                                <span>{{ __('admin/master.HomePage') }} </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>


        </ul>
    </div>
</div>

<div class="sidebar" data-color="red" data-background-color="white"
    data-image="{{ asset('assets/img/logos/smart-tools-logo-only-400.png') }}">
    <div class="logo">
        <a href="https://creative-tim.com/" class="simple-text logo-normal">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" alt="Smart Tools Egypt Logo"
                width="50px">
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

            {{-- Dashboard --}}
            @can('See Dashboard')
                <li class="nav-item {{ $activeSection == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <span class="material-icons">
                            dashboard
                        </span>
                        <span>{{ __('admin/master.dashboard') }}</span>
                    </a>
                </li>
            @endcan

            {{-- Orders --}}
            @can('See All Users')
                <li class="nav-item {{ $activeSection == 'Orders' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#orders"
                        aria-expanded="{{ $activeSection == 'Orders' ? 'true' : 'false' }}">
                        <span class="material-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
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

                            {{-- See All Orders --}}
                            <li class="nav-item {{ $activePage == 'All Orders' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                    <span class="material-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1.13em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 576 512">
                                            <path fill="currentColor"
                                                d="M560 288h-80v96l-32-21.3l-32 21.3v-96h-80c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16zm-384-64h224c8.8 0 16-7.2 16-16V16c0-8.8-7.2-16-16-16h-80v96l-32-21.3L256 96V0h-80c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16zm64 64h-80v96l-32-21.3L96 384v-96H16c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16h224c8.8 0 16-7.2 16-16V304c0-8.8-7.2-16-16-16z" />
                                        </svg>
                                    </span>
                                    <span>{{ __('admin/master.All Orders') }} </span>
                                </a>
                            </li>

                            {{-- Add Order --}}
                            <li class="nav-item {{ $activePage == 'Add Order' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.orders.create') }}">
                                    <span class="material-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M12 9c.55 0 1-.45 1-1V6h2c.55 0 1-.45 1-1s-.45-1-1-1h-2V2c0-.55-.45-1-1-1s-1 .45-1 1v2H9c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1zm-5 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2s-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2s2-.9 2-2s-.9-2-2-2zm-8.9-5h7.45c.75 0 1.41-.41 1.75-1.03l3.24-6.14a.998.998 0 0 0-.4-1.34a.996.996 0 0 0-1.36.41L15.55 11H8.53L4.27 2H2c-.55 0-1 .45-1 1s.45 1 1 1h1l3.6 7.59l-1.35 2.44C4.52 15.37 5.48 17 7 17h11c.55 0 1-.45 1-1s-.45-1-1-1H7l1.1-2z" />
                                        </svg>
                                    </span>
                                    <span>{{ __('admin/master.Add Order') }} </span>
                                </a>
                            </li>

                            {{-- Deleted Orders --}}
                            <li class="nav-item {{ $activePage == 'Deleted Orders' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.orders.softDeletedOrders') }}">
                                    <span class="material-icons rtl:ml-2 ltr:mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M14.12 8.53L12 6.41L9.88 8.54L8.46 7.12L10.59 5L8.47 2.88l1.41-1.41L12 3.59l2.12-2.13l1.42 1.42L13.41 5l2.12 2.12l-1.41 1.41M7 18a2 2 0 0 1 2 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2a2 2 0 0 1 2-2m10 0a2 2 0 0 1 2 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2a2 2 0 0 1 2-2m-9.83-3.25a.25.25 0 0 0 .25.25H19v2H7a2 2 0 0 1-2-2c0-.35.09-.68.25-.96l1.35-2.45L3 4H1V2h3.27l.94 2l.95 2l2.24 4.73l.13.27h7.02l2.76-5l1.1-2h.01l1.74.96l-3.86 7.01c-.34.62-1 1.03-1.75 1.03H8.1l-.9 1.63l-.03.12Z" />
                                        </svg>
                                    </span>
                                    <span>{{ __('admin/master.Deleted Orders') }} </span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcan


            {{-- Products --}}
            @can('See All Users')
                <li class="nav-item {{ $activeSection == 'Products' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#products"
                        aria-expanded="{{ $activeSection == 'Products' ? 'true' : 'false' }}">
                        <span class="material-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64">
                                            <path fill="currentColor"
                                                d="M36.604 23.043c-.623-.342-1.559-.512-2.805-.512h-6.693v7.795h6.525c1.295 0 2.268-.156 2.916-.473c1.146-.551 1.721-1.639 1.721-3.268c0-1.757-.555-2.939-1.664-3.542" />
                                            <path fill="currentColor"
                                                d="M32.002 2C15.434 2 2 15.432 2 32s13.434 30 30.002 30s30-13.432 30-30s-13.432-30-30-30m12.82 44.508h-6.693a20.582 20.582 0 0 1-.393-1.555a14.126 14.126 0 0 1-.256-2.5l-.041-2.697c-.023-1.85-.344-3.084-.959-3.701c-.613-.615-1.766-.924-3.453-.924h-5.922v11.377H21.18V17.492h13.879c1.984.039 3.51.289 4.578.748s1.975 1.135 2.717 2.027a9.07 9.07 0 0 1 1.459 2.441c.357.893.537 1.908.537 3.051c0 1.379-.348 2.732-1.043 4.064s-1.844 2.273-3.445 2.826c1.338.537 2.287 1.303 2.844 2.293c.559.99.838 2.504.838 4.537v1.949c0 1.324.053 2.225.16 2.697c.16.748.533 1.299 1.119 1.652v.731z" />
                                        </svg> </span>
                                    <span>{{ __('admin/master.Brands') }} </span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endcan

            {{-- Offers --}}
            @can('See All Users')
                <li class="nav-item {{ $activeSection == 'Offers' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#offers"
                        aria-expanded="{{ $activeSection == 'Offers' ? 'true' : 'false' }}">
                        <span class="material-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
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
            @endcan

            {{-- Categories --}}
            @can('See All Users')
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
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"
                                            class="inline-block">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"
                                            class="inline-block">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"
                                            class="inline-block">
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
            @endcan

            {{-- Users --}}
            @can('See All Users')
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
                            @can('Add User')
                                <li class="nav-item {{ $activePage == 'Add User' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.users.create') }}">
                                        <span class="material-icons">
                                            person_add
                                        </span>
                                        <span>{{ __('admin/master.add user') }} </span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Deleted Users --}}
                            @can('Force Delete User')
                                <li class="nav-item {{ $activePage == 'Deleted Users' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.users.softDeletedUsers') }}">
                                        <span class="material-icons rtl:ml-2 ltr:mr-2">
                                            person_off
                                        </span>
                                        <span>{{ __('admin/master.Deleted Users') }} </span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Manage Roles --}}
                            @can('See All Roles')
                                <li class="nav-item {{ $activePage == 'Roles Management' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                        <span class="material-icons">
                                            admin_panel_settings
                                        </span>
                                        <span>{{ __('admin/master.Roles Management') }} </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcan


            {{-- Delivery System --}}
            @can('See Delivery System')
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
                            @can('See All Countries')
                                <li class="nav-item {{ $activePage == 'Countries' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.countries.index') }}">
                                        <span class="material-icons">
                                            public
                                        </span>
                                        <span>{{ __('admin/master.Countries') }}</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Governorates --}}
                            @can('See All Governorates')
                                <li class="nav-item {{ $activePage == 'Governorates' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.governorates.index') }}">
                                        <span class="material-icons">
                                            travel_explore
                                        </span>
                                        <span>{{ __('admin/master.Governorates') }}</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Cities --}}
                            @can('See All Cities')
                                <li class="nav-item {{ $activePage == 'Cities' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.cities.index') }}">
                                        <span class="material-icons">
                                            location_city
                                        </span>
                                        <span>{{ __('admin/master.Cities') }}</span>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>
            @endcan

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

                            {{-- HomePage --}}
                            <li class="nav-item {{ $activePage == 'HomePage' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.homepage') }}">
                                    <span class="material-icons">
                                        space_dashboard
                                    </span>
                                    <span>{{ __('admin/master.HomePage') }} </span>
                                </a>
                            </li>

                            {{-- Countries --}}
                            @can('See All Countries')
                                <li class="nav-item {{ $activePage == 'Countries' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.countries.index') }}">
                                        <span class="material-icons">
                                            public
                                        </span>
                                        <span>{{ __('admin/master.Countries') }}</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Governorates --}}
                            @can('See All Governorates')
                                <li class="nav-item {{ $activePage == 'Governorates' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.governorates.index') }}">
                                        <span class="material-icons">
                                            travel_explore
                                        </span>
                                        <span>{{ __('admin/master.Governorates') }}</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Cities --}}
                            @can('See All Cities')
                                <li class="nav-item {{ $activePage == 'Cities' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.cities.index') }}">
                                        <span class="material-icons">
                                            location_city
                                        </span>
                                        <span>{{ __('admin/master.Cities') }}</span>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>


        </ul>
    </div>
</div>

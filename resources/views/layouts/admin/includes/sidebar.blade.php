<div class="sidebar" data-color="red" data-background-color="white"
    data-image="{{ asset('assets/img/logos/smart-tools-logo-only-400.png') }}">
    <div class="logo">
        <a href="https://creative-tim.com/" class="simple-text logo-normal">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" alt="" width="50px">
            {{ 'Smart Tools' }}
        </a>
    </div>

    <div class="sidebar-wrapper">

        {{-- Nav Menu for mobile --}}
        <ul class="nav navbar-nav nav-mobile-menu">

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
                            src="{{ asset('storage/images/profiles/cropped200/' . auth()->user()->profile_photo_path) }}"
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
                                <a class="nav-link"
                                    href="{{ route('admin.products.index') }}">
                                    <span class="material-icons">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10zm0-2a8 8 0 1 0 0-16a8 8 0 0 0 0 16zm1.334-8a1.5 1.5 0 0 0 0-3H10.5v3h2.834zm0-5a3.5 3.5 0 0 1 0 7H10.5v3h-2V7h4.834z" />
                                        </svg>
                                    </span>
                                    <span>{{ __('admin/master.All Products') }} </span>
                                </a>
                            </li>

                            {{-- Add Product --}}
                            <li class="nav-item {{ $activePage == 'Add Product' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.products.create') }}">
                                    <span class="material-icons">
                                        control_point
                                    </span>
                                    <span>{{ __('admin/master.Add Product') }} </span>
                                </a>
                            </li>

                            {{-- Soft Deleted Users --}}
                            <li class="nav-item {{ $activePage == 'Deleted Products' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.products.softDeletedProducts') }}">
                                    <span class="material-icons rtl:ml-2 ltr:mr-2">
                                        auto_delete
                                    </span>
                                    <span>{{ __('admin/master.Soft Deleted Products') }} </span>
                                </a>
                            </li>

                            {{-- Brands --}}
                            <li class="nav-item {{ $activePage == 'Brands' ? ' active' : '' }}">
                                <a class="nav-link"
                                    href="{{ route('admin.brands.index') }}">
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
                                <a class="nav-link"
                                    href="{{ route('admin.supercategories.index') }}">
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
                                <a class="nav-link"
                                    href="{{ route('admin.categories.index') }}">
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
                                <a class="nav-link"
                                    href="{{ route('admin.subcategories.index') }}">
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

                            {{-- Soft Deleted Users --}}
                            @can('Force Delete User')
                                <li class="nav-item {{ $activePage == 'Deleted Users' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.users.softDeletedUsers') }}">
                                        <span class="material-icons rtl:ml-2 ltr:mr-2">
                                            person_off
                                        </span>
                                        <span>{{ __('admin/master.Soft Deleted Users') }} </span>
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


        </ul>
    </div>
</div>

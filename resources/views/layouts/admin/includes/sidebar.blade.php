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

            {{-- Notifications --}}
            <li class="nav-item dropdown">
                <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">notifications</i>
                    <span class="notification">5</span>
                    <p class="d-lg-none d-md-block">
                        Some Actions
                    </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Mike John responded to your email</a>
                </div>
            </li>

            {{-- Profile --}}
            <li class="nav-item dropdown">
                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    @if (auth()->user()->profile_photo_path)
                        <img class="h-10 w-10 rounded-full"
                            src="{{ asset('storage/images/profiles/cropped200/' . auth()->user()->profile_photo_path) }}"
                            alt="{{ auth()->user()->f_name . ' ' . auth()->user()->l_name . 'profile image' }}">
                    @else
                        <i class="fa-regular fa-user"></i>
                    @endif
                    <span class="ltr:ml-2 rtl:mr-2">{{ auth()->user()->f_name }} <b class="caret"></b></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                    <a class="ml-10 dropdown-item"
                        href="{{ route('admin.dashboard') }}">{{ __('admin/master.Profile') }}</a>
                    <a class="ml-10 dropdown-item" href="#">{{ __('admin/master.Settings') }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="ml-10 dropdown-item" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('admin/master.Log out') }}</a>
                </div>
            </li>
            <div class="dropdown-divider border-gray-300"></div>
        </ul>

        <ul class="nav">

            {{-- Dashboard --}}
            @can('See Dashboard')
                <li class="nav-item {{ $activeSection == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>{{ __('admin/master.dashboard') }}</span>
                    </a>
                </li>
            @endcan

            {{-- Users --}}
            @can('See All Users')
                <li class="nav-item {{ $activeSection == 'Users' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#users"
                        aria-expanded="{{ $activeSection == 'Users' ? 'true' : 'false' }}">
                        <i class="fa-solid fa-user"></i>
                        <span>{{ __('admin/master.users') }}
                            <b class="caret"></b>
                        </span>
                    </a>

                    <div class="collapse {{ $activeSection == 'Users' ? ' show' : '' }}" id="users">
                        <ul class="nav">

                            {{-- See All Users --}}
                            <li class="nav-item {{ $activePage == 'All Users' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">
                                    <i class="fa-solid fa-user-group"></i>
                                    <span>{{ __('admin/master.All Users') }}
                                    </span> </a>
                            </li>

                            {{-- Add New User --}}
                            @can('Add New User')
                                <li class="nav-item {{ $activePage == 'Add User' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.users.create') }}">
                                        <i class="fa-solid fa-user-plus"></i>
                                        <span>{{ __('admin/master.add user') }}
                                        </span> </a>
                                </li>
                            @endcan

                            {{-- Soft Deleted Users --}}
                            @can('Force Delete User')
                                <li class="nav-item {{ $activePage == 'Deleted Users' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.users.softDeletedUsers') }}">
                                        <i class="fa-solid fa-users-slash"></i>
                                        <span>{{ __('admin/master.Soft Deleted Users') }}
                                        </span> </a>
                                </li>
                            @endcan

                            {{-- Soft Deleted Users --}}
                            @can('See All Roles')
                                <li class="nav-item {{ $activePage == 'Roles Management' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                        <i class="fa-solid fa-key"></i>
                                        <span>{{ __('admin/master.Roles Management') }}
                                        </span> </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </li>
            @endcan


        </ul>
    </div>
</div>

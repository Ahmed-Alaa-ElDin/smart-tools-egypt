<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top z-40">
    <div class="container-fluid">

        {{-- Title --}}
        <div class="navbar-wrapper">
            <span class="navbar-brand">{{ $titlePage }}</span>
        </div>

        {{-- Toggle Button --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>


        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">

                {{-- Notification Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="material-icons">
                            notifications
                        </span>
                        <span class="notification">5</span>
                        <p class="d-lg-none d-md-block">
                            {{ __('Some Actions') }}
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">{{ __('Mike John responded to your email') }}</a>
                    </div>
                </li>

                {{-- Lang. DropDown --}}
                <li class="nav-item lang dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ LaravelLocalization::getCurrentLocale() }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        @foreach (LaravelLocalization::getSupportedLocales() as $lg => $lang)
                            <a class="dropdown-item"
                                href="{{ LaravelLocalization::getLocalizedURL($lg) }}">{{ $lang['native'] }}</a>
                        @endforeach
                    </div>
                </li>

                {{-- Profile Dropdown --}}
                <li class="nav-item dropdown min-w-max cursor-pointer">
                    <a class="nav-link flex flex-row flex-nowrap items-center gap-x-4 py-1" id="navbarDropdownProfile"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if (auth()->user()->profile_photo_path)
                            <img class="h-10 w-10 rounded-full"
                                src="{{ asset('storage/images/profiles/cropped100/' . auth()->user()->profile_photo_path) }}"
                                alt="{{ auth()->user()->f_name . ' ' . auth()->user()->l_name . 'profile image' }}">
                        @else
                            <span class="material-icons">
                                person
                            </span>
                        @endif
                        <span class="font-bold">{{ auth()->user()->f_name }}</span>
                        <p class="d-lg-none d-md-block">
                            {{ __('admin/master.Account') }}
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item"
                            href="{{ route('admin.dashboard') }}">{{ __('admin/master.Profile') }}</a>
                        <a class="dropdown-item" href="#">{{ __('admin/master.Settings') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('admin/master.Log out') }}</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

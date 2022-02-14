<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="#">{{ $titlePage }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="material-icons">dashboard</i>
                        <p class="d-lg-none d-md-block">
                            {{ __('Stats') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">notifications</i>
                        <span class="notification">5</span>
                        <p class="d-lg-none d-md-block">
                            {{ __('Some Actions') }}
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">{{ __('Mike John responded to your email') }}</a>
                    </div>
                </li>
                <li class="nav-item lang dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ LaravelLocalization::getCurrentLocale() }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        @foreach (LaravelLocalization::getSupportedLocales() as $lg => $lang)
                            <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($lg) }}">{{ $lang["native"] }}</a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">person</i>
                        <p class="d-lg-none d-md-block">
                            {{ __('Account') }}
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('admin/master.Profile') }}</a>
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

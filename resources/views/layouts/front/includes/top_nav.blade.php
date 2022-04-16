<div class="top-nav bg-white border-b z-50">
    <div class="container">
        <div class="flex items-center">

            {{-- Visible in mobile view --}}
            <div class="lg:col-7 col">
                <ul class="flex justify-between">
                    {{-- Lang. DropDown : Start --}}
                    <li class="btn btn-xs bg-primary p-0 rounded  font-bold nav-item lang dropdown">
                        <a class="nav-link focus:text-white hover:text-white text-white" href="#"
                            id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            {{ LaravelLocalization::getCurrentLocale() }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right z-50" aria-labelledby="navbarDropdownMenuLink">
                            @foreach (LaravelLocalization::getSupportedLocales() as $lg => $lang)
                                <a class="dropdown-item"
                                    href="{{ LaravelLocalization::getLocalizedURL($lg) }}">{{ $lang['native'] }}</a>
                            @endforeach
                        </div>
                    </li>
                    {{-- Lang. DropDown : End --}}
                </ul>
            </div>

            {{-- Invisible in mobile view --}}
            <div class="col-5 hidden lg:block">
                <ul class="list-inline mb-0 h-100 flex justify-end items-center text-sm">
                    <li
                        class="list-inline-item ltr:mr-3 rtl:ml-3 ltr:border-r ltr:border-l-0 rtl:border-l rtl:border-r-0 ltr:pr-3 ltr:pl-0 rtl:pl-3 rtl:pr-0">
                        <a href="tel:+01 112 352 566" class="text-reset d-inline-block opacity-60 py-2">
                            <i class="la la-phone"></i>
                            <span>Help line</span>
                            <span>+01 112 352 566</span>
                        </a>
                    </li>
                    <li
                        class="list-inline-item ltr:mr-3 rtl:ml-3 ltr:border-r ltr:border-l-0 rtl:border-l rtl:border-r-0 ltr:pr-3 ltr:pl-0 rtl:pl-3 rtl:pr-0">
                        <a href="https://demo.activeitzone.com/ecommerce/users/login"
                            class="text-reset d-inline-block opacity-60 py-2">تسجيل الدخول</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://demo.activeitzone.com/ecommerce/users/registration"
                            class="text-reset d-inline-block opacity-60 py-2">تسجيل</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<footer class="py-7 bg-secondary text-white">
    <div class="container">
        {{-- All Columns : Start --}}
        <div class="grid grid-cols-12 items-center gap-7 my-6 text-center ltr:lg:text-left rtl:lg:text-right">
            {{-- Column 1 : Customer Service :: Start --}}
            <div class="col-span-12 lg:col-span-4">
                <h3 class="text-lg font-bold mb-3">
                    {{ __('front/homePage.Customer Service') }}
                </h3>

                <ul class="list-inline flex flex-col justify-start items-center lg:items-start gap-2">
                    {{-- Login :: Start --}}
                    <li class="list-inline-item m-0">
                        @if (!auth()->check())
                            <a href="{{ route('login') }}" class="hover:text-white">
                                {{ __('front/homePage.Login') }}
                            </a>
                        @else
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="hover:text-white">
                                    {{ __('front/homePage.Logout') }}
                                </button>
                            </form>
                        @endif
                    </li>
                    {{-- Login :: End --}}

                    {{-- Track Orders :: Start --}}
                    @if (auth()->check())
                        <li class="list-inline-item m-0">
                            <a href="{{ route('front.orders.index') }}" class="hover:text-white">
                                {{ __('front/homePage.Track Orders') }}
                            </a>
                        </li>
                    @else
                        <li class="list-inline-item m-0">
                            <a href="{{ route('login') }}" class="hover:text-white">
                                {{ __('front/homePage.Track Orders') }}
                            </a>
                        </li>
                    @endif
                    {{-- Track Orders :: End --}}

                    {{-- Request a Quote :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Request a Quote') }}
                        </a>
                    </li>
                    {{-- Request a Quote :: End --}}

                    {{-- Be the First to Know Our Offers :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Be the First to Know Our Offers') }}
                        </a>
                    </li>
                    {{-- Be the First to Know Our Offers :: End --}}
                </ul>
            </div>
            {{-- Column 1 : Customer Service :: End --}}

            {{-- Column 2 : Our Services :: Start --}}
            <div class="col-span-12 lg:col-span-4">
                <h3 class="text-lg font-bold mb-3">
                    {{ __('front/homePage.Our Services') }}
                </h3>

                <ul class="list-inline flex flex-col justify-start items-center lg:items-start gap-2">
                    {{-- Delivery :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Delivery') }}
                        </a>
                    </li>
                    {{-- Delivery :: End --}}

                    {{-- Returns & Exchanges :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Returns & Exchanges') }}
                        </a>
                    </li>
                    {{-- Returns & Exchanges :: End --}}

                    {{-- Privacy Policy :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Privacy Policy') }}
                        </a>
                    </li>
                    {{-- Privacy Policy :: End --}}

                    {{-- Sell With Us :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Sell With Us') }}
                        </a>
                    </li>
                    {{-- Sell With Us :: End --}}
                </ul>
            </div>
            {{-- Column 2 : Our Services :: End --}}

            {{-- Column 3 : About Us :: Start --}}
            <div class="col-span-12 lg:col-span-4">
                <h3 class="text-lg font-bold mb-3">
                    {{ __('front/homePage.About Us') }}
                </h3>

                <ul class="list-inline flex flex-col justify-start items-center lg:items-start gap-2">
                    {{-- Contact Us :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Contact Us') }}
                        </a>
                    </li>
                    {{-- Contact Us :: End --}}

                    {{-- Customer Reviews :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Customer Reviews') }}
                        </a>
                    </li>
                    {{-- Customer Reviews :: End --}}

                    {{-- Call Us :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Call Us') }}
                        </a>
                    </li>
                    {{-- Call Us :: End --}}

                    {{-- Our Branches :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('login') }}" class="hover:text-white">
                            {{ __('front/homePage.Our Branches') }}
                        </a>
                    </li>
                    {{-- Our Branches :: End --}}
                </ul>
            </div>
            {{-- Column 3 : About Us :: End --}}
        </div>
        {{-- All Columns : End --}}


        <div class="grid grid-cols-12 items-center justify-center gap-3">
            {{-- Copyright :: Start --}}
            <div class="col-span-12">
                <div class="text-center text-sm font-bold">
                    {{ __('front/homePage.All Rights Reserved to Smart Tools Egypt', ['date' => date('Y')]) }}
                </div>
            </div>
            {{-- Copyright :: End --}}

            {{-- Social Media :: Start --}}
            <div class="col-span-12">
                <ul class="list-inline flex justify-center items-center gap-3">
                    <li class="list-inline-item m-0">
                        <a href="https://www.facebook.com/SmartToolsEgypt" target="_blank"
                            class="facebook flex bg-facebook w-9 h-9 text-xl rounded-circle items-center justify-center transition-all hover:bg-white hover:text-facebook focus:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z" />
                            </svg>
                        </a>
                    </li>
                    <li class="list-inline-item m-0">
                        <a href="#"
                            class="youtube text-xl flex bg-youtube w-9 h-9 rounded-circle items-center justify-center transition-all hover:bg-white hover:text-youtube focus:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <g fill="none">
                                    <g clip-path="url(#svgIDa)">
                                        <path fill="currentColor"
                                            d="M23.5 6.507a2.786 2.786 0 0 0-.766-1.27a3.05 3.05 0 0 0-1.338-.742C19.518 4 11.994 4 11.994 4a76.624 76.624 0 0 0-9.39.47a3.16 3.16 0 0 0-1.338.76c-.37.356-.638.795-.778 1.276A29.09 29.09 0 0 0 0 12c-.012 1.841.151 3.68.488 5.494c.137.479.404.916.775 1.269c.371.353.833.608 1.341.743c1.903.494 9.39.494 9.39.494a76.8 76.8 0 0 0 9.402-.47a3.05 3.05 0 0 0 1.338-.742a2.78 2.78 0 0 0 .765-1.27A28.38 28.38 0 0 0 24 12.023a26.579 26.579 0 0 0-.5-5.517ZM9.602 15.424V8.577l6.26 3.424l-6.26 3.423Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="svgIDa">
                                            <path fill="#fff" d="M0 0h24v24H0z" />
                                        </clipPath>
                                    </defs>
                                </g>
                            </svg> </a>
                    </li>
                    <li class="list-inline-item m-0">
                        <a href="https://wa.me/+2{{ config('settings.whatsapp_number') }}" target="_blank"
                            class="whats-app text-xl flex bg-whatsapp w-9 h-9 rounded-circle items-center justify-center transition-all hover:bg-white hover:text-[#25d366] focus:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024">
                                <path fill="currentColor"
                                    d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" />
                                <path fill="currentColor"
                                    d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            {{-- Social Media :: End --}}
        </div>
    </div>
</footer>

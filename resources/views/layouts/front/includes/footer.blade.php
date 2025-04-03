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
                        <button data-modal-target="first-to-know-modal" data-modal-toggle="first-to-know-modal"
                            data-modal-placement="center-center" class="hover:text-white">
                            {{ __('front/homePage.Be the First to Know Our Offers') }}
                        </button>
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
                        <a href="{{ route('front.policies.delivery') }}" class="hover:text-white">
                            {{ __('front/homePage.Delivery') }}
                        </a>
                    </li>
                    {{-- Delivery :: End --}}

                    {{-- Returns & Exchanges :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('front.policies.return-and-exchange') }}" class="hover:text-white">
                            {{ __('front/homePage.Returns & Exchanges') }}
                        </a>
                    </li>
                    {{-- Returns & Exchanges :: End --}}

                    {{-- Privacy Policy :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('front.policies.privacy') }}" class="hover:text-white">
                            {{ __('front/homePage.Privacy Policy') }}
                        </a>
                    </li>
                    {{-- Privacy Policy :: End --}}

                    {{-- Sell With Us :: Start --}}
                    <li class="list-inline-item m-0">
                        <button data-modal-target="sell-with-us-modal" data-modal-toggle="sell-with-us-modal"
                            data-modal-placement="center-center" class="hover:text-white">
                            {{ __('front/homePage.Sell With Us') }}
                        </button>
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
                        <button data-modal-target="contact-us-modal" data-modal-toggle="contact-us-modal"
                            data-modal-placement="center-center" class="hover:text-white">
                            {{ __('front/homePage.Contact Us') }}
                        </button>
                    </li>
                    {{-- Contact Us :: End --}}

                    {{-- Customer Reviews :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="https://bit.ly/ReviewsSTE" target="_blank" class="hover:text-white">
                            {{ __('front/homePage.Customer Reviews') }}
                        </a>
                    </li>
                    {{-- Customer Reviews :: End --}}

                    {{-- Call Us :: Start --}}
                    <li class="list-inline-item m-0">
                        <button data-modal-target="call-us-modal" data-modal-toggle="call-us-modal"
                            data-modal-placement="center-center" class="hover:text-white">
                            {{ __('front/homePage.Call Us') }}
                        </button>
                    </li>
                    {{-- Call Us :: End --}}

                    {{-- Our Branches :: Start --}}
                    <li class="list-inline-item m-0">
                        <a href="{{ route('front.about-us.branches') }}" class="hover:text-white">
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
                    {{-- Facebook :: Start --}}
                    @if (config('settings.facebook_page_name'))
                        <li class="list-inline-item m-0">
                            <a href="https://www.facebook.com/{{ config('settings.facebook_page_name') }}"
                                target="_blank"
                                class="facebook flex bg-facebook w-9 h-9 text-xl rounded-circle items-center justify-center transition-all hover:bg-white hover:text-facebook focus:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                    height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z" />
                                </svg>
                            </a>
                        </li>
                    @endif
                    {{-- Facebook :: End --}}

                    {{-- Youtube :: Start --}}
                    @if (config('settings.youtube_channel_name'))
                        <li class="list-inline-item m-0">
                            <a href="https://www.youtube.com/{{ config('settings.youtube_channel_name') }}"
                                target="_blank"
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
                                </svg>
                            </a>
                        </li>
                    @endif
                    {{-- Youtube :: End --}}

                    {{-- Whatsapp :: Start --}}
                    @if (config('settings.whatsapp_number'))
                        <li class="list-inline-item m-0">
                            <a href="https://wa.me/+2{{ config('settings.whatsapp_number') }}" target="_blank"
                                class="whats-app text-xl flex bg-whatsapp w-9 h-9 rounded-circle items-center justify-center transition-all hover:bg-white hover:text-[#25d366] focus:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 1024 1024">
                                    <path fill="currentColor"
                                        d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" />
                                    <path fill="currentColor"
                                        d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                                </svg>
                            </a>
                        </li>
                    @endif
                    {{-- Whatsapp :: End --}}

                    {{-- TikTok :: Start --}}
                    @if (config('settings.tiktok_page_name'))
                        <li>
                            <a href="https://www.tiktok.com/{{ config('settings.tiktok_page_name') }}" target="_blank"
                                class="tiktok text-xl flex bg-tiktok w-9 h-9 rounded-circle items-center justify-center transition-all hover:bg-white hover:text-tiktok focus:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M16.708.027C18.453 0 20.188.016 21.921 0c.105 2.041.839 4.12 2.333 5.563c1.491 1.479 3.6 2.156 5.652 2.385v5.369c-1.923-.063-3.855-.463-5.6-1.291c-.76-.344-1.468-.787-2.161-1.24c-.009 3.896.016 7.787-.025 11.667c-.104 1.864-.719 3.719-1.803 5.255c-1.744 2.557-4.771 4.224-7.88 4.276c-1.907.109-3.812-.411-5.437-1.369C4.307 29.027 2.412 26.12 2.136 23a22 22 0 0 1-.016-1.984c.24-2.537 1.495-4.964 3.443-6.615c2.208-1.923 5.301-2.839 8.197-2.297c.027 1.975-.052 3.948-.052 5.923c-1.323-.428-2.869-.308-4.025.495a4.62 4.62 0 0 0-1.819 2.333c-.276.676-.197 1.427-.181 2.145c.317 2.188 2.421 4.027 4.667 3.828c1.489-.016 2.916-.88 3.692-2.145c.251-.443.532-.896.547-1.417c.131-2.385.079-4.76.095-7.145c.011-5.375-.016-10.735.025-16.093z" />
                                </svg>
                            </a>
                        </li>
                    @endif
                    {{-- TikTok :: End --}}

                    {{-- Instagram :: Start --}}
                    @if (config('settings.instagram_page_name'))
                        <li>
                            <a href="https://www.instagram.com/{{ config('settings.instagram_page_name') }}"
                                target="_blank"
                                class="instagram text-xl flex bg-instagram w-9 h-9 rounded-circle items-center justify-center transition-all hover:bg-white hover:text-instagram focus:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M16 0c-4.349 0-4.891.021-6.593.093c-1.709.084-2.865.349-3.885.745a7.85 7.85 0 0 0-2.833 1.849A7.8 7.8 0 0 0 .84 5.52C.444 6.54.179 7.696.095 9.405c-.077 1.703-.093 2.244-.093 6.593s.021 4.891.093 6.593c.084 1.704.349 2.865.745 3.885a7.85 7.85 0 0 0 1.849 2.833a7.8 7.8 0 0 0 2.833 1.849c1.02.391 2.181.661 3.885.745c1.703.077 2.244.093 6.593.093s4.891-.021 6.593-.093c1.704-.084 2.865-.355 3.885-.745a7.85 7.85 0 0 0 2.833-1.849a7.7 7.7 0 0 0 1.849-2.833c.391-1.02.661-2.181.745-3.885c.077-1.703.093-2.244.093-6.593s-.021-4.891-.093-6.593c-.084-1.704-.355-2.871-.745-3.885a7.85 7.85 0 0 0-1.849-2.833A7.7 7.7 0 0 0 26.478.838c-1.02-.396-2.181-.661-3.885-.745C20.89.016 20.349 0 16 0m0 2.88c4.271 0 4.781.021 6.469.093c1.557.073 2.405.333 2.968.553a5 5 0 0 1 1.844 1.197a4.9 4.9 0 0 1 1.192 1.839c.22.563.48 1.411.553 2.968c.072 1.688.093 2.199.093 6.469s-.021 4.781-.099 6.469c-.084 1.557-.344 2.405-.563 2.968c-.303.751-.641 1.276-1.199 1.844a5.05 5.05 0 0 1-1.844 1.192c-.556.22-1.416.48-2.979.553c-1.697.072-2.197.093-6.479.093s-4.781-.021-6.48-.099c-1.557-.084-2.416-.344-2.979-.563c-.76-.303-1.281-.641-1.839-1.199c-.563-.563-.921-1.099-1.197-1.844c-.224-.556-.48-1.416-.563-2.979c-.057-1.677-.084-2.197-.084-6.459c0-4.26.027-4.781.084-6.479c.083-1.563.339-2.421.563-2.979c.276-.761.635-1.281 1.197-1.844c.557-.557 1.079-.917 1.839-1.199c.563-.219 1.401-.479 2.964-.557c1.697-.061 2.197-.083 6.473-.083zm0 4.907A8.21 8.21 0 0 0 7.787 16A8.21 8.21 0 0 0 16 24.213A8.21 8.21 0 0 0 24.213 16A8.21 8.21 0 0 0 16 7.787m0 13.546c-2.948 0-5.333-2.385-5.333-5.333s2.385-5.333 5.333-5.333s5.333 2.385 5.333 5.333s-2.385 5.333-5.333 5.333M26.464 7.459a1.923 1.923 0 0 1-1.923 1.921a1.919 1.919 0 1 1 0-3.838c1.057 0 1.923.86 1.923 1.917" />
                                </svg>
                            </a>
                        </li>
                    @endif
                    {{-- Instagram :: End --}}
                </ul>
            </div>
            {{-- Social Media :: End --}}
        </div>
    </div>
</footer>

<!-- Modals :: Start -->
<!-- Contact Us Modal :: Start -->
<div id="contact-us-modal" tabindex="-1" aria-hidden="true" data-modal-placement="center-center"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('front/homePage.Contact Us') }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="contact-us-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4">
                <ul class="my-4 space-y-3">
                    {{-- Messenger :: Start --}}
                    @if (config('settings.facebook_page_name'))
                        <li>
                            <a href="https://m.me/SmartToolsEgypt" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-messenger hover:bg-messengerHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M0 15.521C0 6.599 6.984 0 16 0s16 6.599 16 15.521c0 8.917-6.984 15.521-16 15.521c-1.615 0-3.172-.214-4.625-.615a1.27 1.27 0 0 0-.854.068l-3.188 1.401a1.282 1.282 0 0 1-1.802-1.135l-.094-2.854a1.28 1.28 0 0 0-.422-.906A15.2 15.2 0 0 1-.001 15.522zm11.094-2.922l-4.693 7.469c-.469.703.427 1.521 1.094 1l5.052-3.828a.944.944 0 0 1 1.161 0l3.729 2.802a2.41 2.41 0 0 0 3.469-.641l4.693-7.469c.469-.703-.427-1.505-1.094-1l-5.052 3.828a.92.92 0 0 1-1.146 0l-3.734-2.802a2.4 2.4 0 0 0-3.479.641" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.Messenger') }}</span>
                            </a>
                        </li>
                    @endif

                    {{-- Whatsapp :: Start --}}
                    @if (config('settings.whatsapp_number'))
                        <li>
                            <a href="https://wa.me/{{ config('settings.whatsapp_number') }}" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-whatsapp hover:bg-whatsappHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 1024 1024">
                                    <path fill="currentColor"
                                        d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" />
                                    <path fill="currentColor"
                                        d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.WhatsApp') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Contact Us Modal :: End --}}

{{-- Call Us Modal :: Start --}}
<div id="call-us-modal" tabindex="-1" aria-hidden="true" data-modal-placement="center-center"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('front/homePage.Call Us') }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="call-us-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4">
                <ul class="my-4 space-y-3">
                    {{-- Facebook :: Start --}}
                    @if (config('settings.facebook_page_name'))
                        <li>
                            <a href="https://m.me/{{ config('settings.facebook_page_name') }}" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-messenger hover:bg-messengerHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M13.397 20.997v-8.196h2.765l.411-3.209h-3.176V7.548c0-.926.258-1.56 1.587-1.56h1.684V3.127A22.336 22.336 0 0 0 14.201 3c-2.444 0-4.122 1.492-4.122 4.231v2.355H7.332v3.209h2.753v8.202h3.312z" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.Facebook') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- Facebook :: End --}}

                    {{-- Whatsapp :: Start --}}
                    @if (config('settings.whatsapp_number'))
                        <li>
                            <a href="https://wa.me/{{ config('settings.whatsapp_number') }}" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-whatsapp hover:bg-whatsappHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 1024 1024">
                                    <path fill="currentColor"
                                        d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" />
                                    <path fill="currentColor"
                                        d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.WhatsApp') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- Whatsapp :: End --}}

                    {{-- YouTube :: Start --}}
                    @if (config('settings.youtube_channel_name'))
                        <li>
                            <a href="https://www.youtube.com/c/{{ config('settings.youtube_channel_name') }}"
                                target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-youtube hover:bg-youtubeHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 24 24">
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
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.Youtube') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- YouTube :: End --}}

                    {{-- TikTok :: Start --}}
                    @if (config('settings.tiktok_page_name'))
                        <li>
                            <a href="https://www.tiktok.com/@{{ config('settings.tiktok_page_name') }}" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-tiktok hover:bg-tiktokHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M16.708.027C18.453 0 20.188.016 21.921 0c.105 2.041.839 4.12 2.333 5.563c1.491 1.479 3.6 2.156 5.652 2.385v5.369c-1.923-.063-3.855-.463-5.6-1.291c-.76-.344-1.468-.787-2.161-1.24c-.009 3.896.016 7.787-.025 11.667c-.104 1.864-.719 3.719-1.803 5.255c-1.744 2.557-4.771 4.224-7.88 4.276c-1.907.109-3.812-.411-5.437-1.369C4.307 29.027 2.412 26.12 2.136 23a22 22 0 0 1-.016-1.984c.24-2.537 1.495-4.964 3.443-6.615c2.208-1.923 5.301-2.839 8.197-2.297c.027 1.975-.052 3.948-.052 5.923c-1.323-.428-2.869-.308-4.025.495a4.62 4.62 0 0 0-1.819 2.333c-.276.676-.197 1.427-.181 2.145c.317 2.188 2.421 4.027 4.667 3.828c1.489-.016 2.916-.88 3.692-2.145c.251-.443.532-.896.547-1.417c.131-2.385.079-4.76.095-7.145c.011-5.375-.016-10.735.025-16.093z" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.Tiktok') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- TikTok :: End --}}

                    {{-- Instagram :: Start --}}
                    @if (config('settings.instagram_page_name'))
                        <li>
                            <a href="https://www.instagram.com/{{ config('settings.instagram_page_name') }}"
                                target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-instagram hover:bg-instagramHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M16 0c-4.349 0-4.891.021-6.593.093c-1.709.084-2.865.349-3.885.745a7.85 7.85 0 0 0-2.833 1.849A7.8 7.8 0 0 0 .84 5.52C.444 6.54.179 7.696.095 9.405c-.077 1.703-.093 2.244-.093 6.593s.021 4.891.093 6.593c.084 1.704.349 2.865.745 3.885a7.85 7.85 0 0 0 1.849 2.833a7.8 7.8 0 0 0 2.833 1.849c1.02.391 2.181.661 3.885.745c1.703.077 2.244.093 6.593.093s4.891-.021 6.593-.093c1.704-.084 2.865-.355 3.885-.745a7.85 7.85 0 0 0 2.833-1.849a7.7 7.7 0 0 0 1.849-2.833c.391-1.02.661-2.181.745-3.885c.077-1.703.093-2.244.093-6.593s-.021-4.891-.093-6.593c-.084-1.704-.355-2.871-.745-3.885a7.85 7.85 0 0 0-1.849-2.833A7.7 7.7 0 0 0 26.478.838c-1.02-.396-2.181-.661-3.885-.745C20.89.016 20.349 0 16 0m0 2.88c4.271 0 4.781.021 6.469.093c1.557.073 2.405.333 2.968.553a5 5 0 0 1 1.844 1.197a4.9 4.9 0 0 1 1.192 1.839c.22.563.48 1.411.553 2.968c.072 1.688.093 2.199.093 6.469s-.021 4.781-.099 6.469c-.084 1.557-.344 2.405-.563 2.968c-.303.751-.641 1.276-1.199 1.844a5.05 5.05 0 0 1-1.844 1.192c-.556.22-1.416.48-2.979.553c-1.697.072-2.197.093-6.479.093s-4.781-.021-6.48-.099c-1.557-.084-2.416-.344-2.979-.563c-.76-.303-1.281-.641-1.839-1.199c-.563-.563-.921-1.099-1.197-1.844c-.224-.556-.48-1.416-.563-2.979c-.057-1.677-.084-2.197-.084-6.459c0-4.26.027-4.781.084-6.479c.083-1.563.339-2.421.563-2.979c.276-.761.635-1.281 1.197-1.844c.557-.557 1.079-.917 1.839-1.199c.563-.219 1.401-.479 2.964-.557c1.697-.061 2.197-.083 6.473-.083zm0 4.907A8.21 8.21 0 0 0 7.787 16A8.21 8.21 0 0 0 16 24.213A8.21 8.21 0 0 0 24.213 16A8.21 8.21 0 0 0 16 7.787m0 13.546c-2.948 0-5.333-2.385-5.333-5.333s2.385-5.333 5.333-5.333s5.333 2.385 5.333 5.333s-2.385 5.333-5.333 5.333M26.464 7.459a1.923 1.923 0 0 1-1.923 1.921a1.919 1.919 0 1 1 0-3.838c1.057 0 1.923.86 1.923 1.917" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.Instagram') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- Instagram :: End --}}
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Call Us Modal :: End --}}

{{-- Be the First to Know Our Offers :: Start --}}
<div id="first-to-know-modal" tabindex="-1" aria-hidden="true" data-modal-placement="center-center"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('front/homePage.Be the First to Know Our Offers') }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="first-to-know-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4">
                <p>
                    {{ __('front/homePage.Join Our WhatsApp Group') }}
                </p>

                <ul class="my-4 space-y-3">
                    {{-- WhatsApp Group :: Start --}}
                    @if (config('settings.whatsapp_group_invitation_code'))
                        <li>
                            <a href="https://chat.whatsapp.com/{{ config('settings.whatsapp_group_invitation_code') }}"
                                target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-whatsapp hover:bg-whatsappHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 1024 1024">
                                    <path fill="currentColor"
                                        d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" />
                                    <path fill="currentColor"
                                        d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.WhatsApp Group') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- WhatsApp Group :: End --}}
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Be the First to Know Our Offers :: End --}}

{{-- Sell With Us :: Start --}}

{{-- Sell With Us :: End --}}
<div id="sell-with-us-modal" tabindex="-1" aria-hidden="true" data-modal-placement="center-center"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('front/homePage.Sell With Us') }}
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="sell-with-us-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4">
                <p>
                    {{ __('front/homePage.Sell With Us Text') }}
                </p>

                <ul class="my-4 space-y-3">
                    {{-- Whatsapp :: Start --}}
                    @if (config('settings.whatsapp_number'))
                        <li>
                            <a href="https://wa.me/{{ config('settings.whatsapp_number') }}" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-whatsapp hover:bg-whatsappHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 1024 1024">
                                    <path fill="currentColor"
                                        d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" />
                                    <path fill="currentColor"
                                        d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.WhatsApp') }}</span>
                            </a>
                        </li>
                    @endif

                    {{-- Messenger :: Start --}}
                    @if (config('settings.facebook_page_name'))
                        <li>
                            <a href="https://m.me/SmartToolsEgypt" target="_blank"
                                class="flex items-center p-3 text-base font-bold text-white rounded-lg bg-messenger hover:bg-messengerHover group hover:shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    viewBox="0 0 32 32">
                                    <path fill="currentColor"
                                        d="M0 15.521C0 6.599 6.984 0 16 0s16 6.599 16 15.521c0 8.917-6.984 15.521-16 15.521c-1.615 0-3.172-.214-4.625-.615a1.27 1.27 0 0 0-.854.068l-3.188 1.401a1.282 1.282 0 0 1-1.802-1.135l-.094-2.854a1.28 1.28 0 0 0-.422-.906A15.2 15.2 0 0 1-.001 15.522zm11.094-2.922l-4.693 7.469c-.469.703.427 1.521 1.094 1l5.052-3.828a.944.944 0 0 1 1.161 0l3.729 2.802a2.41 2.41 0 0 0 3.469-.641l4.693-7.469c.469-.703-.427-1.505-1.094-1l-5.052 3.828a.92.92 0 0 1-1.146 0l-3.734-2.802a2.4 2.4 0 0 0-3.479.641" />
                                </svg>
                                <span
                                    class="flex-1 rtl:mr-2 ltr:ml-2 whitespace-nowrap">{{ __('front/homePage.Messenger') }}</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Modals :: End --}}

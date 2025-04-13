<div class="relative">
    <div wire:loading.delay.longer class="absolute fixed w-full h-full backdrop-blur-sm z-40">
        <hr>
        <div class=" w-full flex gap-2 justify-center items-center p-4">
            <span class="text-primary text-xs font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                    class="animate-spin text-9xl" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50">
                    <path fill="currentColor"
                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                </svg>
            </span>
        </div>
    </div>

    <div class="px-4">

        @if (auth()->user()->valid_points > 0 || auth()->user()->balance > 0)
            <div class="flex gap-2 p-4 justify-around items-center">
                {{-- Pay with Points --}}
                @if (auth()->user()->valid_points > 0)
                    <div class="flex flex-col gap-2 justify-center items-center">
                        <h2 class="text-center font-bold">
                            {{ __('front/homePage.Use my points') }}
                        </h2>
                        <div class="flex items-center gap-3">
                            <label for="points"
                                class="select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('front/homePage.Use') }}</label>
                            <input type="number" dir="ltr" wire:model.live.blur="points"
                                class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('points') border-2 border-primary @enderror"
                                id="points" min="0" max="{{ auth()->user()->valid_points }}">
                            <span
                                class="select-none font-bold text-xs text-gray-700">{{ __('front/homePage.Equivalent to ') }}</span>
                            <span class="w-full select-none font-bold text-successDark">{{ $points_egp }}
                                {{ __('front/homePage.EGP') }}</span>
                        </div>

                        @error('points')
                            <span class="text-primary text-xs font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                @if (auth()->user()->balance > 0)
                    <div class="flex flex-col gap-2 justify-center items-center">
                        <h2 class="text-center font-bold">
                            {{ __('front/homePage.Use my balance') }}
                        </h2>
                        <div class="flex items-center gap-3">
                            <label for="balance"
                                class="select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('front/homePage.Use') }}</label>
                            <input type="number" dir="ltr" wire:model.live.blur="balance"
                                class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('balance') border-2 border-primary @enderror"
                                id="balance" step="0.1" min="0" max="{{ auth()->user()->balance }}">
                        </div>

                        @error('balance')
                            <span class="text-primary text-xs font-bold">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>

            <hr>
        @endif

        {{-- Payment Method :: Start --}}
        <div class="flex flex-col gap-4 p-4">
            <h2 class="col-span-2 text-center font-bold">
                {{ __('front/homePage.Payment Method') }}
            </h2>
            <div class="flex flex-wrap justify-around items-center gap-2">
                {{-- Cash on Delivery --}}
                <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 1) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(1)">
                    {{ __('front/homePage.Cash on delivery (COD)') }}
                </div>

                {{-- Credit Card --}}
                {{-- <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 2) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(2)">
                    {{ __('front/homePage.Credit / Debit Card') }}
                </div> --}}

                {{-- installment --}}
                {{-- <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 3) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(3)">
                    {{ __('front/homePage.Installment') }}
                </div> --}}

                {{-- Electronic Wallet --}}
                <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 4) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(4)">
                    {{ __('front/homePage.Electronic Wallet') }}
                </div>

                {{-- Flash --}}
                <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 5) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(5)">
                    {{ __('front/homePage.Flash') }}
                </div>
            </div>
        </div>
        {{-- Payment Method :: End --}}

        {{-- Payment Method Details :: Start --}}
        @if ($payment_method == 1)
            <hr>

            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="submit">
                    {{ __('front/homePage.Submit & Confirm Order') }}
                    &nbsp;
                    <span class="material-icons">
                        done_all
                    </span>
                </button>

                <a class="btn bg-primary max-w-max font-bold" href="{{ route('front.order.shipping') }}">
                    {{ __('front/homePage.Back to Shipping Details') }}
                    &nbsp;
                    <span class="material-icons">
                        local_shipping
                    </span>
                </a>
            </div>
        @elseif ($payment_method == 2)
            <hr>

            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="submit">
                    {{ __('front/homePage.Go to payment') }}
                    &nbsp;
                    <span class="material-icons">
                        credit_card
                    </span>
                </button>

                <a class="btn bg-primary max-w-max font-bold" href="{{ route('front.order.shipping') }}">
                    {{ __('front/homePage.Back to Shipping Details') }}
                    &nbsp;
                    <span class="material-icons">
                        local_shipping
                    </span>
                </a>
            </div>
        @elseif ($payment_method == 3)
            <hr>
            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="submit">
                    {{ __('front/homePage.Go to payment') }}
                    &nbsp;
                    <span class="material-icons">
                        credit_card
                    </span>
                </button>

                <a class="btn bg-primary max-w-max font-bold" href="{{ route('front.order.shipping') }}">
                    {{ __('front/homePage.Back to Shipping Details') }}
                    &nbsp;
                    <span class="material-icons">
                        local_shipping
                    </span>
                </a>
            </div>
        @elseif ($payment_method == 4)
            <hr>

            <div class="text-center p-4 font-bold text-lg">
                {!! __('front/homePage.Electronic Wallet Confirm', [
                    'icon' =>
                        '<a href="https://wa.me/+2' .
                        config('settings.whatsapp_number') .
                        '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1">
                                                                                        <span class="text-sm">' .
                        config('settings.whatsapp_number') .
                        '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                ]) !!}
            </div>

            <hr>

            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="submit">
                    {{ __('front/homePage.Submit & Confirm Order') }}
                    &nbsp;
                    <span class="material-icons">
                        account_balance_wallet
                    </span>
                </button>

                <a class="btn bg-primary max-w-max font-bold" href="{{ route('front.order.shipping') }}">
                    {{ __('front/homePage.Back to Shipping Details') }}
                    &nbsp;
                    <span class="material-icons">
                        local_shipping
                    </span>
                </a>
            </div>
        @elseif ($payment_method == 5)
            {{-- Flash --}}
            <hr>

            <div class="text-center p-4 font-bold text-lg">
                {!! __('front/homePage.Flash Message', [
                    'flash-icon' => '
                        <a href="https://play.google.com/store/apps/details?id=app.useflash.teller" target="_blank" class="bg-flashPrimary text-flashSecondary hover:bg-flashSecondary hover:text-flashPrimary inline-flex items-center justify-center gap-1 rounded-full overflow-hidden px-2 py-1 m-1">
                            <span class="text-sm">Flash</span>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1em" height="1em" viewBox="0 0 240 240">
                                <defs>
                                    <clipPath id="circleClip">
                                    <circle cx="120" cy="120" r="120" />
                                    </clipPath>
                                </defs>

                                <g clip-path="url(#circleClip)">
                                    <path d="M0 0 C79.2 0 158.4 0 240 0 C240 79.2 240 158.4 240 240 C160.8 240 81.6 240 0 240 C0 160.8 0 81.6 0 0 Z " fill="#2A2F7E"/>
                                    <path d="M0 0 C16.04665791 -0.09352802 32.09322712 -0.16402053 48.1400919 -0.20724869 C55.59129014 -0.22785566 63.04231881 -0.2559403 70.4934082 -0.30175781 C76.98937135 -0.34168304 83.48521166 -0.36744638 89.9812941 -0.37635398 C93.41954918 -0.3815592 96.85745292 -0.39375367 100.29559517 -0.42292023 C104.13796597 -0.45525019 107.97999608 -0.45595576 111.82250977 -0.45410156 C112.95683441 -0.46848267 114.09115906 -0.48286377 115.25985718 -0.49768066 C121.69550381 -0.46407367 125.90039117 -0.08925955 131 4 C135.1486961 9.37912257 134.40106804 15.7606955 134.34057617 22.24755859 C134.34101425 23.47738464 134.34145233 24.70721069 134.34190369 25.9743042 C134.34017595 29.33356483 134.32516624 32.69226737 134.30418181 36.05142927 C134.28538457 39.56679091 134.28366218 43.08216056 134.28010559 46.5975647 C134.27079404 53.24855482 134.24619243 59.8993964 134.21605712 66.55032122 C134.1824853 74.12466043 134.16602013 81.69900685 134.15097082 89.27340198 C134.11964198 104.84902057 134.06689895 120.4244911 134 136 C126.97091001 136.10147154 119.94200911 136.17149565 112.91235352 136.21972656 C110.52133119 136.23983418 108.13035799 136.2671243 105.73950195 136.30175781 C102.30147169 136.35031151 98.86409196 136.37297774 95.42578125 136.390625 C93.82395744 136.42159271 93.82395744 136.42159271 92.18977356 136.45318604 C87.81939058 136.45450556 84.78213587 136.3838535 80.82136536 134.44000244 C78.38921479 131.18175812 78.33136086 128.48181823 78.43237305 124.56933594 C78.43164291 123.82748047 78.43091278 123.085625 78.43016052 122.32128906 C78.43363757 119.8749011 78.47250028 117.43042928 78.51171875 114.984375 C78.52104656 113.28616374 78.52816282 111.58793901 78.53315735 109.88970947 C78.55222994 105.4243034 78.60133238 100.9597131 78.65667725 96.49462891 C78.70786446 91.9366357 78.73067022 87.37851041 78.75585938 82.8203125 C78.80945135 73.8798503 78.8947669 64.93999304 79 56 C77.6039151 56.01665459 77.6039151 56.01665459 76.17962646 56.03364563 C67.41015946 56.13498634 58.64081118 56.20994815 49.87089157 56.25906086 C45.36208449 56.28515929 40.85374995 56.32056122 36.34521484 56.37719727 C31.99448247 56.43150921 27.64420468 56.46141868 23.29315948 56.47438622 C21.63290499 56.48362767 19.97267549 56.50167443 18.31261444 56.52865028 C15.98748449 56.56493208 13.66423449 56.56994607 11.33886719 56.56762695 C10.6519429 56.58560333 9.96501862 56.60357971 9.25727844 56.62210083 C5.98270487 56.5867433 4.20893474 56.15515564 1.56573486 54.19230652 C-0.82923205 49.30931601 -0.4922591 44.720237 -0.390625 39.35546875 C-0.38496521 38.2305452 -0.37930542 37.10562164 -0.37347412 35.9466095 C-0.35111468 32.35980366 -0.30091667 28.77400304 -0.25 25.1875 C-0.22993455 22.75326965 -0.21168386 20.31902362 -0.1953125 17.88476562 C-0.15125123 11.92283511 -0.08434243 5.96148938 0 0 Z" fill="#D1EC74" transform="translate(53,52)" />
                                </g>
                            </svg>
                        </a>
                    ',
                    'whatsapp-icon' =>
                        '<a href="https://wa.me/+2' . config('settings.whatsapp_number') . '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1">
                            <span class="text-sm">' .
                                config('settings.whatsapp_number') .
                            '</span>
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024">
                                <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                            </svg>
                        </a>',
                ]) !!}
            </div>

            <hr>

            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="submit">
                    {{ __('front/homePage.Submit & Confirm Order') }}
                    &nbsp;
                    <span class="material-icons">
                        account_balance_wallet
                    </span>
                </button>

                <a class="btn bg-primary max-w-max font-bold" href="{{ route('front.order.shipping') }}">
                    {{ __('front/homePage.Back to Shipping Details') }}
                    &nbsp;
                    <span class="material-icons">
                        local_shipping
                    </span>
                </a>
            </div>
        @endif
        {{-- Payment Method Details :: End --}}
    </div>
</div>

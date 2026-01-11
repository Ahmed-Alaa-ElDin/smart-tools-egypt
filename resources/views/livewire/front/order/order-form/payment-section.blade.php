<div>
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="material-icons text-primary">account_balance_wallet</span>
            {{ __('front/homePage.Payment Method') }}
        </h3>
    </div>

    <div class="px-6 py-4">
        {{-- Points and Balance :: Start --}}
        @if (Auth::check() && (auth()->user()->valid_points > 0 || auth()->user()->balance > 0))
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100 animate-fadeIn">
                {{-- Pay with Points --}}
                @if (auth()->user()->valid_points > 0)
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="material-icons text-primary text-sm">stars</span>
                            <h4 class="font-bold text-gray-800 text-sm italic">
                                {{ __('front/homePage.Use my points') }}
                            </h4>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-grow">
                                <input type="number" dir="ltr" wire:model.live.blur="points"
                                    class="w-full h-10 px-4 rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm font-bold @error('points') border-red-500 @enderror"
                                    id="points" min="0" max="{{ auth()->user()->valid_points }}">
                            </div>
                            <div class="flex flex-col text-[10px] text-gray-400 font-bold whitespace-nowrap">
                                <span>{{ __('front/homePage.Equivalent to ') }}</span>
                                <span class="text-successDark text-sm">{{ $points_egp }}
                                    {{ __('front/homePage.EGP') }}</span>
                            </div>
                        </div>
                        @error('points')
                            <span class="text-[10px] text-red-500 font-bold px-1">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                {{-- Pay with Balance --}}
                @if (auth()->user()->balance > 0)
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="material-icons text-primary text-sm">account_balance_wallet</span>
                            <h4 class="font-bold text-gray-800 text-sm italic">
                                {{ __('front/homePage.Use my balance') }}
                            </h4>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-grow">
                                <input type="number" dir="ltr" wire:model.live.blur="balance"
                                    class="w-full h-10 px-4 rounded-xl border-gray-200 focus:border-primary focus:ring-primary text-sm font-bold @error('balance') border-red-500 @enderror"
                                    id="balance" step="0.1" min="0" max="{{ auth()->user()->balance }}">
                            </div>
                        </div>
                        @error('balance')
                            <span class="text-[10px] text-red-500 font-bold px-1">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
            </div>
            <hr class="mb-6 border-gray-100">
        @endif
        {{-- Points and Balance :: End --}}

        <div class="space-y-3">
            @foreach ($payment_methods as $method)
                <div wire:click="selectMethod({{ $method['id'] }})"
                    class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300 group {{ $selected_method_id == $method['id'] ? 'border-primary bg-red-50/30' : 'border-gray-100 hover:border-gray-200' }}">

                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors {{ $selected_method_id == $method['id'] ? 'bg-primary text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-gray-200' }}">
                        <span class="material-icons text-2xl">{{ $method['icon'] }}</span>
                    </div>

                    <div class="flex-grow">
                        <h4 class="font-bold text-gray-800 text-sm">
                            {{ $method['name'] }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $method['desc'] }}
                        </p>
                    </div>

                    @if ($selected_method_id == $method['id'])
                        <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center animate-fadeIn">
                            <span class="material-icons text-white text-xs font-bold">check</span>
                        </div>
                    @else
                        <div
                            class="w-6 h-6 rounded-full border-2 border-gray-100 group-hover:border-gray-200 transition-colors">
                        </div>
                    @endif
                </div>

                @if ($selected_method_id == $method['id'])
                    @if ($selected_method_id == \App\Enums\PaymentMethod::ElectronicWallet->value)
                        <div class="p-4 mt-2 bg-gray-50 rounded-2xl border border-gray-100 animate-fadeIn slide-in-top">
                            <div class="text-center font-bold text-sm text-gray-700 leading-relaxed">
                                {!! __('front/homePage.Electronic Wallet Confirm', [
                                    'icon' =>
                                        '<a href="https://wa.me/+2' .
                                        config('settings.whatsapp_number') .
                                        '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-0.5 mx-1 align-middle">
                                                                        <span class="text-xs">' .
                                        config('settings.whatsapp_number') .
                                        '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                                ]) !!}
                            </div>
                        </div>
                    @elseif ($selected_method_id == \App\Enums\PaymentMethod::Flash->value)
                        <div class="p-4 mt-2 bg-gray-50 rounded-2xl border border-gray-100 animate-fadeIn slide-in-top">
                            <div class="text-center font-bold text-sm text-gray-700 leading-relaxed">
                                {!! __('front/homePage.Flash Message', [
                                    'flash-icon' => '
                                                                        <a href="https://play.google.com/store/apps/details?id=app.useflash.teller" target="_blank" class="bg-flashPrimary text-flashSecondary hover:bg-flashSecondary hover:text-flashPrimary inline-flex items-center justify-center gap-1 rounded-full overflow-hidden px-2 py-0.5 mx-1 align-middle">
                                                                            <span class="text-xs">Flash</span>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1em" height="1em" viewBox="0 0 240 240" class="w-3 h-3">
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
                                                                        </a>',
                                    'whatsapp-icon' =>
                                        '<a href="https://wa.me/+2' .
                                        config('settings.whatsapp_number') .
                                        '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-0.5 mx-1 align-middle">
                                                                        <span class="text-xs">' .
                                        config('settings.whatsapp_number') .
                                        '</span>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024">
                                                                            <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" />
                                                                        </svg>
                                                                    </a>',
                                ]) !!}
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
</div>

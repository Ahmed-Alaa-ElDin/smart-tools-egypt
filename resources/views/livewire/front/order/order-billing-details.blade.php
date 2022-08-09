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

        @if (auth()->user()->points > 0 || auth()->user()->balance > 0)
            <div class="flex gap-2 p-4 justify-around items-center">
                {{-- Pay with Points --}}
                @if (auth()->user()->points > 0)
                    <div class="flex flex-col gap-2 justify-center items-center">
                        <h2 class="text-center font-bold">
                            {{ __('front/homePage.Use my points') }}
                        </h2>
                        <div class="flex items-center gap-3">
                            <label for="points"
                                class="select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('front/homePage.Use') }}</label>
                            <input type="number" dir="ltr" wire:model.lazy="points"
                                class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('points') border-2 border-primary @enderror"
                                id="points" min="0" max="{{ auth()->user()->points }}">
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
                            <input type="number" dir="ltr" wire:model.lazy="balance"
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
                <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 2) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(2)">
                    {{ __('front/homePage.Credit / Debit Card') }}
                </div>

                {{-- installment --}}
                <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 3) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(3)">
                    {{ __('front/homePage.Installment') }}
                </div>

                {{-- Vodafone Cash --}}
                <div class="select-none cursor-pointer text-sm hover:shadow-inner shadow rounded-xl py-2 px-3 @if ($payment_method == 4) bg-successDarker text-white shadow-inner font-bold @else bg-gray-100 @endif"
                    wire:click="payBy(4)">
                    {{ __('front/homePage.Vodafone Cash') }}
                </div>
            </div>
        </div>
        {{-- Payment Method :: End --}}

        {{-- Payment Method Details :: Start --}}
        @if ($payment_method == 1)
            <hr>

            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="confirm(1)">
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
                <button class="btn bg-success max-w-max font-bold" wire:click="confirm(2)">
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
                <button class="btn bg-success max-w-max font-bold" wire:click="confirm(3)">
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
                {!! __('front/homePage.Vodafone Cash Confirm', [
                    'icon' =>
                        '<a href="https://wa.me/+2' .
                        config('constants.constants.WHATSAPP_NUMBER') .
                        '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1">
                        <span class="text-sm">' .
                        config('constants.constants.WHATSAPP_NUMBER') .
                        '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                ]) !!}
            </div>

            <hr>

            <div class="flex gap-2 justify-around items-center p-4">
                <button class="btn bg-success max-w-max font-bold" wire:click="confirm(4)">
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

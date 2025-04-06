<div>
    <x-admin.waiting />

    @if ($customer)
        <div class="bg-gray-50 p-2 rounded-xl shadow">
            <div class="text-center mb-2 font-bold text-gray-900 select-none">
                {{ __('admin/ordersPages.Payment') }}
            </div>
            <div class="flex flex-wrap justify-around items-center gap-3">
                {{-- Coupon --}}
                <div class="flex flex-col justify-center items-center gap-0 bg-gray-200 rounded p-2">
                    <label for="coupon" class="m-0 p-2 text-xs font-bold text-gray-900 select-none">
                        {{ __('admin/ordersPages.Coupon') }}
                    </label>
                    @if (!$coupon_id)
                        <div class="flex justify-center items-center">
                            <input type="text" id="coupon"
                                placeholder="{{ __('admin/ordersPages.Enter Coupon Code') }}" dir="ltr"
                                wire:keydown.enter="couponCheck" wire:model.live='code'
                                class="text-sm rounded-l rtl:rounded-r rtl:rounded-l-none text-center border border-gray-300 focus:outline-0 focus:ring-0 focus:border-secondary">
                            <button wire:click="couponCheck"
                                class="px-3 py-2 bg-secondary text-white font-bold rounded-r rtl:rounded-l rtl:rounded-r-none border-2 border-secondary text-xs">
                                {{ __('admin/ordersPages.Apply') }}
                            </button>
                        </div>
                    @endif
                    @if ($message)
                        @if ($message['status'] == 1)
                            <div class="bg-white py-1 px-2 flex flex-col justify-center items-center gap-2 rounded-lg">
                                <div class="my-2 text-center font-bold text-xs text-successDark">
                                    {{ $message['message'] }}
                                </div>
                                <button class="px-3 py-2 bg-primary text-white font-bold rounded text-xs"
                                    wire:click='clearCoupon'>
                                    {{ __('admin/ordersPages.Cancel Coupon') }}
                                </button>
                            </div>
                        @else
                            <div class="my-2 text-center font-bold text-xs text-primary">
                                {{ $message['message'] }}
                            </div>
                        @endif

                    @endif

                </div>

                {{-- Wallet --}}
                @if ($customer->balance > 0)
                    <div class="flex flex-col justify-center items-center gap-0 bg-gray-200 rounded p-2">
                        <label for="wallet" class="m-0 p-2 text-xs font-bold text-gray-900 select-none">
                            {{ __('admin/ordersPages.Wallet') }}
                        </label>
                        <input type="number" id="wallet" min="0" max="{{ $customer->balance }}"
                            step="0.01" value="0" wire:model.live.debounce.500ms="wallet"
                            class="text-sm rounded text-center border-gray-300 focus:outline-0 focus:ring-secondary focus:border-gray-300">
                    </div>
                @endif

                {{-- Points --}}
                @if ($customer->validPoints > 0)
                    <div class="flex flex-col justify-center items-center gap-0 bg-gray-200 rounded p-2">
                        <label for="points" class="m-0 p-2 text-xs font-bold text-gray-900 select-none">
                            {{ __('admin/ordersPages.Points') }}
                        </label>
                        <input type="number" id="points" min="0" max="{{ $customer->validPoints }}"
                            step="1" value="0" wire:model.live.debounce.500ms="points"
                            class="text-sm rounded text-center border-gray-300 focus:outline-0 focus:ring-secondary focus:border-gray-300">
                    </div>
                @endif

                {{-- Payment Method --}}
                <div class="flex flex-col justify-center items-center gap-0 bg-gray-200 rounded p-2">
                    <label for="points" class="m-0 p-2 text-xs font-bold text-gray-900 select-none">
                        {{ __('admin/ordersPages.Payment Method') }}
                    </label>
                    <div class="flex flex-wrap justify-around items-center gap-2">
                        {{-- Cash on Delivery --}}
                        <div class="select-none cursor-pointer text-xs hover:shadow-inner shadow rounded-xl py-2 px-3
                    @if ($payment_method == 1) bg-secondary text-white shadow-inner font-bold @else bg-white @endif"
                            wire:click="$set('payment_method',1)">
                            {{ __('front/homePage.Cash on delivery (COD)') }}
                        </div>

                        {{-- Credit Card --}}
                        {{-- <div class="select-none cursor-pointer text-xs hover:shadow-inner shadow rounded-xl py-2 px-3
                    @if ($payment_method == 2) bg-secondary text-white shadow-inner font-bold @else bg-white @endif"
                            wire:click="$set('payment_method',2)">
                            {{ __('front/homePage.Credit / Debit Card') }}
                        </div> --}}

                        {{-- installment --}}
                        {{-- <div class="select-none cursor-pointer text-xs hover:shadow-inner shadow rounded-xl py-2 px-3
                    @if ($payment_method == 3) bg-secondary text-white shadow-inner font-bold @else bg-white @endif"
                            wire:click="$set('payment_method',3)">
                            {{ __('front/homePage.Installment') }}
                        </div> --}}

                        {{-- Vodafone Cash --}}
                        <div class="select-none cursor-pointer text-xs hover:shadow-inner shadow rounded-xl py-2 px-3
                    @if ($payment_method == 4) bg-secondary text-white shadow-inner font-bold @else bg-white @endif"
                            wire:click="$set('payment_method',4)">
                            {{ __('front/homePage.Vodafone Cash') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>

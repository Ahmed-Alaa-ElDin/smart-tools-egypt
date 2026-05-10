<div class="flex flex-col justify-center items-center gap-5">
    <x-admin.waiting />

    {{-- User Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-user-part', key('user-part'), [
            'customerId' => $this->customerId,
            'initialAddressId' => $this->default_address,
            'initialPhoneId' => $this->default_phone
        ])
    </div>
    {{-- User Part :: End --}}

    {{-- Products Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-products-part', key('products-part'), [
            'customerId' => $this->customerId,
            'initialProducts' => $this->items
        ])
    </div>
    {{-- Products Part :: End --}}

    {{-- Payment Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-payment-part', key('payment-part'), [
            'customerId' => $this->customerId,
            'initialCouponId' => $this->coupon_id,
            'initialWallet' => $this->wallet,
            'initialPoints' => $this->points,
            'initialPaymentMethod' => $this->payment_method
        ])
    </div>
    {{-- Payment Part :: End --}}

    {{-- Notes :: Start --}}
    <div class="w-full rounded-2xl shadow-sm border border-gray-200" style="background: linear-gradient(180deg, #fef9f0 0%, #fff 100%);">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
            <span class="material-icons text-lg" style="color: #d97706;">sticky_note_2</span>
            <h2 class="text-sm font-bold text-gray-800 m-0">
                {{ __('front/homePage.Notes') }}
            </h2>
        </div>
        <div class="p-4 flex flex-col gap-4">
            <textarea id="notes" rows="2" wire:model.live.blur="notes" dir="rtl"
                placeholder="{{ __('front/homePage.Please mention any note related to the order') }}"
                class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-gray-200 bg-white focus:outline-0 focus:ring-2 focus:ring-amber-200 focus:border-amber-300 transition-all duration-200 resize-none">
            </textarea>

            {{-- Allow open package --}}
            <div class="flex items-center gap-3 px-1">
                <label for="allowToOpenPackage" class="relative inline-flex items-center cursor-pointer select-none gap-3">
                    <input id="allowToOpenPackage" type="checkbox" wire:model.live="allowToOpenPackage"
                        class="w-5 h-5 rounded-md border-2 border-gray-300 text-amber-500 focus:ring-amber-300 focus:ring-offset-0 cursor-pointer transition-colors duration-200">
                    <span class="ltr:ml-2.5 rtl:mr-2.5 text-sm font-medium text-gray-700">
                        {{ __('admin/ordersPages.Allow to open package') }}
                    </span>
                </label>
            </div>
        </div>
    </div>
    {{-- Notes :: End --}}

    {{-- Edit Reason :: Start --}}
    <div class="w-full rounded-2xl shadow-sm border border-amber-200" style="background: linear-gradient(180deg, #fffbeb 0%, #fff 100%);">
        <div class="px-4 py-3 border-b border-amber-100 flex items-center gap-2">
            <span class="material-icons text-lg" style="color: #b45309;">edit_note</span>
            <h2 class="text-sm font-bold text-amber-900 m-0">
                {{ __('admin/ordersPages.Edit Reason') }}
            </h2>
        </div>
        <div class="p-4">
            <textarea id="edit_reason" rows="2" wire:model.live.blur="edit_reason" dir="rtl"
                placeholder="{{ __('admin/ordersPages.Please mention the reason for editing this order') }}"
                class="w-full py-2.5 px-3 rounded-xl text-center text-sm border-amber-200 bg-white focus:outline-0 focus:ring-2 focus:ring-amber-200 focus:border-amber-300 transition-all duration-200 resize-none">
            </textarea>
        </div>
    </div>
    {{-- Edit Reason :: End --}}

    {{-- Errors :: Start --}}
    @if ($errors->any())
        <div class="w-full rounded-xl p-4 flex items-start gap-3" style="background: linear-gradient(135deg, #fef2f2, #fee2e2);">
            <span class="material-icons text-red-500 mt-0.5">error_outline</span>
            <ul class="text-sm font-semibold text-red-700 m-0 p-0" style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li class="py-0.5">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Errors :: End --}}

    {{-- Buttons :: Start --}}
    <div class="flex flex-wrap items-center justify-center gap-3 w-full pt-2">
        <button wire:click="getOrderData(false)"
            class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-5 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
            style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
            <span class="material-icons text-lg">calculate</span>
            {{ __('admin/ordersPages.Calculate the order cost') }}
        </button>

        <button wire:click="getOrderData(true)"
            class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-5 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
            style="background: linear-gradient(135deg, #d97706, #b45309);">
            <span class="material-icons text-lg">save</span>
            {{ __('admin/ordersPages.Update Order') }}
        </button>

        <a href="{{ route('admin.orders.new-orders') }}"
            class="inline-flex items-center gap-2 font-bold rounded-xl px-5 py-2.5 text-sm border-2 border-gray-300 text-gray-600 bg-white transition-all duration-200 hover:bg-gray-50 hover:border-gray-400 hover:shadow-sm">
            <span class="material-icons text-lg">close</span>
            {{ __('admin/ordersPages.Cancel') }}
        </a>
    </div>
    {{-- Buttons :: End --}}

    {{-- Summary Part :: Start --}}
    <div id="displayOrderSummary" tabindex="-1" wire:ignore.self onclick="modal.hide()"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
        aria-modal="true" role="dialog">
        <div class="relative p-4 w-full max-w-2xl h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Modal header -->
                <div class="flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #92400e 0%, #b45309 100%);">
                    <h3 class="grow text-lg font-bold text-white m-0 flex items-center gap-2">
                        <span class="material-icons">receipt_long</span>
                        {{ __('admin/ordersPages.Order Summary') }}
                        <span class="text-sm font-normal text-amber-100">(#{{ $order->id }})</span>
                    </h3>
                    <button type="button" onclick="modal.hide()"
                        class="text-amber-100 bg-transparent hover:bg-white/10 hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-all duration-200">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap gap-3 justify-around items-start w-full">
                        {{-- Order Summary :: Start --}}
                        <div>
                            <div class="flex justify-between items-center gap-3 p-4">
                                <h3 class="h5 text-center font-bold m-0">
                                    {{ __('admin/ordersPages.Order Summary') }}
                                </h3>
                                <h4 class="text-sm font-bold">
                                    {{ trans_choice('admin/ordersPages.Product', $items_total_quantities, ['product' => $items_total_quantities]) }}
                                </h4>
                            </div>

                            <hr>

                            <div class="font-bold p-4 flex flex-col gap-3 justify-center items-center">
                                {{-- Original Total (for reference) --}}
                                @if($original_total > 0)
                                    <div class="w-100 flex justify-between items-center gap-6 bg-blue-50 p-2 rounded">
                                        <div class="h6 font-bold m-0 text-blue-700">
                                            {{ __('admin/ordersPages.Original Total:') }}
                                        </div>
                                        <div class="flex rtl:flex-row-reverse gap-1 text-blue-700">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-xl" dir="ltr">{{ number_format($original_total, 2) }}</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Base Price --}}
                                <div class="w-100 flex justify-between items-center gap-6">
                                    <div class="h6 font-bold m-0">
                                        {{ __('admin/ordersPages.Subtotal (before discounts):') }}
                                    </div>
                                    <div class="flex rtl:flex-row-reverse gap-1 text-primary @if ($items_discounts || $offers_discounts || $coupon_discount) line-through @endif">
                                        <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                        <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $items_base_prices)[0], 0, '.', '\'') }}</span>
                                        <span class="font-bold text-xs">{{ explode('.', $items_base_prices)[1] ?? '00' }}</span>
                                    </div>
                                </div>

                                @if ($items_discounts)
                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">{{ __('admin/ordersPages.Products Discounts:') }}</div>
                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                                <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $items_discounts)[0], 0, '.', '\'') }}</span>
                                                <span class="font-bold text-xs">{{ explode('.', number_format($items_discounts, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>({{ $items_discounts_percentage }} %)</span>
                                        </div>
                                    </div>
                                    <hr class="my-1 w-full">
                                @endif

                                @if ($offers_discounts)
                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">{{ __('admin/ordersPages.Offers Discounts:') }}</div>
                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                                <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $offers_discounts)[0], 0, '.', '\'') }}</span>
                                                <span class="font-bold text-xs">{{ explode('.', number_format($offers_discounts, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>({{ $offers_discounts_percentage }} %)</span>
                                        </div>
                                    </div>
                                    <hr class="w-full">
                                @endif

                                @if ($coupon_discount)
                                    <div class="flex justify-between items-center gap-6 py-1 w-full">
                                        <span class="h6 font-bold m-0">{{ __('admin/ordersPages.Coupon Discount:') }}</span>
                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                                <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $coupon_discount)[0], 0, '.', '\'') }}</span>
                                                <span class="font-bold text-xs">{{ explode('.', number_format($coupon_discount, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>({{ $coupon_discount_percentage }} %)</span>
                                        </div>
                                    </div>
                                @endif

                                {{-- Shipping --}}
                                <div class="w-100 flex justify-between items-center gap-6">
                                    <div class="h6 font-bold m-0 grow min-w-max">{{ __('admin/ordersPages.Shipping:') }}</div>
                                    <div>
                                        @if ($delivery_fees == 0 || $coupon_free_shipping)
                                            <span class="text-successDark">{{ __('admin/ordersPages.Free Shipping') }}</span>
                                        @else
                                            <span class="flex rtl:flex-row-reverse gap-1 text-primary">
                                                <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                                <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $delivery_fees)[0], 0, '.', '\'') }}</span>
                                                <span class="font-bold text-xs">{{ explode('.', number_format($delivery_fees, 2))[1] ?? '00' }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="p-4 flex flex-col gap-3 justify-center items-center">
                                {{-- New Total --}}
                                <div class="w-full flex justify-between items-center gap-6">
                                    <div class="h6 font-bold m-0">{{ __('admin/ordersPages.Total:') }}</div>
                                    <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                        <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                        <span class="font-bold text-2xl" dir="ltr">{{ number_format(explode('.', $total)[0], 0, '.', '\'') }}</span>
                                        <span class="font-bold text-xs">{{ explode('.', number_format($total, 2))[1] ?? '00' }}</span>
                                    </div>
                                </div>

                                {{-- Diff indicator --}}
                                @if($original_total > 0 && $total != $original_total)
                                    <div class="w-full flex justify-between items-center gap-6 p-2 rounded {{ $total > $original_total ? 'bg-red-50' : 'bg-green-50' }}">
                                        <div class="h6 font-bold m-0 {{ $total > $original_total ? 'text-red-700' : 'text-green-700' }}">
                                            {{ $total > $original_total ? __('admin/ordersPages.Increase:') : __('admin/ordersPages.Decrease:') }}
                                        </div>
                                        <div class="flex rtl:flex-row-reverse gap-1 {{ $total > $original_total ? 'text-red-700' : 'text-green-700' }}">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-xl" dir="ltr">{{ number_format(abs($total - $original_total), 2) }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($wallet > 0)
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">{{ __('admin/ordersPages.Paid by wallet:') }}</div>
                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $wallet)[0], 0, '.', '\'') }}</span>
                                            <span class="font-bold text-xs">{{ explode('.', number_format($wallet, 2))[1] ?? '00' }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($points > 0)
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">{{ __('admin/ordersPages.Paid using points:') }}</div>
                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-xl" dir="ltr">{{ number_format(explode('.', $points_egp)[0], 0, '.', '\'') }}</span>
                                            <span class="font-bold text-xs">{{ explode('.', number_format($points_egp, 2))[1] ?? '00' }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($wallet > 0 || $points > 0)
                                    <hr class="w-full">
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">{{ __('admin/ordersPages.Total after wallet:') }}</div>
                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-2xl" dir="ltr">{{ number_format(explode('.', $total_after_wallet)[0], 0, '.', '\'') }}</span>
                                            <span class="font-bold text-xs">{{ explode('.', number_format($total_after_wallet, 2))[1] ?? '00' }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- Order Summary :: End --}}
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex flex-wrap items-center justify-center gap-3 px-6 py-4 border-t border-gray-100" style="background: #f8fafc;">
                    <button type="button" wire:click="getOrderData(true)"
                        class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-5 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #d97706, #b45309);">
                        <span class="material-icons text-lg">save</span>
                        {{ __('admin/ordersPages.Update Order') }}
                    </button>

                    <button type="button" onclick="modal.hide()"
                        class="inline-flex items-center gap-2 font-bold rounded-xl px-5 py-2.5 text-sm border-2 border-gray-300 text-gray-600 bg-white transition-all duration-200 hover:bg-gray-50 hover:border-gray-400">
                        <span class="material-icons text-lg">close</span>
                        {{ __('admin/ordersPages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Summary Part :: End --}}
</div>

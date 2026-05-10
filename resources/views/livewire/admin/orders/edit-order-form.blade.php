<div class="flex flex-col justify-center items-center gap-3">
    <x-admin.waiting />

    {{-- User Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-user-part', key('user-part'), ['customerId' => $this->customerId])
    </div>
    {{-- User Part :: End --}}

    {{-- Products Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-products-part', key('products-part'), ['customerId' => $this->customerId])
    </div>
    {{-- Products Part :: End --}}

    {{-- Payment Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-payment-part', key('payment-part'), ['customerId' => $this->customerId])
    </div>
    {{-- Payment Part :: End --}}

    {{-- Notes :: Start --}}
    <div class="w-full bg-red-50 p-2 pb-3 rounded-xl shadow">
        <div class="grid grid-cols-4 justify-center items-center gap-3 p-3">
            <h2 class="col-span-4 text-center font-bold">
                {{ __('front/homePage.Notes') }}
            </h2>
            <div class="col-span-4">
                <textarea id="notes" rows="2" wire:model.live.blur="notes" dir="rtl"
                    placeholder="{{ __('front/homePage.Please mention any note related to the order') }}"
                    class="w-full py-1 rounded text-center border-red-300 focus:outline-0 focus:ring-0 focus:border-primary overflow-hidden">
                </textarea>
            </div>
        </div>

        {{-- Allow open package :: Start --}}
        <div class="col-span-4">
            <label for="allowToOpenPackage" class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3">
                {{ __('admin/ordersPages.Allow to open package') }}
            </label>
            <input id="allowToOpenPackage" type="checkbox" wire:model.live="allowToOpenPackage" class="appearance-none border-red-600 rounded-full checked:bg-primary outline-none ring-0 cursor-pointer">
        </div>
        {{-- Allow open package :: End --}}
    </div>
    {{-- Notes :: End --}}

    {{-- Edit Reason :: Start --}}
    <div class="w-full bg-yellow-50 p-2 pb-3 rounded-xl shadow">
        <div class="grid grid-cols-4 justify-center items-center gap-3 p-3">
            <h2 class="col-span-4 text-center font-bold">
                {{ __('admin/ordersPages.Edit Reason') }}
            </h2>
            <div class="col-span-4">
                <textarea id="edit_reason" rows="2" wire:model.live.blur="edit_reason" dir="rtl"
                    placeholder="{{ __('admin/ordersPages.Please mention the reason for editing this order') }}"
                    class="w-full py-1 rounded text-center border-yellow-300 focus:outline-0 focus:ring-0 focus:border-primary overflow-hidden">
                </textarea>
            </div>
        </div>
    </div>
    {{-- Edit Reason :: End --}}

    {{-- Errors :: Start --}}
    <div class="">
        @if ($errors->any())
            <div class="bg-red-500 rounded-lg p-3 text-white font-bold">
                <ul class="text-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    {{-- Errors :: End --}}

    {{-- Buttons :: Start --}}
    <div class="flex flex-wrap items-center justify-around gap-3 w-full">
        <button wire:click="getOrderData(false)"
            class="text-white font-bold rounded px-3 py-2 bg-success hover:bg-successDark">
            {{ __('admin/ordersPages.Calculate the order cost') }}
        </button>

        <button wire:click="getOrderData(true)"
            class="text-white font-bold rounded px-3 py-2 bg-yellow-600 hover:bg-yellow-700">
            {{ __('admin/ordersPages.Update Order') }}
        </button>

        <a href="{{ route('admin.orders.new-orders') }}"
            class="btn font-bold bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4 rounded-t border-b">
                    <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('admin/ordersPages.Order Summary') }}
                        <span class="text-sm text-gray-500">({{ __('admin/ordersPages.Edit Order') }} #{{ $order->id }})</span>
                    </h3>
                    <button type="button" onclick="modal.hide()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
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
                <div class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                    <button type="button" wire:click="getOrderData(true)"
                        class="btn font-bold text-white bg-yellow-600 hover:bg-yellow-700 hover:text-white focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        {{ __('admin/ordersPages.Update Order') }}
                    </button>

                    <button type="button" onclick="modal.hide()"
                        class="btn font-bold bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('admin/ordersPages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Summary Part :: End --}}
</div>

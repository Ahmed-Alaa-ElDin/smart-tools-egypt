<div class="flex flex-col justify-center items-center gap-5">
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
            style="background: linear-gradient(135deg, #22c55e, #16a34a);">
            <span class="material-icons text-lg">shopping_cart_checkout</span>
            {{ __('admin/ordersPages.Create Order') }}
        </button>

        <button wire:click="getOrderData(true, true)"
            class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-5 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
            style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
            <span class="material-icons text-lg">playlist_add</span>
            {{ __('admin/ordersPages.Create and Start another order') }}
        </button>

        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 font-bold rounded-xl px-5 py-2.5 text-sm border-2 border-gray-300 text-gray-600 bg-white transition-all duration-200 hover:bg-gray-50 hover:border-gray-400 hover:shadow-sm">
            <span class="material-icons text-lg">arrow_back</span>
            {{ __('admin/ordersPages.Back') }}
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
                <div class="flex justify-between items-center px-6 py-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                    <h3 class="grow text-lg font-bold text-white m-0 flex items-center gap-2">
                        <span class="material-icons">receipt_long</span>
                        {{ __('admin/ordersPages.Order Summary') }}
                    </h3>
                    <button type="button" onclick="modal.hide()"
                        class="text-gray-300 bg-transparent hover:bg-white/10 hover:text-white rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-all duration-200">
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
                        {{-- Order Summery :: Start --}}
                        <div>
                            {{-- ############## Title :: Start ############## --}}
                            <div class="flex justify-between items-center gap-3 p-4">
                                <h3 class="h5 text-center font-bold m-0">
                                    {{ __('admin/ordersPages.Order Summary') }}
                                </h3>

                                <h4 class="text-sm font-bold">
                                    {{ trans_choice('admin/ordersPages.Product', $items_total_quantities, ['product' => $items_total_quantities]) }}
                                </h4>
                            </div>
                            {{-- ############## Title :: End ############## --}}

                            <hr>

                            <div class="font-bold p-4 flex flex-col gap-3 justify-center items-center">

                                {{-- ############## Base Price :: Start ############## --}}
                                <div class="w-100 flex justify-between items-center gap-6">
                                    <div class="h6 font-bold m-0">
                                        {{ __('admin/ordersPages.Subtotal (before discounts):') }}
                                    </div>

                                    <div
                                        class="flex rtl:flex-row-reverse gap-1 text-primary @if ($items_discounts || $offers_discounts || $coupon_discount) line-through @endif">
                                        <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                        <span class="font-bold text-xl"
                                            dir="ltr">{{ number_format(explode('.', $items_base_prices)[0], 0, '.', '\'') }}</span>
                                        <span
                                            class="font-bold text-xs">{{ explode('.', $items_base_prices)[1] ?? '00' }}</span>
                                    </div>

                                </div>
                                {{-- ############## Base Price :: End ############## --}}

                                @if ($items_discounts)
                                    {{-- ############## Products Discounts :: Start ############## --}}
                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Products Discounts:') }}
                                        </div>

                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">
                                                    {{ __('admin/ordersPages.EGP') }}
                                                </span>
                                                <span class="font-bold text-xl"
                                                    dir="ltr">{{ number_format(explode('.', $items_discounts)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-xs">{{ explode('.', number_format($items_discounts, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>
                                                ({{ $items_discounts_percentage }} %)
                                            </span>
                                        </div>

                                    </div>
                                    {{-- ############## Products Discounts :: End ############## --}}

                                    <hr class="my-1 w-full">

                                    {{-- ############## Final Price :: Start ############## --}}
                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Subtotal (after discounts):') }}
                                        </div>

                                        <div
                                            class="flex rtl:flex-row-reverse gap-1 text-primary @if ($offers_discounts || $coupon_discount) line-through @endif">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-xl"
                                                dir="ltr">{{ number_format(explode('.', $items_final_prices)[0], 0, '.', '\'') }}</span>
                                            <span
                                                class="font-bold text-xs">{{ explode('.', $items_final_prices)[1] ?? '00' }}</span>
                                        </div>
                                    </div>
                                    {{-- ############## Final Price :: End ############## --}}
                                @endif

                                @if ($offers_discounts)
                                    {{-- ############## Offers Discounts :: Start ############## --}}
                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Offers Discounts:') }}
                                        </div>

                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">
                                                    {{ __('admin/ordersPages.EGP') }}
                                                </span>
                                                <span class="font-bold text-xl"
                                                    dir="ltr">{{ number_format(explode('.', $offers_discounts)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-xs">{{ explode('.', number_format($offers_discounts, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>
                                                ({{ $offers_discounts_percentage }} %)
                                            </span>
                                        </div>

                                    </div>
                                    {{-- ############## Offers Discounts :: End ############## --}}

                                    <hr class="w-full">
                                @endif

                                @if ($order_discount)
                                    {{-- ############## Best Price :: Start ############## --}}
                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Subtotal (after offers):') }}
                                        </div>

                                        <div class="flex rtl:flex-row-reverse gap-1 text-primary ">
                                            <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                            <span class="font-bold text-xl"
                                                dir="ltr">{{ number_format(explode('.', $items_best_prices)[0], 0, '.', '\'') }}</span>
                                            <span
                                                class="font-bold text-xs">{{ explode('.', $items_best_prices)[1] ?? '00' }}</span>
                                        </div>
                                    </div>
                                    {{-- ############## Best Price :: End ############## --}}

                                    {{-- ############## Order Discount :: End ############## --}}
                                    <div class="flex justify-between items-center gap-6 py-1 w-full">
                                        <span class="h6 font-bold m-0"> {{ __('admin/ordersPages.Order Discount:') }}
                                        </span>

                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">
                                                    {{ __('admin/ordersPages.EGP') }}
                                                </span>
                                                <span class="font-bold text-xl"
                                                    dir="ltr">{{ number_format(explode('.', $order_discount)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-xs">{{ explode('.', number_format($order_discount, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>
                                                ({{ $order_discount_percentage }} %)
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                {{-- ############## Order Discount :: End ############## --}}

                                {{-- ############## Coupon Discount :: End ############## --}}
                                @if ($coupon_discount)
                                    <div class="flex justify-between items-center gap-6 gap-1 py-1 w-full">
                                        <span class="h6 font-bold m-0"> {{ __('admin/ordersPages.Coupon Discount:') }}
                                        </span>

                                        <div class="flex gap-2 text-successDark">
                                            <span class="flex rtl:flex-row-reverse gap-1">
                                                <span class="font-bold text-sm">
                                                    {{ __('admin/ordersPages.EGP') }}
                                                </span>
                                                <span class="font-bold text-xl"
                                                    dir="ltr">{{ number_format(explode('.', $coupon_discount)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-xs">{{ explode('.', number_format($coupon_discount, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>
                                                ({{ $coupon_discount_percentage }} %)
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                {{-- ############## Coupon Discount :: End ############## --}}

                                {{-- ############## Shipping:: Start ############## --}}
                                <div class="w-100 flex justify-between items-center gap-6">
                                    <div class="h6 font-bold m-0 grow min-w-max">
                                        {{ __('admin/ordersPages.Shipping:') }}
                                    </div>
                                    <div>
                                        {{-- Free Shipping --}}
                                        @if ($delivery_fees == 0 || $coupon_free_shipping)
                                            <span class="text-successDark">
                                                {{ __('admin/ordersPages.Free Shipping') }}
                                            </span>
                                        @else
                                            {{-- Calculate Shipping --}}
                                            <span class="flex rtl:flex-row-reverse gap-1 text-primary">
                                                <span class="font-bold text-sm">
                                                    {{ __('admin/ordersPages.EGP') }}
                                                </span>

                                                <span class="font-bold text-xl"
                                                    dir="ltr">{{ number_format(explode('.', $delivery_fees)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-xs">{{ explode('.', number_format($delivery_fees, 2))[1] ?? '00' }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- ############## Shipping:: End ############## --}}
                            </div>

                            <hr>

                            <div class="p-4 flex flex-col gap-3 justify-center items-center">
                                {{-- ############## Total:: Start ############## --}}
                                <div class="w-full flex justify-between items-center gap-6">
                                    <div class="h6 font-bold m-0">
                                        {{ __('admin/ordersPages.Total:') }}
                                    </div>

                                    <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                        <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                        <span class="font-bold text-2xl"
                                            dir="ltr">{{ number_format(explode('.', $total)[0], 0, '.', '\'') }}</span>
                                        <span
                                            class="font-bold text-xs">{{ explode('.', number_format($total, 2))[1] ?? '00' }}</span>
                                    </div>

                                </div>
                                {{-- ############## Total:: End ############## --}}

                                {{-- ############## Wallet :: Start ############## --}}
                                @if ($wallet > 0)
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Paid by wallet:') }}
                                        </div>

                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">
                                                {{ __('admin/ordersPages.EGP') }}
                                            </span>

                                            <span class="font-bold text-xl"
                                                dir="ltr">{{ number_format(explode('.', $wallet)[0], 0, '.', '\'') }}
                                            </span>
                                            <span class="font-bold text-xs">
                                                {{ explode('.', number_format($wallet, 2))[1] ?? '00' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                {{-- ############## Wallet :: End ############## --}}

                                {{-- ############## Points :: Start ############## --}}
                                @if ($points > 0)
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Paid using points:') }}
                                        </div>

                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">
                                                {{ __('admin/ordersPages.EGP') }}
                                            </span>

                                            <span class="font-bold text-xl" dir="ltr">
                                                {{ number_format(explode('.', $points_egp)[0], 0, '.', '\'') }}
                                            </span>
                                            <span class="font-bold text-xs">
                                                {{ explode('.', number_format($points_egp, 2))[1] ?? '00' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                {{-- ############## Points :: End ############## --}}

                                {{-- ############## Total After Wallet :: Start ############## --}}
                                @if ($wallet > 0 || $points > 0)
                                    <hr class="w-full">

                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Total after wallet:') }}
                                        </div>

                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">
                                                {{ __('admin/ordersPages.EGP') }}
                                            </span>
                                            <span class="font-bold text-2xl" dir="ltr">
                                                {{ number_format(explode('.', $total_after_wallet)[0], 0, '.', '\'') }}
                                            </span>
                                            <span class="font-bold text-xs">
                                                {{ explode('.', number_format($total_after_wallet, 2))[1] ?? '00' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                {{-- ############## Total After Wallet :: End ############## --}}
                            </div>
                        </div>
                        {{-- Order Summery :: End --}}

                        {{-- Getting Points :: Start --}}
                        @if ($total_points)
                            <div>
                                {{-- ############## Title :: Start ############## --}}
                                <div class="flex justify-around items-center gap-3 p-4">
                                    <h3 class="h5 text-center font-bold m-0">
                                        {{ __('admin/ordersPages.Customer will get') }}
                                    </h3>
                                </div>
                                {{-- ############## Title :: End ############## --}}

                                <hr>

                                <div class="font-bold p-4 flex flex-col gap-3 justify-center items-center">

                                    {{-- ############## Products Points :: Start ############## --}}
                                    @if ($items_best_points)
                                        <div class="w-100 flex justify-between items-center gap-6">
                                            <div class="h6 font-bold m-0">
                                                {{ __('admin/ordersPages.Products Points :') }}
                                            </div>

                                            <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                                <span class="font-bold text-sm">
                                                    {{ trans_choice('admin/ordersPages.Point/Points', $items_best_points, ['points' => $items_best_points]) }}
                                                </span>
                                                <span class="font-bold text-xl" dir="ltr">
                                                    {{ number_format($items_best_points, 0, '.', '\'') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- ############## Products Points :: End ############## --}}

                                    {{-- ############## Order Points :: Start ############## --}}
                                    @if ($order_points)
                                        <div class="w-100 flex justify-between items-center gap-6">
                                            <div class="h6 font-bold m-0">
                                                {{ __('admin/ordersPages.Order Points :') }}
                                            </div>

                                            <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                                <span class="font-bold text-sm">
                                                    {{ trans_choice('admin/ordersPages.Point/Points', $order_points, ['points' => $order_points]) }}
                                                </span>
                                                <span class="font-bold text-xl" dir="ltr">
                                                    {{ number_format($order_points, 0, '.', '\'') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- ############## Order Points :: End ############## --}}

                                    {{-- ############## Coupon Points :: Start ############## --}}
                                    @if ($coupon_points)
                                        <div class="w-100 flex justify-between items-center gap-6">
                                            <div class="h6 font-bold m-0">
                                                {{ __('admin/ordersPages.Coupon Points :') }}
                                            </div>

                                            <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                                <span class="font-bold text-sm">
                                                    {{ trans_choice('admin/ordersPages.Point/Points', $coupon_points, ['points' => $coupon_points]) }}
                                                </span>
                                                <span class="font-bold text-xl" dir="ltr">
                                                    {{ number_format($coupon_points, 0, '.', '\'') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- ############## Coupon Points :: End ############## --}}


                                    {{-- ############## Total Points :: Start ############## --}}
                                    <hr class="w-full">

                                    <div class="w-100 flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Total Points :') }}
                                        </div>

                                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                            <span class="font-bold text-sm">
                                                {{ trans_choice('admin/ordersPages.Point/Points', $total_points, ['points' => $total_points]) }}
                                            </span>
                                            <span class="font-bold text-2xl" dir="ltr">
                                                {{ number_format($total_points, 0, '.', '\'') }}
                                            </span>
                                        </div>
                                    </div>
                                    {{-- ############## Total Points :: End ############## --}}

                                </div>
                            </div>
                        @endif
                        {{-- Getting Points :: End --}}
                    </div>

                </div>
                <!-- Modal footer -->
                <div class="flex flex-wrap items-center justify-center gap-3 px-6 py-4 border-t border-gray-100" style="background: #f8fafc;">
                    <button type="button" wire:click="getOrderData(true)"
                        class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-5 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <span class="material-icons text-lg">shopping_cart_checkout</span>
                        {{ __('admin/ordersPages.Create Order') }}
                    </button>

                    <button type="button" wire:click="getOrderData(true, true)"
                        class="inline-flex items-center gap-2 text-white font-bold rounded-xl px-5 py-2.5 text-sm shadow-sm transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
                        <span class="material-icons text-lg">playlist_add</span>
                        {{ __('admin/ordersPages.Create and Start another order') }}
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

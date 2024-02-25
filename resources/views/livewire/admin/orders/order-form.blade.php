<div class="flex flex-col justify-center items-center gap-3">
    <x-admin.waiting />

    {{-- User Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-user-part', key('user-part'))
    </div>
    {{-- User Part :: End --}}

    {{-- Products Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-products-part', key('products-part'))
    </div>
    {{-- Products Part :: End --}}

    {{-- Payment Part :: Start --}}
    <div class="w-full">
        @livewire('admin.orders.new-order-payment-part', key('payment-part'))
    </div>
    {{-- Payment Part :: End --}}

    {{-- Notes :: Start --}}
    <div class="w-full bg-red-50 p-2 rounded-xl shadow">
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
    </div>
    {{-- Notes :: End --}}

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
            class="text-white font-bold rounded px-3 py-2 bg-success hover:bg-successDark">
            {{ __('admin/ordersPages.Create Order') }}
        </button>

        <a href="{{ url()->previous() }}"
            class="btn font-bold bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4 rounded-t border-b">
                    <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('admin/ordersPages.Order Summary') }}
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
                        {{-- Order Summery :: Start --}}
                        <div>
                            {{-- ############## Title :: Start ############## --}}
                            <div class="flex justify-between items-center gap-3 p-4">
                                <h3 class="h5 text-center font-bold m-0">
                                    {{ __('admin/ordersPages.Order Summary') }}
                                </h3>

                                <h4 class="text-sm font-bold">
                                    {{ trans_choice('admin/ordersPages.Product', $product_total_amounts, ['product' => $product_total_amounts]) }}
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
                                        class="flex rtl:flex-row-reverse gap-1 text-primary @if ($products_discounts || $offers_discounts || $coupon_discount) line-through @endif">
                                        <span class="font-bold text-sm">{{ __('admin/ordersPages.EGP') }}</span>
                                        <span class="font-bold text-xl"
                                            dir="ltr">{{ number_format(explode('.', $products_base_prices)[0], 0, '.', '\'') }}</span>
                                        <span
                                            class="font-bold text-xs">{{ explode('.', $products_base_prices)[1] ?? '00' }}</span>
                                    </div>

                                </div>
                                {{-- ############## Base Price :: End ############## --}}

                                @if ($products_discounts)
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
                                                    dir="ltr">{{ number_format(explode('.', $products_discounts)[0], 0, '.', '\'') }}</span>
                                                <span
                                                    class="font-bold text-xs">{{ explode('.', number_format($products_discounts, 2))[1] ?? '00' }}</span>
                                            </span>
                                            <span>
                                                ({{ $products_discounts_percentage }} %)
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
                                                dir="ltr">{{ number_format(explode('.', $products_final_prices)[0], 0, '.', '\'') }}</span>
                                            <span
                                                class="font-bold text-xs">{{ explode('.', $products_final_prices)[1] ?? '00' }}</span>
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
                                                dir="ltr">{{ number_format(explode('.', $products_best_prices)[0], 0, '.', '\'') }}</span>
                                            <span
                                                class="font-bold text-xs">{{ explode('.', $products_best_prices)[1] ?? '00' }}</span>
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
                                @if ($wallet)
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Paid by wallet :') }}
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
                                @if ($points)
                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Paid using points :') }}
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
                                @if ($wallet || $points)
                                    <hr class="w-full">

                                    <div class="w-full flex justify-between items-center gap-6">
                                        <div class="h6 font-bold m-0">
                                            {{ __('admin/ordersPages.Total after wallet :') }}
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
                                    @if ($products_best_points)
                                        <div class="w-100 flex justify-between items-center gap-6">
                                            <div class="h6 font-bold m-0">
                                                {{ __('admin/ordersPages.Products Points :') }}
                                            </div>

                                            <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                                <span class="font-bold text-sm">
                                                    {{ trans_choice('admin/ordersPages.Point/Points', $products_best_points, ['points' => $products_best_points]) }}
                                                </span>
                                                <span class="font-bold text-xl" dir="ltr">
                                                    {{ number_format($products_best_points, 0, '.', '\'') }}
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
                <div class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                    <button type="button" wire:click="getOrderData(true)"
                        class="btn font-bold text-white bg-success hover:bg-successDark hover:text-white focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        {{ __('admin/ordersPages.Create Order') }}
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

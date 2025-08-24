<div class="relative">
    <div wire:loading class="absolute top-0 left-0 z-10 w-full h-full backdrop-blur-sm select-none animate-pulse">
        <div class="flex flex-col justify-center items-center">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-waiting-400.png') }}"
                class="w-48 drop-shadow-[0px_0px_20px_rgba(255,255,255,0.7)]" alt="Loading" draggable="false">
            <span class="text-primary h3 font-bold"
                style="text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff">{{ __('admin/master.Loading ...') }}</span>
        </div>
    </div>

    {{-- ############## Coupon :: Start ############## --}}
    <div wire:ignore>
        <div class="flex justify-between items-center p-4">
            <h3 class="h5 text-center font-bold m-0">
                {{ __('front/homePage.Add Coupon') }}
            </h3>
        </div>
        <hr>
        <div class="p-3">
            @livewire('front.order.payment.coupon-block', [
                'items' => $items,
                // 'total' => $total,
                // 'points' => $total_points,
            ])
        </div>
    </div>
    <hr>
    {{-- ############## Coupon :: End ############## --}}

    {{-- ############## Title :: Start ############## --}}
    <div class="flex justify-between items-center p-4">
        <h3 class="h5 text-center font-bold m-0">
            {{ __('front/homePage.Order Summary') }}
        </h3>

        <h4 class="text-sm font-bold">
            {{ trans_choice('front/homePage.Item', $items_total_quantities, ['item' => $items_total_quantities]) }}
        </h4>
    </div>
    {{-- ############## Title :: End ############## --}}
    <hr>
    <div class="font-bold p-4 flex flex-col gap-3 justify-center items-center">

        {{-- ############## Base Price :: Start ############## --}}
        <div class="w-100 flex justify-between items-center">
            <div class="h6 font-bold m-0">
                {{ __('front/homePage.Subtotal before products discounts') }}
            </div>

            <div
                class="flex rtl:flex-row-reverse gap-1 text-successDark @if ($items_total_discounts || $offers_total_discounts || $order_discount) line-through @endif">
                <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                <span class="font-bold text-xl"
                    dir="ltr">{{ number_format(explode('.', $items_total_base_prices)[0], 0, '.', '\'') }}</span>
                <span class="font-bold text-xs">{{ explode('.', $items_total_base_prices)[1] ?? '00' }}</span>
            </div>

        </div>
        {{-- ############## Base Price :: End ############## --}}

        @if ($items_total_discounts)
            {{-- ############## Products Discounts :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Products Discounts:') }}
                </div>

                <div class="flex gap-2 text-success">
                    <span class="flex rtl:flex-row-reverse gap-1">
                        <span class="font-bold text-sm">
                            {{ __('front/homePage.EGP') }}
                        </span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $items_total_discounts)[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', number_format($items_total_discounts, 2))[1] ?? '00' }}</span>
                    </span>
                    <span>
                        ({{ $items_discounts_percentage }} %)
                    </span>
                </div>

            </div>
            {{-- ############## Products Discounts :: End ############## --}}

            <hr class="my-1 w-full">

            {{-- ############## Final Price :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Subtotal after products discounts:') }}
                </div>

                <div
                    class="flex rtl:flex-row-reverse gap-1 text-successDark @if ($offers_total_discounts || $order_discount) line-through @endif">
                    <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-xl"
                        dir="ltr">{{ number_format(explode('.', $items_total_final_prices)[0], 0, '.', '\'') }}</span>
                    <span class="font-bold text-xs">{{ explode('.', $items_total_final_prices)[1] ?? '00' }}</span>
                </div>
            </div>
            {{-- ############## Final Price :: End ############## --}}
        @endif

        @if ($offers_total_discounts)
            {{-- ############## Offers Discounts :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Offers Discounts:') }}
                </div>

                <div class="flex gap-2 text-success">
                    <span class="flex rtl:flex-row-reverse gap-1">
                        <span class="font-bold text-sm">
                            {{ __('front/homePage.EGP') }}
                        </span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $offers_total_discounts)[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', number_format($offers_total_discounts, 2))[1] ?? '00' }}</span>
                    </span>
                    <span>
                        ({{ $offers_discounts_percentage }} %)
                    </span>
                </div>

            </div>
            {{-- ############## Offers Discounts :: End ############## --}}

            <hr class="my-1 w-full">

            {{-- ############## Best Price :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Subtotal after offers discounts:') }}
                </div>

                <div
                    class="flex rtl:flex-row-reverse gap-1 text-successDark @if ($order_discount) line-through @endif">
                    <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-xl"
                        dir="ltr">{{ number_format(explode('.', $total_after_offer_prices)[0], 0, '.', '\'') }}</span>
                    <span class="font-bold text-xs">{{ explode('.', $total_after_offer_prices)[1] ?? '00' }}</span>
                </div>
            </div>
            {{-- ############## Best Price :: End ############## --}}
        @endif

        {{-- ############## Shipping:: Start ############## --}}
        @if ($items_total_quantities)
            <div class="w-100 flex justify-between items-center gap-3">
                <div class="h6 font-bold m-0 grow min-w-max">
                    {{ __('front/homePage.Shipping:') }}
                </div>
                <div>
                    {{-- Free Shipping --}}
                    @if ($total_order_free_shipping || $shipping_fees == 0)
                        <span class="text-success">
                            {{ __('front/homePage.Free Shipping') }}
                        </span>
                    @elseif ($address === null)
                        <span class="text-xs text-danger">
                            {{ __('front/homePage.select default address') }}
                        </span>
                    @elseif ($best_zone_id === null)
                        <span class="text-xs text-danger text-center">
                            {!! __('front/homePage.No Deliveries', [
                                'city' => $city_name,
                                'icon' =>
                                    '<a href="https://wa.me/+2' .
                                    config('settings.whatsapp_number') .
                                    '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span class="text-sm">' .
                                    config('settings.whatsapp_number') .
                                    '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                            ]) !!}
                        </span>
                    @elseif ($shipping_fees)
                        <div class="flex gap-2 text-success">

                            {{-- Calculate Shipping --}}
                            <span class="flex rtl:flex-row-reverse gap-1">
                                <span class="font-bold text-sm">
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $shipping_fees)[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format($shipping_fees, 2))[1] ?? '00' }}</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        {{-- ############## Shipping:: End ############## --}}

        @if ($order_discount)
            {{-- ############## Order Discounts :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Discount on order:') }}
                </div>

                <div class="flex gap-2 text-success">
                    <span class="flex rtl:flex-row-reverse gap-1">
                        <span class="font-bold text-sm">
                            {{ __('front/homePage.EGP') }}
                        </span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $order_discount)[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', number_format($order_discount, 2))[1] ?? '00' }}</span>
                    </span>
                    <span>
                        ({{ $order_discount_percent }} %)
                    </span>
                </div>

            </div>
            {{-- ############## Order Discounts :: End ############## --}}

            <hr class="my-1 w-full">

            {{-- ############## Total After Order Discount :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Subtotal after order discounts:') }}
                </div>

                <div
                    class="flex rtl:flex-row-reverse gap-1 text-successDark @if ($coupon_id && $total_after_order_discount > $total_after_coupon_discount) line-through @endif">
                    <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-xl"
                        dir="ltr">{{ number_format(explode('.', $total_after_order_discount)[0], 0, '.', '\'') }}</span>
                    <span class="font-bold text-xs">{{ explode('.', $total_after_order_discount)[1] ?? '00' }}</span>
                </div>
            </div>
            {{-- ############## Total After Order Discount :: End ############## --}}
        @endif

        @if ($coupon_id)
            {{-- ############## Coupon Discounts :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Coupon Discount:') }}
                </div>

                <div class="flex gap-2 text-success">
                    <span class="flex rtl:flex-row-reverse gap-1">
                        <span class="font-bold text-sm">
                            {{ __('front/homePage.EGP') }}
                        </span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $coupon_total_discount)[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', number_format($coupon_total_discount, 2))[1] ?? '00' }}</span>
                    </span>
                    <span>
                        ({{ $coupon_total_discount_percent }} %)
                    </span>
                </div>

            </div>
            {{-- ############## Coupon Discounts :: End ############## --}}

            <hr class="my-1 w-full">

            {{-- ############## Total After Coupon Discount :: Start ############## --}}
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Subtotal after coupon discounts:') }}
                </div>

                <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                    <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-xl"
                        dir="ltr">{{ number_format(explode('.', $total_after_coupon_discount)[0], 0, '.', '\'') }}</span>
                    <span
                        class="font-bold text-xs">{{ explode('.', number_format($total_after_coupon_discount, 2))[1] ?? '00' }}</span>
                </div>
            </div>
            {{-- ############## Total After Coupon Discount :: End ############## --}}
        @endif
    </div>

    <hr>
    <hr>

    <div class="p-4 flex flex-col gap-3 justify-center items-center bg-successDark rounded shadow-inner">
        {{-- ############## Total:: Start ############## --}}
        <div class="w-full flex justify-between items-center text-white">
            <div class="h6 font-bold m-0">
                {{ __('front/homePage.Total:') }}
            </div>

            <div class="flex rtl:flex-row-reverse gap-1 text-white">
                <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                <span class="font-bold text-2xl"
                    dir="ltr">{{ formatTotal($total_after_coupon_discount) }}</span>
                <span
                    class="font-bold text-xs">00</span>
            </div>

        </div>
        {{-- ############## Total:: End ############## --}}

        {{-- ############## Points :: Start ############## --}}
        @if ($total_points_after_coupon_points)
            <div class="w-full flex justify-between items-center bg-successDark text-white">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.You will get:') }}
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex rtl:flex-row-reverse gap-1">
                        <span
                            class="font-bold text-sm">{{ trans_choice('front/homePage.Point/Points', $total_points_after_coupon_points, ['points' => $total_points_after_coupon_points]) }}</span>
                        <span class="font-bold text-2xl"
                            dir="ltr">{{ number_format($total_points_after_coupon_points, 0, '.', '\'') }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- ############## Points :: End ############## --}}

    {{-- Payment Method Details :: Start --}}
    @if ($payment_method == 1)
        <hr>

        <div class="flex gap-2 justify-around items-center p-2">
            <button class="btn bg-success max-w-max font-bold"
                wire:click="$dispatchTo('front.order.payment.order-payment-details','submit')">
                {{ __('front/homePage.Submit & Confirm Order') }}
                &nbsp;
                <span class="material-icons">
                    done_all
                </span>
            </button>
        </div>
    @elseif ($payment_method == 2 || $payment_method == 3)
        <hr>

        <div class="flex gap-2 justify-around items-center p-2">
            <button class="btn bg-success max-w-max font-bold"
                wire:click="$dispatchTo('front.order.payment.order-payment-details','submit')">
                {{ __('front/homePage.Go to payment') }}
                &nbsp;
                <span class="material-icons">
                    credit_card
                </span>
            </button>
        </div>
    @elseif ($payment_method == 4 || $payment_method == 5)
        <hr>

        <div class="flex gap-2 justify-around items-center p-2">
            <button class="btn bg-success max-w-max font-bold"
                wire:click="$dispatchTo('front.order.payment.order-payment-details','submit')">
                {{ __('front/homePage.Submit & Confirm Order') }}
                &nbsp;
                <span class="material-icons">
                    account_balance_wallet
                </span>
            </button>

        </div>
    @endif
    {{-- Payment Method Details :: End --}}
</div>

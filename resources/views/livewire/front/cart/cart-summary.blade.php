<div class="relative">
    <div wire:loading class="absolute top-0 left-0 z-10 w-full h-full backdrop-blur-sm select-none animate-pulse">
        <div class="flex flex-col justify-center items-center">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-waiting-400.png') }}"
                class="w-48 drop-shadow-[0px_0px_20px_rgba(255,255,255,0.7)]" alt="Loading" draggable="false">
            <span class="text-primary h3 font-bold"
                style="text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff">{{ __('admin/master.Loading ...') }}</span>
        </div>
    </div>

    {{-- ############## Title :: Start ############## --}}
    <div class="flex justify-between items-center p-4">
        <h3 class="h5 text-center font-bold m-0">
            {{ __('front/homePage.Cart Summary') }}
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
                    {{ __('front/homePage.Subtotal after products discounts') }}
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
                    @if ($total_order_free_shipping)
                        <span class="text-success">
                            {{ __('front/homePage.Free Shipping') }}
                        </span>
                    @else
                        {{-- Calculate Shipping --}}
                        <span class="text-sm text-yellow-500">
                            {{ __('front/homePage.Will be determined in the next steps') }}
                        </span>
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

                <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                    <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-xl"
                        dir="ltr">{{ number_format(explode('.', $total_after_order_discount)[0], 0, '.', '\'') }}</span>
                    <span class="font-bold text-xs">{{ explode('.', $total_after_order_discount)[1] ?? '00' }}</span>
                </div>
            </div>
            {{-- ############## Total After Order Discount :: End ############## --}}
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
                    dir="ltr">{{ number_format(explode('.', $total_after_order_discount)[0], 0, '.', '\'') }}</span>
                <span
                    class="font-bold text-xs">{{ explode('.', number_format($total_after_order_discount, 2))[1] ?? '00' }}</span>
            </div>

        </div>
        {{-- ############## Total:: End ############## --}}

        {{-- ############## Points :: Start ############## --}}
        @if ($total_points_after_order_points)
            <div class="w-full flex justify-between items-center bg-successDark text-white">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.You will get:') }}
                </div>
                <div class="flex flex-col gap-2">
                    <div class="flex rtl:flex-row-reverse gap-1">
                        <span
                            class="font-bold text-sm">{{ trans_choice('front/homePage.Point/Points', $total_points_after_order_points, ['points' => $total_points_after_order_points]) }}</span>
                        <span class="font-bold text-2xl"
                            dir="ltr">{{ number_format($total_points_after_order_points, 0, '.', '\'') }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- ############## Points :: End ############## --}}

    {{-- ############## Buttons :: Start ############## --}}
    @if ($items_total_quantities > 0)
        <hr>
        <div class="p-2 flex justify-center items-center">
            <a class="btn bg-primary font-bold self-stretch" href="{{ route('front.order.shipping') }}">
                {{ __('front/homePage.Proceed to Shipping Info.') }}
                &nbsp;
                <span class="material-icons">
                    local_shipping
                </span>
            </a>
        </div>
    @else
        <hr>
        <div class="text-center p-3">
            <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                {{ __('front/homePage.Continue Shopping') }}
            </a>
        </div>
    @endif
    {{-- ############## Buttons :: End ############## --}}
</div>

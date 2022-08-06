@extends('layouts.front.user_control_layout', ['titlePage' => __('front/homePage.Edits Preview'), 'page' => 'orders'])

@section('breadcrumb')
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.homepage') }}">
            {{ __('front/homePage.Homepage') }}
        </a>
    </li>
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.orders.edit', $order_data['order_id']) }}">
            {{ __('front/homePage.Edit Order') }}
        </a>
    </li>
    <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
        {{ __('front/homePage.Edits Preview') }}
    </li>
@endsection

@section('sub-content')
    <div class="bg-white rounded-xl shadow col-span-12">
        {{-- ############## Title :: Start ############## --}}
        <div class="flex justify-between items-center">
            <h3 class="h5 text-center font-bold p-4 m-0">
                {{ __('front/homePage.Edits Preview') }}
            </h3>
        </div>
        {{-- ############## Title :: End ############## --}}

        <hr>

        {{-- ############## Preview :: Start ############## --}}
        <div class="p-4 flex flex-col lg:flex-row gap-8 justify-center">
            <div class="rounded-xl shadow pt-4 bg-gray-100 overflow-hidden w-full">
                {{-- Base Price --}}
                <div class="flex justify-between items-center gap-1 px-4 py-1">
                    <span class="text-sm font-bold"> {{ __('front/homePage.Subtotal (before discounts):') }} </span>

                    <div
                        class="flex rtl:flex-row-reverse gap-1 text-primary @if ($order_data['products_final_prices'] < $order_data['products_base_prices'] ||
                            $order_data['products_best_prices'] < $order_data['products_base_prices']) line-through @endif">
                        <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $order_data['products_base_prices'])[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', $order_data['products_base_prices'])[1] ?? '00' }}</span>
                    </div>
                </div>

                {{-- Products Disconts --}}
                @if ($order_data['products_final_prices'] < $order_data['products_base_prices'])
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Products Discounts:') }} </span>

                        <div class="flex gap-2 text-successDark">
                            <span class="flex rtl:flex-row-reverse gap-1">
                                <span class="font-bold text-sm">
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $order_data['products_discounts'])[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format($order_data['products_discounts'], 2))[1] ?? '00' }}</span>
                            </span>
                            <span>
                                ({{ $order_data['products_discounts_percentage'] }} %)
                            </span>
                        </div>
                    </div>

                    <hr class="my-2">

                    {{-- Final Price --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Subtotal (after discounts):') }} </span>

                        <div
                            class="flex rtl:flex-row-reverse gap-1 text-primary @if ($order_data['products_best_prices'] < $order_data['products_final_prices']) line-through @endif">
                            <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['products_final_prices'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', $order_data['products_final_prices'])[1] ?? '00' }}</span>
                        </div>
                    </div>
                @endif

                @if ($order_data['products_best_prices'] < $order_data['products_final_prices'])
                    {{-- Offers --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Offers Discounts:') }} </span>

                        <div class="flex gap-2 text-successDark">
                            <span class="flex rtl:flex-row-reverse gap-1">
                                <span class="font-bold text-sm">
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $order_data['offers_discounts'])[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format($order_data['offers_discounts'], 2))[1] ?? '00' }}</span>
                            </span>
                            <span>
                                ({{ $order_data['offers_discounts_percentage'] }} %)
                            </span>
                        </div>
                    </div>

                    <hr class="my-2">
                @endif

                {{-- Best Price --}}
                <div class="flex justify-between items-center gap-1 px-4 py-1">
                    <span class="text-sm font-bold"> {{ __('front/homePage.Subtotal (after offers):') }} </span>

                    <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                        <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $order_data['products_best_prices'])[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', $order_data['products_best_prices'])[1] ?? '00' }}</span>
                    </div>
                </div>

                {{-- Coupon --}}
                @if ($order_data['coupon_discount'] > 0)
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Coupon Discount:') }} </span>

                        <div class="flex gap-2 text-successDark">
                            <span class="flex rtl:flex-row-reverse gap-1">
                                <span class="font-bold text-sm">
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $order_data['coupon_discount'])[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format($order_data['coupon_discount'], 2))[1] ?? '00' }}</span>
                            </span>
                            <span>
                                ({{ $order_data['coupon_discount_percentage'] }} %)
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Wallet --}}
                @if ($order_data['used_balance'] > 0 || $order_data['used_points_egp'] > 0)
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Wallet Discount:') }} </span>

                        <div class="flex gap-2 text-successDark">
                            <span class="flex rtl:flex-row-reverse gap-1">
                                <span class="font-bold text-sm">
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $order_data['used_balance'] + $order_data['used_points_egp'])[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format($order_data['used_balance'] + $order_data['used_points_egp'], 2))[1] ?? '00' }}</span>
                            </span>
                        </div>
                    </div>
                @endif

                {{-- Delivery Fees --}}
                <div class="flex justify-between items-center gap-1 px-4 py-1">
                    <span class="text-sm font-bold"> {{ __('front/homePage.Delivery Fees:') }} </span>

                    @if ($order_data['delivery_fees'] > 0)
                        <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                            <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['delivery_fees'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', $order_data['delivery_fees'])[1] ?? '00' }}</span>
                        </div>
                    @else
                        <span class="text-successDark font-bold">
                            {{ __('front/homePage.Free Shipping') }}
                        </span>
                    @endif
                </div>

                <hr class="mt-2">

                {{-- Order Total --}}
                <div class="flex justify-between items-center gap-1 p-4 bg-white">
                    <span class="font-bold"> {{ __('front/homePage.Order Total:') }} </span>

                    <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                        <span class="font-bold">{{ __('front/homePage.EGP') }}</span>
                        <span class="font-bold text-2xl"
                            dir="ltr">{{ number_format(explode('.', $order_data['order_total'])[0], 0, '.', '\'') }}</span>
                        <span class="font-bold text-sm">{{ explode('.', $order_data['order_total'])[1] ?? '00' }}</span>
                    </div>
                </div>
            </div>

            <div class="w-full">
                {{-- Products Count --}}
                <div class="mb-3 rounded-xl shadow p-4 bg-gray-100 overflow-hidden">
                    <div class="font-bold flex gap-2 justify-between items-center">
                        <h4>
                            {{ __('front/homePage.Number of products:') }}
                        </h4>

                        <span class="text-xl text-successDark">
                            {{ trans_choice('front/homePage.Product', $order_data['products_total_quantities'], ['product' => $order_data['products_total_quantities']]) }}
                        </span>
                    </div>
                </div>

                {{-- Gifted Points --}}
                <div class="rounded-xl shadow pt-4 bg-gray-100 overflow-hidden w-full">
                    <h4 class="text-center font-bold">
                        {{ __('front/homePage.You will earn:') }}
                    </h4>

                    {{-- Products Points --}}
                    <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                        <span class="text-sm"> {{ __("front/homePage.Product's Points:") }} </span>

                        <span class="text-xl text-successDark">
                            <span dir="ltr">
                                {{ number_format($order_data['total_points'], 0, '.', '\'') }}
                            </span>
                            &nbsp;
                            <span class="text-sm">
                                {{ trans_choice('front/homePage.Point/Points', $order_data['total_points'], ['points' => $order_data['total_points']]) }}
                            </span>
                        </span>
                    </div>

                    @if ($order_data['coupon_points'] > 0)
                        {{-- Coupons Points --}}
                        <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                            <span class="text-sm"> {{ __("front/homePage.Coupon's Points:") }} </span>

                            <span class="text-xl text-successDark">
                                <span dir="ltr">
                                    {{ number_format($order_data['coupon_points'], 0, '.', '\'') }}
                                </span>
                                &nbsp;
                                <span class="text-sm">
                                    {{ trans_choice('front/homePage.Point/Points', $order_data['coupon_points'], ['points' => $order_data['coupon_points']]) }}
                                </span>
                            </span>
                        </div>
                    @endif

                    <hr class="mt-2">

                    {{-- Total Points --}}
                    <div class="font-bold flex gap-2 justify-between items-center p-4 bg-white">
                        <span class="text-sm"> {{ __('front/homePage.Total Points:') }} </span>

                        <span class="text-xl text-successDark">
                            <span dir="ltr">
                                {{ number_format($order_data['total_points'] + $order_data['coupon_points'], 0, '.', '\'') }}
                            </span>
                            &nbsp;
                            <span class="text-sm">
                                {{ trans_choice('front/homePage.Point/Points', $order_data['total_points'] + $order_data['coupon_points'], ['points' => $order_data['total_points'] + $order_data['coupon_points']]) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        {{-- ############## Preview :: End ############## --}}
    </div>

    <div>
        {{-- Buttons :: Start --}}
        <div class="p-2 flex justify-around items-center gap-2">
            <button type="submit" name="type" value="submit" class="btn bg-successDark font-bold">
                {{ __('front/homePage.Save Edits') }}
            </button>

            <a href="{{ route('front.orders.index') }}" class="btn bg-primary font-bold">
                {{ __('front/homePage.Back') }}
            </a>
        </div>
        {{-- Buttons :: End --}}
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        window.addEventListener('swalNotification', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                position: 'top-right',
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
    </script>
@endpush

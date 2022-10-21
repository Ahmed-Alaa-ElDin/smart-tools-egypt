@extends('layouts.front.user_control_layout', ['titlePage' => __('front/homePage.Edits Preview'), 'page' => 'orders'])

@section('breadcrumb')
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.homepage') }}">
            {{ __('front/homePage.Homepage') }}
        </a>
    </li>
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.orders.index') }}">
            {{ __('front/homePage.My Orders') }}
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
        <div class="p-4 flex flex-col lg:flex-row gap-4 justify-center items-start">
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

                @if ($order_data['order_offers_discounts'] > 0.0)
                    {{-- Order Offers --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Order Discount:') }} </span>

                        <div class="flex gap-2 text-successDark">
                            <span class="flex rtl:flex-row-reverse gap-1">
                                <span class="font-bold text-sm">
                                    {{ __('front/homePage.EGP') }}
                                </span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $order_data['order_offers_discounts'])[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format($order_data['order_offers_discounts'], 2))[1] ?? '00' }}</span>
                            </span>
                            <span>
                                ({{ $order_data['order_offers_discounts_percentage'] }} %)
                            </span>
                        </div>
                    </div>
                @endif

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
                        <span
                            class="font-bold text-sm">{{ explode('.', number_format($order_data['order_total'], 2))[1] ?? '00' }}</span>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col gap-4">
                {{-- Products Count --}}
                <div class="rounded-xl shadow p-4 bg-gray-100 overflow-hidden">
                    <div class="font-bold flex gap-2 justify-between items-center">
                        <h4>
                            {{ __('front/homePage.Number of products:') }}
                        </h4>

                        <span class="text-xl text-successDark">
                            {{ trans_choice('front/homePage.Item', $order_data['products_total_quantities'], ['item' => $order_data['products_total_quantities']]) }}
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

                {{-- Payment --}}
                <div class="rounded-xl shadow pt-4 bg-gray-100 overflow-hidden w-full">
                    <h4 class="text-center font-bold">
                        {{ __('front/homePage.Payment:') }}
                    </h4>

                    {{-- Payment Method --}}
                    <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                        <span class="text-sm"> {{ __('front/homePage.Payment Method:') }} </span>

                        <span class="text-successDark">
                            @if ($order_data['payment_method'] == 1)
                                {{ __('front/homePage.Cash on delivery (COD)') }}
                            @elseif ($order_data['payment_method'] == 2)
                                {{ __('front/homePage.Credit / Debit Card') }}
                            @elseif ($order_data['payment_method'] == 3)
                                {{ __('front/homePage.Installment') }}
                            @elseif ($order_data['payment_method'] == 4)
                                {{ __('front/homePage.Vodafone Cash') }}
                            @endif
                        </span>
                    </div>

                    {{-- Payment Status --}}
                    <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                        <span class="text-sm"> {{ __('front/homePage.Payment Status:') }} </span>

                        @if ($order_data['old_order_paid'])
                            <span class="text-successDark">
                                {{ __('front/homePage.Paid') }}
                            </span>
                        @else
                            <span class="text-primary">
                                {{ __('front/homePage.Unpaid') }}
                            </span>
                        @endif
                    </div>

                    {{-- Old Amount --}}
                    <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                        <span class="text-sm"> {{ __('front/homePage.Old Cost:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                            <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['old_price'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', number_format($order_data['old_price'], 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                    {{-- New Amount --}}
                    <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                        <span class="text-sm"> {{ __('front/homePage.New Cost:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                            <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['order_total'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', number_format($order_data['order_total'], 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                    <hr class="mt-2">

                    {{-- Difference --}}
                    <div class="font-bold flex gap-2 justify-between items-center p-4 bg-white">
                        @if ($order_data['difference'] < 0)
                            <span class="text-sm"> {{ __('front/homePage.Difference (get):') }} </span>
                        @elseif ($order_data['difference'] == 0)
                            <span class="text-sm"> {{ __('front/homePage.Difference:') }} </span>
                        @else
                            <span class="text-sm"> {{ __('front/homePage.Difference (pay):') }} </span>
                        @endif

                        <div
                            class="flex rtl:flex-row-reverse gap-1 @if ($order_data['difference'] <= 0) text-successDark @else text-primary @endif">
                            <span class="font-bold">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-2xl"
                                dir="ltr">{{ number_format(explode('.', abs($order_data['difference']))[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-sm">{{ explode('.', number_format(abs($order_data['difference']), 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- ############## Preview :: End ############## --}}

        <div>
            {{-- Buttons :: Start --}}
            <div class="p-2 flex justify-around items-center gap-2">

                @if ($order_data['products_total_quantities'] == 0)
                    <form method="POST"
                        action="{{ route('front.orders.cancel', [
                            'order_id' => $order_data['order_id'],
                            'new_order_id' => $order_data['new_order_id'],
                        ]) }}"
                        class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn text-red-600 bg-white font-bold focus:outline-none border-2 border-red-200 text-sm px-5 py-2.5 hover:text-red-800 focus:z-10">
                            {{ __('front/homePage.Cancel Order') }}
                        </button>
                    </form>
                @elseif ($order_data['payment_method'] == 1)
                    <form
                        action="{{ route('front.orders.update', [$order_data['order_id'], $order_data['new_order_id']]) }}"
                        method="POST" class="m-0">
                        @csrf
                        @method('PUT')

                        <button type="submit" name="type" value="submit" class="btn bg-successDark font-bold">
                            {{ __('front/homePage.Save Edits') }}
                        </button>
                    </form>
                @elseif($order_data['payment_method'] == 2 ||
                    $order_data['payment_method'] == 3 ||
                    $order_data['payment_method'] == 4)
                    @if ($order_data['difference'] < 0)
                        <button type="button" data-modal-toggle="card-confirm" class="btn bg-successDark font-bold">
                            {{ __('front/homePage.Save Edits and Get Difference') }}
                        </button>
                    @elseif ($order_data['difference'] == 0)
                        <form
                            action="{{ route('front.orders.update', [$order_data['order_id'], $order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="equal" class="btn bg-successDark font-bold">
                                {{ __('front/homePage.Save Edits') }}
                            </button>
                        </form>
                    @elseif ($order_data['difference'] > 0)
                        <form
                            action="{{ route('front.orders.update', [$order_data['order_id'], $order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="pay" class="btn bg-successDark font-bold">
                                {{ __('front/homePage.Save Edits and Pay Difference') }}
                            </button>
                        </form>
                    @endif
                @endif

                <a href="{{ route('front.orders.edit', $order_data['order_id']) }}" class="btn bg-primary font-bold">
                    {{ __('front/homePage.Undo') }}
                </a>
            </div>
            {{-- Buttons :: End --}}
        </div>
    </div>

    {{-- Order Modals :: Start --}}
    {{-- Return to Card or Wallet Modal :: Start --}}
    <div id="card-confirm" tabindex="-1"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
        aria-modal="true" role="dialog">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4 rounded-t border-b">
                    <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('front/homePage.Return to Bank Account or Wallet') }}
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="card-confirm">
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
                    <p class="leading-relaxed text-gray-900 text-center">
                        {{ __('front/homePage.Bank Account or Wallet') }}
                    </p>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                    <form
                        action="{{ route('front.orders.update', [$order_data['order_id'], $order_data['new_order_id']]) }}"
                        method="POST" class="m-0">
                        @csrf
                        @method('PUT')

                        <button type="submit" name="type" value="wallet" class="btn bg-successDark font-bold">
                            {{ __('front/homePage.My Wallet on the Website') }}
                        </button>
                    </form>

                    @if ($order_data['payment_method'] == 4)
                        <form
                            action="{{ route('front.orders.update', [$order_data['order_id'], $order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="vodafone" class="btn bg-successDark font-bold">
                                {{ __('front/homePage.My Vodafone-cash Wallet') }}
                            </button>
                        </form>
                    @else
                        <form
                            action="{{ route('front.orders.update', [$order_data['order_id'], $order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="card" class="btn bg-successDark font-bold">
                                {{ __('front/homePage.Bank Account') }}
                            </button>
                        </form>
                    @endif

                    <button data-modal-toggle="card-confirm" type="button"
                        class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('front/homePage.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Return to Card or Wallet Modal :: End --}}
    {{-- Order Modals :: End --}}

@endsection

{{-- Extra Scripts --}}


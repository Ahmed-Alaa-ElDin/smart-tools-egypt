@extends('layouts.front.user_control_layout', ['titlePage' => __('front/homePage.Return Preview'), 'page' => 'orders'])

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
        <a href="{{ route('front.orders.return', $order_data['old_order_id']) }}">
            {{ __('front/homePage.Return Products') }}
        </a>
    </li>
    <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
        {{ __('front/homePage.Return Preview') }}
    </li>
@endsection

@section('sub-content')
    <div class="bg-white rounded-xl shadow col-span-12">
        {{-- ############## Title :: Start ############## --}}
        <div class="flex justify-between items-center">
            <h3 class="h5 text-center font-bold p-4 m-0">
                {{ __('front/homePage.Return Preview') }}
            </h3>
        </div>
        {{-- ############## Title :: End ############## --}}

        <hr>

        {{-- ############## Preview :: Start ############## --}}
        <div class="p-4 flex flex-col lg:flex-row gap-4 justify-center items-start">
            <div class="w-full">
                <div class="rounded-xl shadow p-4 bg-white overflow-hidden w-full">
                    {{-- Old Order Total --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Old order cost:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                            <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['old_order_total'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', number_format($order_data['old_order_total'], 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                    {{-- Returned Products Total --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Returned products prices:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                            <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['returned_price'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', number_format($order_data['returned_price'], 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                    {{-- Returned Products Total --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Returning fees:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                            <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['returning_fees'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', number_format($order_data['returning_fees'], 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                    <hr class="my-2">

                    {{-- Returned Total --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        @if ($order_data['return_subtotal'] != $order_data['return_total'])
                            <span class="text-sm font-bold"> {{ __('front/homePage.Subtotal :') }} </span>
                        @else
                            <span class="text-sm font-bold"> {{ __('front/homePage.Total:') }} </span>
                        @endif
                        <div
                            class="flex rtl:flex-row-reverse gap-1 @if ($order_data['return_subtotal'] > 0) text-primary @else text-successDark @endif">
                            <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', abs($order_data['return_subtotal']))[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', number_format(abs($order_data['return_subtotal']), 2))[1] ?? '00' }}</span>
                        </div>
                    </div>

                    {{-- Return to Wallet --}}
                    @if ($order_data['return_subtotal'] != $order_data['return_total'])
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.Will be returned to my wallet:') }}
                            </span>

                            <div class="flex flex-col justify-center items-end min-w-max">
                                {{-- Balance --}}
                                <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                                    <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                                    <span class="font-bold text-xl"
                                        dir="ltr">{{ number_format(explode('.', abs($order_data['returned_to_balance']))[0], 0, '.', '\'') }}</span>
                                    <span
                                        class="font-bold text-xs">{{ explode('.', number_format(abs($order_data['returned_to_balance']), 2))[1] ?? '00' }}</span>
                                </div>

                                {{-- Points --}}
                                <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                                    <span class="font-bold text-xl">
                                        {{ $order_data['returned_to_points'] }}
                                        <span class="text-xs">
                                            {{ trans_choice('front/homePage.Point/Points', $order_data['returned_to_points'], ['points' => $order_data['returned_to_points']]) }}
                                        </span>
                                    </span>
                                    &equiv;
                                    <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                                    <span class="font-bold text-xl" dir="ltr">
                                        {{ number_format(explode('.', abs($order_data['returned_to_points_egp']))[0], 0, '.', '\'') }}
                                    </span>
                                    <span class="font-bold text-xs">
                                        {{ explode('.', number_format(abs($order_data['returned_to_points_egp']), 2))[1] ?? '00' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr class="my-2">

                        {{-- Returned Total --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.Total:') }} </span>

                            <div
                                class="flex rtl:flex-row-reverse gap-1 @if ($order_data['return_total'] > 0) text-primary @else text-successDark @endif">
                                <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', abs($order_data['return_total']))[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', number_format(abs($order_data['return_total']), 2))[1] ?? '00' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-4">
                    <div class="rounded-xl shadow p-4 bg-white overflow-hidden w-full">
                        {{-- Payment Method --}}
                        <div class="font-bold flex gap-2 justify-between items-center px-4 py-1">
                            <span class="text-sm min-w-max"> {{ __('front/homePage.Payment Method:') }} </span>

                            <span class="text-gray-900">
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

                        {{-- Products Count Before Returning --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold">
                                {{ __('front/homePage.Number of products before returning:') }}
                            </span>

                            <span class="text-successDark font-bold">
                                {{ trans_choice('front/homePage.Item', $order_data['old_products_total_quantities'], ['item' => $order_data['old_products_total_quantities']]) }}
                            </span>
                        </div>

                        {{-- Products Count After Returning --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold">
                                {{ __('front/homePage.Number of products to be returned:') }}
                            </span>

                            <span class="text-primary font-bold">
                                {{ trans_choice('front/homePage.Item', $order_data['returned_products_total_quantities'], ['item' => $order_data['returned_products_total_quantities']]) }}
                            </span>
                        </div>
                    </div>

                    <div class="rounded-xl shadow p-4 bg-white overflow-hidden w-full">
                        {{-- Old Order Gift Points --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.Old order gift points:') }} </span>

                            <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                                <span class="font-bold text-xl">
                                    {{ $order_data['old_product_gift_points'] }}
                                    <span class="text-xs">
                                        {{ trans_choice('front/homePage.Point/Points', $order_data['old_product_gift_points'], ['points' => $order_data['old_product_gift_points']]) }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        {{-- Old Order Gift Points --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.You will lose:') }} </span>

                            <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                                <span class="font-bold text-xl">
                                    {{ $order_data['returned_points'] }}
                                    <span class="text-xs">
                                        {{ trans_choice('front/homePage.Point/Points', $order_data['returned_points'], ['points' => $order_data['returned_points']]) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center p-2 font-bold">
            <span>
                {!! __('front/homePage.Industrial Defect', [
                    'icon' =>
                        '<a href="https://wa.me/+2' .
                        config('settings.whatsapp_number') .
                        '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1"> <span class="text-sm">' .
                        config('settings.whatsapp_number') .
                        ' </a>',
                ]) !!}
            </span>
        </div>
        {{-- ############## Preview :: End ############## --}}

        <div>
            {{-- Buttons :: Start --}}
            <div class="p-2 flex justify-around items-center gap-2">


                @if ($order_data['return_total'] < 0)
                    <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}" method="POST"
                        class="m-0">
                        @csrf
                        @method('PUT')

                        <button type="submit" name="type" value="wallet" class="btn bg-successDark font-bold">
                            {{ __('front/homePage.Confirm and Refund to My Wallet') }}
                        </button>
                    </form>
                    @if ($order_data['payment_method'] == 1)
                        <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="cod"
                                class="btn border border-successDark text-successDark hover:bg-successDark hover:text-white active:bg-successDark active:text-white font-bold">
                                {{ __('front/homePage.Confirm and Return Money') }}
                            </button>
                        </form>
                    @elseif($order_data['payment_method'] == 2 || $order_data['payment_method'] == 3)
                        <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="card"
                                class="btn border border-successDark text-successDark hover:bg-successDark hover:text-white active:bg-successDark active:text-white font-bold">
                                {{ __('front/homePage.Confirm and Refund to Bank Account') }}
                            </button>
                        </form>
                    @elseif($order_data['payment_method'] == 4)
                        <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="vodafone"
                                class="btn border border-successDark text-successDark hover:bg-successDark hover:text-white active:bg-successDark active:text-white font-bold">
                                {{ __('front/homePage.Confirm and Refund to My Vodafone Cash Wallet') }}
                            </button>
                        </form>
                    @endif
                @else
                    <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                        method="POST" class="m-0">
                        @csrf
                        @method('PUT')

                        <button type="submit" name="type" value="cod"
                            class="btn border bg-successDark font-bold">
                            {{ __('front/homePage.Confirm') }}
                        </button>
                    </form>
                @endif

                <a href="{{ url()->previous() }}"
                    class="btn bg-primary font-bold">
                    {{ __('front/homePage.Undo') }}
                </a>
            </div>
            {{-- Buttons :: End --}}
        </div>
    </div>
@endsection

{{-- Extra Scripts --}}


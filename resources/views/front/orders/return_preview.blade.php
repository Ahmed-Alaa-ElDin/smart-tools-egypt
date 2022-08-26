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
                            <span class="text-sm font-bold"> {{ __('front/homePage.Total :') }} </span>
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

                            <div class="flex flex-col grow min-w-max">
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
                            <span class="text-sm font-bold"> {{ __('front/homePage.Total :') }} </span>

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
                            <span class="text-sm"> {{ __('front/homePage.Payment Method:') }} </span>

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
                                {{ trans_choice('front/homePage.Product', $order_data['old_products_total_quantities'], ['product' => $order_data['old_products_total_quantities']]) }}
                            </span>
                        </div>

                        {{-- Products Count After Returning --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold">
                                {{ __('front/homePage.Number of products to be returned:') }}
                            </span>

                            <span class="text-primary font-bold">
                                {{ trans_choice('front/homePage.Product', $order_data['returned_products_total_quantities'], ['product' => $order_data['returned_products_total_quantities']]) }}
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
            <div>
                <span>{{ __('front/homePage.Industrial Defect') }}</span>
            </div>
        </div>
        {{-- ############## Preview :: End ############## --}}

        <div>
            {{-- Buttons :: Start --}}
            <div class="p-2 flex justify-around items-center gap-2">

                @if ($order_data['returned_products_total_quantities'] == $order_data['old_products_total_quantities'])
                    <form method="POST"
                        action="{{ route('front.orders.return', [
                            'old_order_id' => $order_data['old_order_id'],
                            'new_order_id' => $order_data['new_order_id'],
                        ]) }}"
                        class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn text-red-600 bg-white font-bold focus:outline-none border-2 border-red-200 text-sm px-5 py-2.5 hover:text-red-800 focus:z-10">
                            {{ __('front/homePage.Return Total Order') }}
                        </button>
                    </form>
                @else
                    @if ($order_data['return_total'] < 0)
                        <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
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
                                    class="btn bg-successDark font-bold">
                                    {{ __('front/homePage.Confirm and Return Money') }}
                                </button>
                            </form>
                        @elseif($order_data['payment_method'] == 2 || $order_data['payment_method'] == 3)
                            <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                                method="POST" class="m-0">
                                @csrf
                                @method('PUT')

                                <button type="submit" name="type" value="card"
                                    class="btn bg-successDark font-bold">
                                    {{ __('front/homePage.Confirm and Refund to Bank Account') }}
                                </button>
                            </form>
                        @elseif($order_data['payment_method'] == 4)
                            <form action="{{ route('front.orders.return-confirm', [$order_data['new_order_id']]) }}"
                                method="POST" class="m-0">
                                @csrf
                                @method('PUT')

                                <button type="submit" name="type" value="vodafone"
                                    class="btn bg-successDark font-bold">
                                    {!! __('front/homePage.Confirm and Refund to My Vodafone Cash Wallet', [
                                        'icon' => '<a href="https://wa.me/+2' . config('constants.constants.WHATSAPP_NUMBER') . '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1"> <span class="text-sm">' . config('constants.constants.WHATSAPP_NUMBER') . '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                                    ]) !!}
                                </button>
                            </form>
                        @endif
                    @endif
                @endif

                <a href="{{ route('front.orders.return', $order_data['old_order_id']) }}"
                    class="btn bg-primary font-bold">
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
                        {{ __('front/homePage.Return to Card or Wallet') }}
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
                        {{ __('front/homePage.Card or Wallet') }}
                    </p>
                </div>
                <!-- Modal footer -->
                {{-- <div class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                    <form
                        action="{{ route('front.orders.update', [$order_data['new_order_id']]) }}"
                        method="POST" class="m-0">
                        @csrf
                        @method('PUT')

                        <button type="submit" name="type" value="wallet" class="btn bg-successDark font-bold">
                            {{ __('front/homePage.My Wallet on the Website') }}
                        </button>
                    </form>

                    @if ($order_data['payment_method'] == 4)
                        <form
                            action="{{ route('front.orders.update', [$order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="vodafone" class="btn bg-successDark font-bold">
                                {{ __('front/homePage.My Vodafone-cash Wallet') }}
                            </button>
                        </form>
                    @else
                        <form
                            action="{{ route('front.orders.update', [$order_data['new_order_id']]) }}"
                            method="POST" class="m-0">
                            @csrf
                            @method('PUT')

                            <button type="submit" name="type" value="card" class="btn bg-successDark font-bold">
                                {{ __('front/homePage.Card') }}
                            </button>
                        </form>
                    @endif

                    <button data-modal-toggle="card-confirm" type="button"
                        class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('front/homePage.Cancel') }}
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- Return to Card or Wallet Modal :: End --}}
    {{-- Order Modals :: End --}}
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

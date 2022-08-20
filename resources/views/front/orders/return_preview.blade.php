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
        <a href="{{ route('front.orders.return', $order_data['order_id']) }}">
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

                        <div class="flex rtl:flex-row-reverse gap-1 text-gray-900">
                            <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-xl"
                                dir="ltr">{{ number_format(explode('.', $order_data['old_order_total'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-xs">{{ explode('.', $order_data['old_order_total'])[1] ?? '00' }}</span>
                        </div>
                    </div>

                    {{-- Old Order Number of Products --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Old order number of products:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-gray-900">
                            <span class="font-bold text">
                                {{ trans_choice('front/homePage.Product', $order_data['old_products_total_quantities'], ['product' => $order_data['old_products_total_quantities']]) }}
                            </span>
                        </div>
                    </div>

                    {{-- Old Order Gift Points --}}
                    <div class="flex justify-between items-center gap-1 px-4 py-1">
                        <span class="text-sm font-bold"> {{ __('front/homePage.Old order gift points:') }} </span>

                        <div class="flex rtl:flex-row-reverse gap-1 text-gray-900">
                            <span class="font-bold text-xl">
                                {{ $order_data['old_product_gift_points'] }}
                            </span>
                        </div>
                    </div>

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
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-4">
                    <div class="rounded-xl shadow p-4 bg-white overflow-hidden w-full">
                        {{-- Price of returned products --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.Price of returned products:') }} </span>

                            <div class="flex rtl:flex-row-reverse gap-1 text-gray-900">
                                <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                                <span class="font-bold text-xl"
                                    dir="ltr">{{ number_format(explode('.', $order_data['returned_price'])[0], 0, '.', '\'') }}</span>
                                <span
                                    class="font-bold text-xs">{{ explode('.', $order_data['returned_price'])[1] ?? '00' }}</span>
                            </div>
                        </div>

                        {{-- Old Order Number of Products --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.Old order number of products:') }}
                            </span>

                            <div class="flex rtl:flex-row-reverse gap-1 text-gray-900">
                                <span class="font-bold text">
                                    {{ trans_choice('front/homePage.Product', $order_data['old_products_total_quantities'], ['product' => $order_data['old_products_total_quantities']]) }}
                                </span>
                            </div>
                        </div>

                        {{-- Old Order Gift Points --}}
                        <div class="flex justify-between items-center gap-1 px-4 py-1">
                            <span class="text-sm font-bold"> {{ __('front/homePage.Old order gift points:') }} </span>

                            <div class="flex rtl:flex-row-reverse gap-1 text-gray-900">
                                <span class="font-bold text-xl">
                                    {{ $order_data['old_product_gift_points'] }}
                                </span>
                            </div>
                        </div>

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
                    </div>
                </div>


            </div>
        </div>
        {{-- ############## Preview :: End ############## --}}

        <div>
            {{-- Buttons :: Start --}}
            <div class="p-2 flex justify-around items-center gap-2">

                {{-- @if ($order_data['products_total_quantities'] == 0)
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
                </a> --}}
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

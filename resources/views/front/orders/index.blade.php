@extends('layouts.front.user_control_layout', ['titlePage' => __('front/homePage.My Orders'), 'page' => 'orders'])

@section('breadcrumb')
    <li class="breadcrumb-item hover:text-primary">
        <a href="{{ route('front.homepage') }}">
            {{ __('front/homePage.Homepage') }}
        </a>
    </li>
    <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
        {{ __('front/homePage.My Orders') }}
    </li>
@endsection

@section('sub-content')
    <div class="container col-span-12">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 flex flex-col gap-5 self-start">

                {{-- ############## My Orders :: Start ############## --}}
                <div class="bg-white rounded-xl overflow-hidden">
                    {{-- ############## Title :: Start ############## --}}
                    <div class="flex justify-between items-center">
                        <h3 class="h5 text-center font-bold p-4 m-0">
                            {{ __('front/homePage.My Orders') }}
                        </h3>
                    </div>
                    {{-- ############## Title :: End ############## --}}

                    <hr>

                    <div class="p-7 flex flex-col justify-center items-center gap-3">
                        {{-- Order List :: Start --}}
                        @forelse ($orders as $order)
                            @if (!in_array($order->status_id, [1, 7, 15]))
                                {{-- Order :: Start --}}
                                <div class="rounded overflow-hidden shadow-lg border border-gray-200 w-full">
                                    {{-- Order Header --}}
                                    <div
                                        class="bg-gray-100 border-b border-gray-200 p-3 flex flex-col justify-center items-center lg:flex-row lg:justify-between lg:items-center gap-2">
                                        <div class="flex justify-start items-center gap-4">
                                            {{-- Creation Date --}}
                                            <div class="flex flex-col justify-center items-center gap-1">
                                                <span class="text-xs font-bold"> {{ __('front/homePage.Creation Date') }}
                                                </span>
                                                <span class="text-sm">
                                                    {{ $order->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>

                                            {{-- Order Total --}}
                                            <div class="flex flex-col justify-center items-center gap-1">
                                                <span class="text-xs font-bold"> {{ __('front/homePage.Total') }} </span>
                                                <div class="flex rtl:flex-row-reverse gap-1 text-sm">
                                                    <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                                    <span class=""
                                                        dir="ltr">{{ number_format(abs($order->total), 2, '.', '\'') }}</span>
                                                </div>
                                            </div>

                                            {{-- Payment Method --}}
                                            <div class="flex flex-col justify-center items-center gap-1 min-w-max">
                                                <span class="text-xs font-bold"> {{ __('front/homePage.Payment Method') }}
                                                </span>
                                                <span class="text-sm">
                                                    {{ $order->payment_method == 1
                                                        ? __('front/homePage.Cash on delivery (COD)')
                                                        : ($order->payment_method == 2
                                                            ? __('front/homePage.Credit / Debit Card')
                                                            : ($order->payment_method == 3
                                                                ? __('front/homePage.Installment')
                                                                : ($order->payment_method == 4
                                                                    ? __('front/homePage.Vodafone Cash')
                                                                    : ''))) }}
                                                </span>
                                            </div>

                                            {{-- Order Status --}}
                                            <div class="flex flex-col justify-center items-center gap-1 min-w-max">
                                                <span
                                                    class="text-xs py-1 px-2 shadow-inner rounded-xl font-bold {{ in_array($order->status_id, [1, 2, 14, 15, 16])
                                                        ? 'bg-yellow-100 text-yellow-900'
                                                        : (in_array($order->status_id, [3, 45, 12])
                                                            ? 'bg-green-100 text-green-900'
                                                            : (in_array($order->status_id, [4, 5, 6])
                                                                ? 'bg-blue-100 text-blue-900'
                                                                : (in_array($order->status_id, [8, 9, 13])
                                                                    ? 'bg-red-100 text-red-900'
                                                                    : 'bg-blue-100 text-blue-900'))) }} ">
                                                    {{ $order->status->name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex flex-wrap justify-around items-center gap-1">

                                                {{-- Go To Paymob Iframe --}}
                                                @if ($order->status_id == 2 && ($order->payment_method == 2 || $order->payment_method == 3))
                                                    <a href="{{ route('front.orders.payment', $order->id) }}"
                                                        class="btn btn-sm bg-successDark font-bold">
                                                        {{ __('front/homePage.Go to Payment') }}
                                                    </a>

                                                    {{-- Popup Vodafone cash pay warning --}}
                                                @elseif ($order->status_id == 2 && $order->payment_method == 4)
                                                    <button data-modal-toggle="payVodafonCashModal" type="button"
                                                        class="btn btn-sm bg-successDark font-bold">
                                                        {{ __('front/homePage.Go to Payment') }}
                                                    </button>
                                                @endif

                                                {{-- Refund --}}
                                                @if ($order->status_id == 14)
                                                    <button data-modal-toggle="getMoneyModal" type="button"
                                                        class="btn btn-sm bg-successDark font-bold">
                                                        {{ __('front/homePage.Refund') }}
                                                    </button>
                                                @endif

                                                {{-- Track Order --}}
                                                @if (!in_array($order->status_id, [1, 2, 9]))
                                                    <a href="{{ route('front.orders.track', $order->id) }}"
                                                        class="btn btn-sm bg-secondary font-bold">
                                                        {{ __('front/homePage.Track Your Order') }}
                                                    </a>
                                                @endif

                                                {{-- Invoice Request --}}
                                                @if (auth()->user()->invoiceRequests->where('order_id', $order->id)->count() == 0 && !in_array($order->status_id, [8, 9, 7, 17, 18, 19]))
                                                    <form class="inline m-0"
                                                        action="{{ route('front.invoice-request.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                                        <button type="submit" class="btn btn-sm bg-secondary font-bold">
                                                            {{ __('front/homePage.Invoice Request') }}
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Edit Order --}}
                                                @if (in_array($order->status_id, [1, 2, 3, 4, 5, 12, 13]))
                                                    <button data-modal-toggle="editOrCancelOrder-{{ $order->id }}"
                                                        type="button" class="btn btn-sm bg-primary font-bold">
                                                        {{ __('front/homePage.Edit/Cancel Order') }}
                                                    </button>
                                                @endif

                                                {{-- Return Order --}}
                                                @if ($order->can_returned)
                                                    <button class="btn btn-sm bg-primary font-bold" type="button"
                                                        data-modal-toggle="returnOrderOrProduct-{{ $order->id }}">
                                                        {{ __('front/homePage.Return Order/Product') }}
                                                    </button>
                                                @endif

                                                {{-- Cancel Order Return --}}
                                                @if (in_array($order->status_id, [17]))
                                                    <form class="inline m-0"
                                                        action="{{ route('front.orders.return-cancel',$order->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm bg-primary font-bold"
                                                            type="button">
                                                            {{ __('front/homePage.Cancel the Return Request') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Order Header --}}

                                    {{-- Order Body --}}
                                    <div class="bg-white p-2">

                                        @forelse ($order->products as $product)
                                            {{-- Product :: Start --}}
                                            <x-front.order-product :product="$product" />
                                            {{-- Product :: End --}}

                                            @if (!$loop->last)
                                                <hr class="border-b border-gray-200">
                                            @endif
                                        @empty
                                            <div class="text-center">
                                                <span class="text-sm font-bold">
                                                    {{ __('front/homePage.No products in this order') }}
                                                </span>
                                            </div>
                                        @endforelse
                                    </div>
                                    {{-- Order Body --}}
                                </div>
                                {{-- Order :: End --}}

                                {{-- Order Modals :: Start --}}
                                {{-- Return Order Or Product Modal :: Start --}}
                                <div id="returnOrderOrProduct-{{ $order->id }}" tabindex="-1"
                                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
                                    aria-modal="true" role="dialog">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-4 rounded-t border-b">
                                                <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ __('front/homePage.Return Order Or Product Request') }}
                                                </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="returnOrderOrProduct-{{ $order->id }}">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                    {{ __('front/homePage.Order Or Product') }}
                                                </p>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                                                <button data-modal-toggle="returnOrderConfirm-{{ $order->id }}"
                                                    type="button"
                                                    class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('front/homePage.All Order Products') }}
                                                </button>

                                                <a type="button" href="{{ route('front.orders.return', $order->id) }}"
                                                    class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('front/homePage.Specific Product') }}
                                                </a>

                                                <button data-modal-toggle="returnOrderOrProduct-{{ $order->id }}"
                                                    type="button"
                                                    class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{ __('front/homePage.Cancel') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Return Order Or Product Modal :: End --}}

                                {{-- Return Order Confirm :: Start --}}
                                <div id="returnOrderConfirm-{{ $order->id }}" tabindex="-1"
                                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
                                    aria-modal="true" role="dialog">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-4 rounded-t border-b">
                                                <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ __("front/homePage.Return All Order's Products Confirm") }}
                                                </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="returnOrderConfirm-{{ $order->id }}">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                    {{ __("front/homePage.Are you sure, you want to return all order's products?") }}
                                                </p>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                                                <form action="{{ route('front.orders.return-calc', $order->id) }}"
                                                    method="POST" class="m-0">
                                                    @csrf

                                                    <button type="submit" name="type" value="return"
                                                        class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                        {{ __('front/homePage.Yes') }}
                                                    </button>
                                                </form>
                                                <button data-modal-toggle="returnOrderConfirm-{{ $order->id }}"
                                                    type="button"
                                                    class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{ __('front/homePage.No') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Return Order Confirm :: End --}}

                                {{-- Edit Or Cancel Order Modal :: Start --}}
                                <div id="editOrCancelOrder-{{ $order->id }}" tabindex="-1"
                                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
                                    aria-modal="true" role="dialog">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-4 rounded-t border-b">
                                                <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ __('front/homePage.Edit Or Cancel Order Request') }}
                                                </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="editOrCancelOrder-{{ $order->id }}">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                    {{ __('front/homePage.Edit Or Cancel Order') }}
                                                </p>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                                                <a href="{{ route('front.orders.edit', ['order_id' => $order->id]) }}"
                                                    class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('front/homePage.Edit Order') }}
                                                </a>

                                                <button data-modal-toggle="cancelOrderConfirm-{{ $order->id }}"
                                                    type="button"
                                                    class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('front/homePage.Cancel Order') }}
                                                </button>

                                                <button data-modal-toggle="editOrCancelOrder-{{ $order->id }}"
                                                    type="button"
                                                    class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{ __('front/homePage.Cancel') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Edit Or Cancel Order Modal :: End --}}

                                {{-- Cancel Order Confirm :: Start --}}
                                <div id="cancelOrderConfirm-{{ $order->id }}" tabindex="-1"
                                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
                                    aria-modal="true" role="dialog">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-4 rounded-t border-b">
                                                <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ __('front/homePage.Cancel Order Confirm') }}
                                                </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="cancelOrderConfirm-{{ $order->id }}">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                    {{ __('front/homePage.Are you sure, you want to cancel the order?') }}
                                                </p>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                                                <form method="POST"
                                                    action="{{ route('front.orders.cancel', ['order_id' => $order->id]) }}"
                                                    class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                        {{ __('front/homePage.Yes') }}
                                                    </button>
                                                </form>
                                                <button data-modal-toggle="cancelOrderConfirm-{{ $order->id }}"
                                                    type="button"
                                                    class="btn bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{ __('front/homePage.No') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Cancel Order Confirm :: End --}}

                                {{-- Pay via Vodafone Cash Warning Modal :: Start --}}
                                <div id="payVodafonCashModal" tabindex="-1"
                                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
                                    aria-modal="true" role="dialog">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-4 rounded-t border-b">
                                                <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ __('front/homePage.Pay via vodafone-cash') }} </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="payVodafonCashModal">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                    {!! __('front/homePage.Vodafone Cash Confirm', [
                                                        'icon' =>
                                                            '<a href="https://wa.me/+2' .
                                                            config('constants.constants.WHATSAPP_NUMBER') .
                                                            '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1 font-bold">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <span class="text-sm">' .
                                                            config('constants.constants.WHATSAPP_NUMBER') .
                                                            '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                                                    ]) !!}
                                                </p>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                                                <button data-modal-toggle="payVodafonCashModal" type="button"
                                                    class="btn bg-successDark focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{ __('front/homePage.Done') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Pay via Vodafone Cash Warning Modal :: End --}}

                                {{-- Refund via Vodafone Cash Warning Modal :: Start --}}
                                <div id="getMoneyModal" tabindex="-1"
                                    class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center hidden"
                                    aria-modal="true" role="dialog">
                                    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <!-- Modal header -->
                                            <div class="flex justify-between items-start p-4 rounded-t border-b">
                                                <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ __('front/homePage.Refund via vodafone-cash') }} </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                                                    data-modal-toggle="getMoneyModal">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                                                    {!! __('front/homePage.Vodafone Cash Refund Confirm', [
                                                        'icon' =>
                                                            '<a href="https://wa.me/+2' .
                                                            config('constants.constants.WHATSAPP_NUMBER') .
                                                            '" target="_blank" class="inline-flex items-center justify-center gap-1 bg-whatsapp text-white rounded-full px-2 py-1 m-1 font-bold">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <span class="text-sm">' .
                                                            config('constants.constants.WHATSAPP_NUMBER') .
                                                            '</span> <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 1024 1024"> <path fill="currentColor" d="M713.5 599.9c-10.9-5.6-65.2-32.2-75.3-35.8c-10.1-3.8-17.5-5.6-24.8 5.6c-7.4 11.1-28.4 35.8-35 43.3c-6.4 7.4-12.9 8.3-23.8 2.8c-64.8-32.4-107.3-57.8-150-131.1c-11.3-19.5 11.3-18.1 32.4-60.2c3.6-7.4 1.8-13.7-1-19.3c-2.8-5.6-24.8-59.8-34-81.9c-8.9-21.5-18.1-18.5-24.8-18.9c-6.4-.4-13.7-.4-21.1-.4c-7.4 0-19.3 2.8-29.4 13.7c-10.1 11.1-38.6 37.8-38.6 92s39.5 106.7 44.9 114.1c5.6 7.4 77.7 118.6 188.4 166.5c70 30.2 97.4 32.8 132.4 27.6c21.3-3.2 65.2-26.6 74.3-52.5c9.1-25.8 9.1-47.9 6.4-52.5c-2.7-4.9-10.1-7.7-21-13z" /> <path fill="currentColor" d="M925.2 338.4c-22.6-53.7-55-101.9-96.3-143.3c-41.3-41.3-89.5-73.8-143.3-96.3C630.6 75.7 572.2 64 512 64h-2c-60.6.3-119.3 12.3-174.5 35.9c-53.3 22.8-101.1 55.2-142 96.5c-40.9 41.3-73 89.3-95.2 142.8c-23 55.4-34.6 114.3-34.3 174.9c.3 69.4 16.9 138.3 48 199.9v152c0 25.4 20.6 46 46 46h152.1c61.6 31.1 130.5 47.7 199.9 48h2.1c59.9 0 118-11.6 172.7-34.3c53.5-22.3 101.6-54.3 142.8-95.2c41.3-40.9 73.8-88.7 96.5-142c23.6-55.2 35.6-113.9 35.9-174.5c.3-60.9-11.5-120-34.8-175.6zm-151.1 438C704 845.8 611 884 512 884h-1.7c-60.3-.3-120.2-15.3-173.1-43.5l-8.4-4.5H188V695.2l-4.5-8.4C155.3 633.9 140.3 574 140 513.7c-.4-99.7 37.7-193.3 107.6-263.8c69.8-70.5 163.1-109.5 262.8-109.9h1.7c50 0 98.5 9.7 144.2 28.9c44.6 18.7 84.6 45.6 119 80c34.3 34.3 61.3 74.4 80 119c19.4 46.2 29.1 95.2 28.9 145.8c-.6 99.6-39.7 192.9-110.1 262.7z" /> </svg> </a>',
                                                    ]) !!}
                                                </p>
                                            </div>
                                            <!-- Modal footer -->
                                            <div
                                                class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                                                <button data-modal-toggle="getMoneyModal" type="button"
                                                    class="btn bg-successDark focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    {{ __('front/homePage.Done') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Refund via Vodafone Cash Warning Modal :: End --}}

                                {{-- Order Modal :: End --}}
                            @endif
                        @empty
                            <div class="flex flex-col justify-center items-center gap-3">
                                <span class="font-bold text-lg">
                                    {{ __("front/homePage.You didn't make any orders until now") }}
                                </span>
                                <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                                    {{ __('front/homePage.Continue Shopping') }}
                                </a>
                            </div>
                        @endforelse
                        {{-- Order List :: End --}}
                        <div class="w-full">
                            {{ $orders->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>
                {{-- ############## My Orders :: End ############## --}}
            </div>
        </div>

        {{-- todo: Other Product Suggestions (Similar Products, Related Products, etc.) in the Cart Page (if any) --}}
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

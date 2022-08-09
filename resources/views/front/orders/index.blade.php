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
{{-- @dd(Session::all()) --}}
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
                            @if ($order->status_id != 1 && $order->status_id != 10)
                                {{-- Order :: Start --}}
                                <div class="rounded overflow-hidden shadow-lg border border-gray-200 w-full">
                                    {{-- Order Header --}}
                                    <div
                                        class="bg-gray-100 border-b border-gray-200 p-3 flex flex-col justify-center items-center lg:flex-row lg:justify-between lg:items-center gap-2">
                                        <div class="flex justify-start items-center gap-4">
                                            {{-- Order Placed --}}
                                            <div class="flex flex-col justify-center items-center gap-1">
                                                <span class="text-xs font-bold"> {{ __('front/homePage.Order Placed') }}
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
                                                        dir="ltr">{{ number_format($order->total, 2, '.', '\'') }}</span>
                                                </div>
                                            </div>

                                            {{-- Payment Method --}}
                                            <div class="flex flex-col justify-center items-center gap-1">
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
                                            <div class="flex flex-col justify-center items-center gap-1">
                                                <span
                                                    class="text-xs py-1 px-2 shadow-inner rounded-xl font-bold {{ $order->status_id <= 2
                                                        ? 'bg-yellow-100 text-yellow-900'
                                                        : ($order->status_id == 3 || $order->status_id == 7
                                                            ? 'bg-green-100 text-green-900'
                                                            : ($order->status_id == 4 || $order->status_id == 5 || $order->status_id == 6
                                                                ? 'bg-blue-100 text-blue-900'
                                                                : ($order->status_id == 8
                                                                    ? 'bg-red-100 text-red-900'
                                                                    : 'bg-green-100 text-green-900'))) }} ">
                                                    {{ __('front/homePage.' . $order->status->name) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div>
                                            {{-- todo :: Order Actions --}}
                                            <div class="flex flex-wrap justify-around items-center gap-1">
                                                @if ($order->status_id > 2 && $order->status_id != 9)
                                                    <a href="#" class="btn btn-sm bg-secondary font-bold">
                                                        {{ __('front/homePage.Track Your Order') }}
                                                    </a>
                                                @endif

                                                @if (auth()->user()->invoiceRequests->where('order_id', $order->id)->count() == 0 && $order->status_id != 8)
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

                                                @if ($order->status_id <= 5)
                                                    <button data-modal-toggle="editOrCancelOrder-{{ $order->id }}"
                                                        type="button" class="btn btn-sm bg-primary font-bold">
                                                        {{ __('front/homePage.Edit/Cancel Order') }}
                                                    </button>
                                                @endif

                                                @if ($order->can_returned)
                                                    <button class="btn btn-sm bg-primary font-bold" type="button"
                                                        data-modal-toggle="returnOrderOrProduct-{{ $order->id }}">
                                                        {{ __('front/homePage.Return Order/Product') }}
                                                    </button>
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

                                                {{-- todo :: return product request --}}
                                                <a type="button" href="#"
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
                                                {{-- todo :: return product request --}}
                                                <a data-modal-toggle="returnOrderConfirm-{{ $order->id }}"
                                                    type="button"
                                                    class="btn text-gray-600 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                                                    {{ __('front/homePage.Yes') }}
                                                </a>
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
                                                    action="{{ route('front.order.cancel', ['order_id' => $order->id]) }}"
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

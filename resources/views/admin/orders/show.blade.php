@extends('layouts.admin.admin', [
    'activeSection' => 'Orders',
    'activePage' => 'All Orders',
    'titlePage' => __("admin/ordersPages.Order's Details"),
])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/ordersPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.orders.index') }}">{{ __('admin/ordersPages.All Orders') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __("admin/ordersPages.Order's Details") }}</li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex flex-wrap justify-between">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class=""> {{ __("admin/ordersPages.Here you can see order's details") }}</p>
                                </div>

                                {{-- Add New Order --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                        class="btn btn-sm bg-yellow-400 hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-700 font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            edit
                                        </span>
                                        {{ __("admin/ordersPages.Edit Order's Details") }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Order's Summary :: Start --}}
                            <div class="overflow-auto">
                                <x-admin.orders.order-summary :order="$order" />
                            </div>
                            {{-- Order's Summary :: End --}}

                            <hr class="my-2">

                            {{-- Order's Products :: Start --}}
                            <x-admin.orders.order-products-list :products="$order->products" />
                            {{-- Order's Products :: End --}}

                            <hr class="my-2">

                            <div class="grid md:grid-cols-3 gap-3 justify-between items-start">
                                {{-- Order's Statuses :: Start --}}
                                <div class="overflow-auto md:col-span-1">
                                    <x-admin.orders.order-track-view :statuses="$order->statuses" />
                                </div>
                                {{-- Order's Statuses :: End --}}

                                {{-- Order's Transactions :: Start --}}
                                <div class="overflow-auto md:col-span-2">
                                    <x-admin.orders.order-payment-history-view :payments="$order->payments" />
                                    {{-- Order's Transactions :: End --}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // #### Edit Order Status ####
        window.addEventListener('swalSelectBox', function(e) {
            Swal.fire({
                title: e.detail.title,
                input: 'select',
                inputOptions: JSON.parse(e.detail.data),
                inputValue: e.detail.selected,
                customClass: {
                    input: 'role-grapper rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300',
                },
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,

            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit(e.detail.method, e.detail.id, result.value);
                }
            });
        });
        // #### Edit Order Status ####
    </script>
@endpush

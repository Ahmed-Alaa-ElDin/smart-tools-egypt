@extends('layouts.admin.admin', ['activeSection' => 'Orders', 'activePage' => 'All Orders', 'titlePage' => __('admin/ordersPages.Payment History')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.dashboard') }}">
                            {{ __('admin/ordersPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.orders.index') }}">
                            {{ __('admin/ordersPages.All Orders') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/ordersPages.Payment History') }}</li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex justify-between">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __('admin/ordersPages.Here you can view the payment details for order #', ['order_id' => $order->id]) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            @livewire('admin.orders.payment-history', ['order' => $order])
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

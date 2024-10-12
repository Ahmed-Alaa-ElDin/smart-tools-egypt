@extends('layouts.admin.admin', ['activeSection' => 'Orders', 'activePage' => 'Shipped Orders', 'titlePage' => __('admin/ordersPages.Shipped Orders')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/ordersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/ordersPages.Shipped Orders') }}</li>
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
                                    <p class=""> {{ __('admin/ordersPages.Here you can manage shipped orders') }}</p>
                                </div>

                                {{-- Create New Order --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.orders.create') }}"
                                        class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/ordersPages.Add Order') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.orders.orders-datatable',[
                                'type' => 'shipped_orders'
                            ])
                            {{-- Datatable End --}}
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
                    Livewire.dispatch(e.detail.method, {
                        'id': e.detail.id,
                        'status_id': result.value
                    });
                }
            });
        });
        // #### Edit Order Status ####
    </script>
@endpush

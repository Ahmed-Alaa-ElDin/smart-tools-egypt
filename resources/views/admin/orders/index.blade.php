@extends('layouts.admin.admin', ['activeSection' => 'Orders', 'activePage' => 'All Orders', 'titlePage'
=> __('admin/ordersPages.All Orders')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/ordersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/ordersPages.All Orders') }}
                    </li>
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
                                        {{ __('admin/ordersPages.Here you can manage orders') }}</p>
                                </div>

                                {{-- Add New Order Button --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.orders.create') }}"
                                        class="btn btn-sm bg-green-600 hover:bg-green-700 focus:bg-green-600 active:bg-green-600 font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/ordersPages.Add Order') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.orders.orders-datatable')
                            {{-- Datatable End --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

{{-- Extra Styles --}}
@push('css')
    @livewireStyles
@endpush

{{-- Extra Scripts --}}
@push('js')
    @livewireScripts

    <script>
        // #### Order Sweetalert ####
        window.addEventListener('swalConfirm', function(e) {

            Swal.fire({
                icon: 'warning',
                text: e.detail.text,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: 'gray',
                confirmButtonColor: e.detail.confirmButtonColor,
                focusDeny: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit(e.detail.func, e.detail.order_id);
                }
            });
        });

        window.addEventListener('swalDone', function(e) {
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
        // #### Order Sweetalert ####
    </script>
@endpush

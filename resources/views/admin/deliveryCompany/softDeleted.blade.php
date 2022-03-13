@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => '', 'titlePage' =>
__('admin/deliveriesPages.Deleted Delivery Companies')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.deliveries.index') }}">{{ __('admin/deliveriesPages.Delivery Companies') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/deliveriesPages.Deleted Delivery Companies') }}</li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-12 ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __('admin/deliveriesPages.Here you can Restore / Permanently delete delivery companies') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Data Table Start --}}
                            @livewire('admin.deliveries.deleted-delivery-companies-datatable')
                            {{-- Data Table End --}}

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
        // #### Delivery Force Delete ####
        window.addEventListener('swalConfirm', function(e) {
            Swal.fire({
                icon: e.detail.icon,
                text: e.detail.text,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: e.detail.denyButtonColor,
                confirmButtonColor: e.detail.confirmButtonColor,
                focusDeny: e.detail.focusDeny,
                showDenyButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit(e.detail.method, e.detail.delivery_id);
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
        // #### Delivery Force Delete ####


        // #### Restore ####
        window.addEventListener('swalRestore', function(e) {
            Swal.fire({
                icon: 'warning',
                text: e.detail.text,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: 'gray',
                confirmButtonColor: 'green',
                focusDeny: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('restoreDelivery', e.detail.delivery_id);
                }
            });
        });

        window.addEventListener('swalDeliveryRestored', function(e) {
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
        // #### Restore ####
    </script>
@endpush

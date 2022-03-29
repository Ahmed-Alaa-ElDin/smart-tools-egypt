@extends('layouts.admin.admin', ['activeSection' => 'Products', 'activePage' => 'All Products', 'titlePage'
=> __('admin/productsPages.All Products')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/productsPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/productsPages.All Products') }}
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
                                        {{ __('admin/productsPages.Here you can manage products') }}</p>
                                </div>

                                {{-- Add New Product Button --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.products.create') }}"
                                        class="btn btn-sm bg-green-600 hover:bg-green-700 focus:bg-green-600 active:bg-green-600 font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/productsPages.Add Product') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Data Table Start --}}
                            @livewire('admin.products.products-datatable')
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
        // #### Product Soft Delete ####
        window.addEventListener('swalConfirmSoftDelete', function(e) {
            Swal.fire({
                icon: 'warning',
                text: e.detail.text,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: 'gray',
                confirmButtonColor: 'red',
                focusDeny: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('softDeleteProduct', e.detail.product_id);
                }
            });
        });

        window.addEventListener('swalProductDeleted', function(e) {
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
        // #### Product Soft Delete ####

        // #### Delivery Activation / Deactivation ####
        window.addEventListener('swalProductPublished', function(e) {
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
        // #### Delivery Activation / Deactivation ####
    </script>
@endpush

@extends('layouts.admin.admin', ['activeSection' => 'Categories System', 'activePage' => '', 'titlePage'
=> __('admin/productsPages.Deleted Subcategories')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/productsPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.subcategories.index') }}">{{ __('admin/productsPages.Subcategories') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/productsPages.Deleted Subcategories') }}
                    </li>
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
                                        {{ __('admin/productsPages.Here you can Restore / Permanently delete subcategories') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Datatable Start --}}
                            @livewire('admin.subcategories.deleted-subcategories-datatable')
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
        // #### Subcategories Sweetalert ####
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
                    Livewire.emit(e.detail.func, e.detail.subcategory_id);
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
        // #### Subcategories Sweetalert ####
    </script>
@endpush

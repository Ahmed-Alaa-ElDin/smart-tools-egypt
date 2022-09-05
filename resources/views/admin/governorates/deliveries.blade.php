@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => '', 'titlePage' => __("admin/deliveriesPages.'s Delivery Companies", ['name' => $governorate->name])])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.governorates.index') }}">{{ __('admin/deliveriesPages.All Governorates') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __("admin/deliveriesPages.'s Delivery Companies", ['name' => $governorate->name]) }}
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
                                <div class="ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __("admin/deliveriesPages.Here you can manage governorate's delivery companies") }}
                                    </p>
                                </div>

                                {{-- Add New Delivery Button --}}
                                @can('Add Delivery')
                                    <div class="ltr:text-right rtl:text-left">
                                        <a href="{{ route('admin.deliveries.create') }}"
                                            class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                            <span class="material-icons rtl:ml-1 ltr:mr-1">
                                                add
                                            </span>
                                            {{ __('admin/deliveriesPages.Add Delivery Company') }}</a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.deliveries.delivery-companies-datatable', ['governorate_id' => $governorate->id])
                            {{-- Datatable End --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        // #### Delivery Activation / Deactivation ####
        window.addEventListener('swalDeliveryActivated', function(e) {
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

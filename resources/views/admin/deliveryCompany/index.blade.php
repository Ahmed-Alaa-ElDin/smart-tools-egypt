@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => 'Delivery Companies', 'titlePage'
=>
__('admin/deliveriesPages.Delivery Companies')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/deliveriesPages.Delivery Companies') }}</li>
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
                                        {{ __('admin/deliveriesPages.Here you can manage delivery companies') }}</p>
                                </div>

                                {{-- Add New Delivery Company Button --}}
                                @can('Add Delivery')
                                    <div class="ltr:text-right rtl:text-left">
                                        <a href="{{ route('admin.deliveries.create') }}"
                                            class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                            <span class="material-icons rtl:ml-1 ltr:mr-1">
                                                add
                                            </span>
                                            {{ __('admin/deliveriesPages.Add Company') }}</a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.deliveries.delivery-companies-datatable')
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
 @if (session('locale' == 'en'))
                    position: 'top-left',
                @else
                    position: 'top-right',
                @endif                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
        // #### Delivery Activation / Deactivation ####
    </script>
@endpush

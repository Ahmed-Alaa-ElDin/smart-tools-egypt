@extends('layouts.admin.admin', ['activeSection' => 'Customers', 'activePage' => '', 'titlePage' =>
__('admin/usersPages.Edit Customer')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/usersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.customers.index') }}">{{ __('admin/usersPages.All Customers') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.Edit Customer') }}</li>
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
                                        {{ __('admin/usersPages.Through this form you can edit customer data') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Form Start --}}
                            @livewire('admin.customers.customer-form', ['customer_id' => $customer])
                            {{-- content --}}

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
        window.addEventListener('swalConfirmPassword', function(e) {
            Swal.fire({
                text: e.detail.text,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: 'gray',
                confirmButtonColor: 'orange',
                focusDeny: true,
                denyButtonText: e.detail.denyButtonText,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('resetPassword');
                }
            });
        });

        window.addEventListener('swalPasswordReset', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                @if (session('locale' == 'en'))
                    position: 'top-left',
                @else
                    position: 'top-right',
                @endif                
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
    </script>
@endpush

@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => '', 'titlePage'
=> __("admin/deliveriesPages.'s Users",['name'=>$country->name])])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.countries.index') }}">{{ __('admin/deliveriesPages.All Countries') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __("admin/deliveriesPages.'s Users", ['name' => $country->name]) }}
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
                                        {{ __("admin/deliveriesPages.Here you can manage country's users") }}</p>
                                </div>

                                {{-- Add New User Button --}}
                                @can('Add User')
                                    <div class="ltr:text-right rtl:text-left">
                                        <a href="{{ route('admin.users.create') }}"
                                            class="btn btn-sm bg-success hover:bg-green-700 focus:bg-success active:bg-success font-bold">
                                            <span class="material-icons rtl:ml-1 ltr:mr-1">
                                                add
                                            </span>
                                            {{ __('admin/deliveriesPages.Add User') }}</a>
                                    </div>
                                @endcan
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.countries.users-country-datatable' , ['country_id' => $country->id])
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
        // #### User Deleted ####
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
                    Livewire.emit('softDeleteUser', e.detail.user_id);
                }
            });
        });

        window.addEventListener('swalUserDeleted', function(e) {
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
        // #### User Deleted ####

        window.addEventListener('swalEditRolesSelect', function(e) {
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
                denyButtonColor: 'gray',
                confirmButtonColor: 'green',

            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('editRoles', e.detail.user_id, result.value);
                }
            });
        });

        window.addEventListener('swalUserRoleChanged', function(e) {
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
    </script>
@endpush

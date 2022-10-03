@extends('layouts.admin.admin', ['activeSection' => 'Users', 'activePage' => 'All Users', 'titlePage' => __('admin/usersPages.All Users')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/usersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.All Users') }}</li>
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
                                    <p class=""> {{ __('admin/usersPages.Here you can manage users') }}</p>
                                </div>

                                {{-- Add New User Button --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/usersPages.Add User') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.users.users-datatable')
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
        // #### User Edit Role ####
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
        // #### User Edit Role ####

        // #### User Add Points ####
        window.addEventListener('swalAddPointsForm', function(e) {
            Swal.fire({
                title: e.detail.title,
                input: 'number',
                customClass: {
                    title: 'text-lg mt-4',
                    input: 'role-grapper rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300',
                },
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: 'gray',
                confirmButtonColor: 'green',

            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('addPoints', e.detail.user_id, result.value);
                }
            });
        });
        // #### User Add Points ####
    </script>
@endpush

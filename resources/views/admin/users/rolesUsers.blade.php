@extends('layouts.admin.admin', ['activeSection' => 'Users', 'activePage' => '', 'titlePage' =>
__('admin/usersPages.Role\'s Users List')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/usersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.users.index') }}">{{ __('admin/usersPages.All Users') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.roles.index') }}">{{ __('admin/usersPages.Roles Management') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.Role\'s Users List') }}
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
                                        {{ __('admin/usersPages.Here you can view the list of role\'s users') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- List Start --}}
                            @livewire('admin.roles.roles-users-list',['role_id' => $id])

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
        // #### User Soft Delete ####
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
        // #### User Soft Delete ####

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

@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => '', 'titlePage' => __("admin/deliveriesPages.'s Users", ['name' => $city->name])])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.cities.index') }}">{{ __('admin/deliveriesPages.All Cities') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __("admin/deliveriesPages.'s Users", ['name' => $city->name]) }}
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
                                        {{ __("admin/deliveriesPages.Here you can manage city's users") }}</p>
                                </div>

                                {{-- Add New User Button --}}
                                    <div class="ltr:text-right rtl:text-left">
                                        <a href="{{ route('admin.users.create') }}"
                                            class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                            <span class="material-icons rtl:ml-1 ltr:mr-1">
                                                add
                                            </span>
                                            {{ __('admin/deliveriesPages.Add User') }}</a>
                                    </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.customers.customers-datatable', ['city_id' => $city->id])
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
                    Livewire.dispatch('editRoles', e.detail.user_id, result.value);
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

        // #### Customer Add Points ####
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
                    Livewire.dispatch('addPoints', e.detail.user_id, result.value);
                }
            });
        });
        // #### Customer Add Points ####

        // #### Customer Banned :: Start ####
        window.addEventListener('swalUserPanned', function(e) {
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
        })
        // #### Customer Banned :: End ####
    </script>
@endpush

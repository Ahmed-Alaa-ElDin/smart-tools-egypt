@extends('layouts.admin.admin', ['activeSection' => 'Users', 'activePage' => 'All Users', 'titlePage' =>
__('admin/usersPages.All Users')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/usersPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.All Users') }}</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-6 ltr:text-left rtl:text-right font-bold self-center">
                                    <p class=""> {{ __('admin/usersPages.Here you can manage users') }}</p>
                                </div>
                                <div class="col-6 ltr:text-right rtl:text-left">
                                    <a href="#"
                                        class="btn btn-sm bg-green-700 hover:bg-green-600 focus:bg-green-700 active:bg-green-700 font-bold">{{ __('admin/usersPages.Add New User') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Data Table Start --}}
                            <livewire:admin.users.users-datatable />
                            {{-- Data Table End --}}

                        </div>
                    </div>
                </div>
            </div>
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
@endpush



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

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-6 ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class=""> {{ __('admin/usersPages.Here you can manage users') }}</p>
                                </div>
                                <div class="col-6 ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn btn-sm bg-green-600 hover:bg-green-700 focus:bg-green-600 active:bg-green-600 font-bold">{{ __('admin/usersPages.Add User') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Data Table Start --}}
                            @livewire('admin.users.users-datatable')
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
@endpush



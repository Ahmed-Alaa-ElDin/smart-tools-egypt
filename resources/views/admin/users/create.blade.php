@extends('layouts.admin.admin', ['activeSection' => 'Users', 'activePage' => 'Add User', 'titlePage' =>
__('admin/usersPages.Add User')])

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
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/usersPages.Add User') }}</li>
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
                                    <p class="">
                                        {{ __('admin/usersPages.Through this form you can add new user') }}</p>
                                </div>

                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            <form action="" method="post">

                                <div class="grid grid-cols-12 gap-6 items-center bg-red-100 p-2 rounded text-center my-2">
                                    <label class="col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.First Name') }}</label>
                                    {{-- First Name Ar --}}
                                    <input class="col-span-5 py-1 rounded text-center focus:outline-red-600 focus:ring-red-300 focus:border-red-300" type="text" name="" id="" placeholder="{{ __('admin/usersPages.in Arabic') }}">
                                    {{-- First Name En --}}
                                    <input class="col-span-5 py-1 rounded text-center focus:outline-red-600 focus:ring-red-300 focus:border-red-300" type="text" name="" id="" placeholder="{{ __('admin/usersPages.in English') }}">
                                </div>

                                <div class="grid grid-cols-12 gap-6 items-center bg-gray-100 p-2 rounded text-center">
                                    <label class="col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Last Name') }}</label>
                                    {{-- First Name Ar --}}
                                    <input class="col-span-5 py-1 rounded text-center focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300" type="text" name="" id="" placeholder="{{ __('admin/usersPages.in Arabic') }}">
                                    {{-- First Name En --}}
                                    <input class="col-span-5 py-1 rounded text-center focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300" type="text" name="" id="" placeholder="{{ __('admin/usersPages.in English') }}">
                                </div>

                                {{-- Last Name Ar --}}
                                {{-- Last Name En --}}
                                {{-- Email --}}
                                {{-- Phone --}}
                                {{-- Gender --}}
                                {{-- Photo --}}
                                {{-- Role --}}
                                {{-- Password Notification --}}
                                {{-- <TPDO> Address --}}
                            </form>
                            {{-- content --}}
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

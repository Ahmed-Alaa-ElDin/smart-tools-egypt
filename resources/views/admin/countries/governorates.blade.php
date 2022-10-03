@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => '', 'titlePage' => __("admin/deliveriesPages.'s Governorates", ['name' => $country->name])])

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
                        {{ __("admin/deliveriesPages.'s Governorates", ['name' => $country->name]) }}
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
                                        {{ __("admin/deliveriesPages.Here you can manage country's governorates") }}</p>
                                </div>

                                {{-- Add New Governorate Button --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.governorates.create') }}"
                                        class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/deliveriesPages.Add Governorate') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.governorates.governorates-datatable', ['country_id' => $country->id])
                            {{-- Datatable End --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

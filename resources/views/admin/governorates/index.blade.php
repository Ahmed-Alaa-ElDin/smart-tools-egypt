@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => 'Governorates', 'titlePage' => __('admin/deliveriesPages.All Governorates')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/deliveriesPages.All Governorates') }}
                    </li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex flex-wrap justify-around md:justify-between gap-2">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __('admin/deliveriesPages.Here you can manage governorates') }}</p>
                                </div>

                                {{-- Import Governorates From Bosta --}}
                                <a href="{{ route('admin.governorates.importFromBosta') }}"
                                    class="btn btn-sm bg-secondary font-bold hover:bg-secondayDark">
                                    <span class="material-icons text-sm rtl:ml-1 ltr:mr-1">
                                        cloud_download
                                    </span>
                                    {{ __('admin/deliveriesPages.Import From Bosta') }}
                                </a>

                                {{-- Add New Governorate Button --}}
                                <a href="{{ route('admin.governorates.create') }}"
                                    class="btn btn-sm bg-success font-bold hover:bg-successDark">
                                    <span class="material-icons text-sm rtl:ml-1 ltr:mr-1">
                                        add
                                    </span>
                                    {{ __('admin/deliveriesPages.Add Governorate') }}
                                </a>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.governorates.governorates-datatable')
                            {{-- Datatable End --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

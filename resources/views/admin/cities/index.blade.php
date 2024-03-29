@extends('layouts.admin.admin', ['activeSection' => 'Delivery System', 'activePage' => 'Cities', 'titlePage' => __('admin/deliveriesPages.All Cities')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin/deliveriesPages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('admin/deliveriesPages.All Cities') }}
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
                                        {{ __('admin/deliveriesPages.Here you can manage cities') }}</p>
                                </div>

                                <a href="{{ route('admin.cities.importFromBosta') }}"
                                    class="btn btn-sm bg-secondary hover:bg-secondaryDark focus:bg-secondary active:bg-secondary font-bold">
                                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                                        cloud_download
                                    </span>
                                    {{ __('admin/deliveriesPages.Import From Bosta') }}
                                </a>


                                {{-- Add New City Button --}}
                                <a href="{{ route('admin.cities.create') }}"
                                    class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                                        add
                                    </span>
                                    {{ __('admin/deliveriesPages.Add City') }}
                                </a>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">
                            {{-- Datatable Start --}}
                            @livewire('admin.cities.cities-datatable')
                            {{-- Datatable End --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

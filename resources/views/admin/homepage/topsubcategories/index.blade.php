@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => '', 'titlePage'
=> __("admin/sitePages.Homepage's Top Subcategories")])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.dashboard') }}">
                            {{ __('admin/sitePages.Dashboard') }}
                        </a>
                    </li>

                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.homepage') }}">
                            {{ __('admin/sitePages.Homepage Control') }}
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __("admin/sitePages.Homepage's Top Subcategories") }}
                    </li>
                </ol>
            </nav>

            <section class="row">
                <div class="col-md-12">

                    {{-- Card --}}
                    <div class="card">

                        {{-- Card Head --}}
                        <div class="card-header card-header-primary">
                            <div class="flex justify-between items-center">
                                <div class=" ltr:text-left rtl:text-right font-bold self-center text-gray-100">
                                    <p class="">
                                        {{ __("admin/sitePages.Here you can manage homepage's top subcategories") }}</p>
                                </div>

                                {{-- Add New Subcategories Button --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.subcategories.create') }}"
                                        class="btn btn-sm bg-success hover:bg-green-700 focus:bg-success active:bg-success font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/sitePages.Add Subcategory') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Datatable Start --}}
                            @livewire('admin.homepage.topsubcategories.topsubcategories')
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
@endpush

@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => '', 'titlePage'
=> __("admin/sitePages.Homepage's Slider Control")])

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
                        <a href="{{ route('admin.setting.homepage') }}">
                            {{ __('admin/sitePages.Homepage Control') }}
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __("admin/sitePages.Homepage's Slider Control") }}
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
                                        {{ __("admin/sitePages.Here you can choose banners for main slider") }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Datatable Start --}}
                            @livewire('admin.setting.homepage.sliders.choose-banners-datatable')
                            {{-- Datatable End --}}

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

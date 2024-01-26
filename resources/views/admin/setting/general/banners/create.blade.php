@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => 'general', 'titlePage' => __('admin/sitePages.Add New Banner')])

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
                        <a href="{{ route('admin.setting.general') }}">
                            {{ __('admin/sitePages.General Settings') }}
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/sitePages.Add New Banner') }}
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
                                        {{ __('admin/sitePages.Through this form you can add new banner') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Form Start --}}
                            @livewire('admin.setting.general.banners.banner-form')

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection


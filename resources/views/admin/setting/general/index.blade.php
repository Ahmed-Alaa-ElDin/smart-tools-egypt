@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => 'general', 'titlePage' => __('admin/sitePages.General Settings')])

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
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/sitePages.General Settings') }}
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
                                        {{ __("admin/sitePages.Here you can manage general website settings") }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body :: Start --}}
                        <div class="card-body overflow-hidden">

                            {{-- Static Part : Start --}}
                            <div class="grid grid-cols-12 gap-4 justify-between items-center mb-3">

                                {{-- Top Banner Bar : Start --}}
                                <a href="{{ route('admin.setting.general.topbanner.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                d="M7 10h10a2 2 0 1 1 0 4H7a2 2 0 1 1 0-4Zm-4 7h18a1 1 0 0 1 0 2H3a1 1 0 0 1 0-2ZM3 5h18a1 1 0 0 1 0 2H3a1 1 0 1 1 0-2Z" />
                                        </svg> </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Banner Bar') }}
                                    </span>
                                </a>
                                {{-- Top Banner Bar : End --}}

                                {{-- Slider : Start --}}
                                <a href="{{ route('admin.setting.general.nav-link.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        more_horiz
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Header Navbar') }}
                                    </span>
                                </a>
                                {{-- Slider : End --}}

                                {{-- Banners List : Start --}}
                                <a href="{{ route('admin.setting.general.banners.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        view_carousel
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Banners List') }}
                                    </span>
                                </a>
                                {{-- Banners List : End --}}

                            </div>
                            {{-- Static Part : End --}}

                        </div>
                        {{-- Card Body :: End --}}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

{{-- Extra Scripts --}}
@push('js')

    <script>

    </script>
@endpush

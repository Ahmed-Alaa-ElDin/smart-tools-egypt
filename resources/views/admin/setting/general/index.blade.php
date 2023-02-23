@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => 'HomePage', 'titlePage' => __('admin/sitePages.Homepage Control')])

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
                        {{ __('admin/sitePages.Homepage Control') }}
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
                                        {{ __("admin/sitePages.Here you can manage homepage's sections") }}</p>
                                </div>

                                {{-- Add New Home page section --}}
                                <div class="ltr:text-right rtl:text-left">
                                    <a href="{{ route('admin.setting.homepage.create') }}"
                                        class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/sitePages.Add New Section') }}</a>
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
                                <a href="{{ route('admin.setting.homepage.banners.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        view_carousel
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Main Slider') }}
                                    </span>
                                </a>
                                {{-- Slider : End --}}

                                {{-- Top Main Categories : Start --}}
                                <a href="{{ route('admin.setting.homepage.topsupercategories.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        category
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Main Categories') }}
                                    </span>
                                </a>
                                {{-- Top Main Categories : End --}}

                                {{-- Top Categories : Start --}}
                                <a href="{{ route('admin.setting.homepage.topcategories.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                                            <path fill="currentColor"
                                                d="M238.6 78.6A31.6 31.6 0 0 1 216 88a32.2 32.2 0 0 1-7.6-.9l-26.7 49.4l.9.9a31.9 31.9 0 0 1 0 45.2a31.9 31.9 0 0 1-45.2 0a32 32 0 0 1-5-38.9l-20.1-20.1A32.7 32.7 0 0 1 96 128a32.2 32.2 0 0 1-7.6-.9l-26.7 49.4l.9.9a31.9 31.9 0 0 1 0 45.2a31.9 31.9 0 0 1-45.2 0a31.9 31.9 0 0 1 0-45.2a32.1 32.1 0 0 1 30.2-8.5l26.7-49.4l-.9-.9a31.9 31.9 0 0 1 0-45.2a32 32 0 0 1 50.2 38.9l20.1 20.1a32.4 32.4 0 0 1 23.9-3.5l26.7-49.4l-.9-.9a31.9 31.9 0 0 1 0-45.2a32 32 0 0 1 45.2 45.2Z" />
                                        </svg>
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Categories') }}
                                    </span>
                                </a>
                                {{-- Top Categories : End --}}

                                {{-- Top Sub Categories : Start --}}
                                <a href="{{ route('admin.setting.homepage.topsubcategories.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        hub
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Subcategories') }}
                                    </span>
                                </a>
                                {{-- Top Sub Categories : End --}}

                                {{-- Top Brands : Start --}}
                                <a href="{{ route('admin.setting.homepage.topbrands.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                            height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64">
                                            <path fill="currentColor"
                                                d="M36.604 23.043c-.623-.342-1.559-.512-2.805-.512h-6.693v7.795h6.525c1.295 0 2.268-.156 2.916-.473c1.146-.551 1.721-1.639 1.721-3.268c0-1.757-.555-2.939-1.664-3.542" />
                                            <path fill="currentColor"
                                                d="M32.002 2C15.434 2 2 15.432 2 32s13.434 30 30.002 30s30-13.432 30-30s-13.432-30-30-30m12.82 44.508h-6.693a20.582 20.582 0 0 1-.393-1.555a14.126 14.126 0 0 1-.256-2.5l-.041-2.697c-.023-1.85-.344-3.084-.959-3.701c-.613-.615-1.766-.924-3.453-.924h-5.922v11.377H21.18V17.492h13.879c1.984.039 3.51.289 4.578.748s1.975 1.135 2.717 2.027a9.07 9.07 0 0 1 1.459 2.441c.357.893.537 1.908.537 3.051c0 1.379-.348 2.732-1.043 4.064s-1.844 2.273-3.445 2.826c1.338.537 2.287 1.303 2.844 2.293c.559.99.838 2.504.838 4.537v1.949c0 1.324.053 2.225.16 2.697c.16.748.533 1.299 1.119 1.652v.731z" />
                                        </svg>
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Brands') }}
                                    </span>
                                </a>
                                {{-- Top Brands : End --}}

                                {{-- Today Deal : Start --}}
                                <a href="{{ route('admin.setting.homepage.today-deals.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        percent
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __("admin/sitePages.Manage Today's Deal") }}
                                    </span>
                                </a>
                                {{-- Today Deal : End --}}
                            </div>
                            {{-- Static Part : End --}}

                            {{-- Sections List :: Start --}}
                            <div>
                                @livewire('admin.setting.homepage.sections-list')
                            </div>
                            {{-- Sections List :: End --}}
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
        // #### Section Activation / Deactivation ####
        window.addEventListener('swalSectionActivated', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                @if (session('locale' == 'en'))
                    position: 'top-left',
                @else
                    position: 'top-right',
                @endif                
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
        // #### Section Activation / Deactivation ####
    </script>
@endpush

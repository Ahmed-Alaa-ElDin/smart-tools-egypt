@extends('layouts.admin.admin', ['activeSection' => 'Site Control', 'activePage' => 'HomePage', 'titlePage'
=> __('admin/sitePages.Homepage Control')])

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
                                    <a href="{{ route('admin.homepage.create') }}"
                                        class="btn btn-sm bg-green-600 hover:bg-green-700 focus:bg-green-600 active:bg-green-600 font-bold">
                                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                                            add
                                        </span>
                                        {{ __('admin/sitePages.Add New Section') }}</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Static Part : Start --}}
                            <div class="grid grid-cols-12 gap-4 justify-between items-center">

                                {{-- Slider : Start --}}
                                <a href="{{ route('admin.site.banners.index') }}"
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
                                <a href="{{ route('admin.site.topsupercategories.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        category
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Main Categories') }}
                                    </span>
                                </a>
                                {{-- Top Main Categories : End --}}

                                {{-- Top Sub Categories : Start --}}
                                <a href="{{ route('admin.site.topcategories.index') }}"
                                    class="col-span-6 md:col-span-3 bg-gray-100 rounded-xl shadow hover:shadow-lg cursor-pointer p-3 flex flex-col justify-center items-center gap-3">
                                    <span class="material-icons text-center text-9xl ">
                                        hub
                                    </span>
                                    <span class="text-center font-bold">
                                        {{ __('admin/sitePages.Manage Top Categories') }}
                                    </span>
                                </a>
                                {{-- Top Sub Categories : End --}}

                                {{-- Today Deal : Start --}}
                                <a href="#"
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

    <script>
        // #### Offer Sweetalert ####
        window.addEventListener('swalConfirm', function(e) {

            Swal.fire({
                icon: 'warning',
                text: e.detail.text,
                showDenyButton: true,
                confirmButtonText: e.detail.confirmButtonText,
                denyButtonText: e.detail.denyButtonText,
                denyButtonColor: 'gray',
                confirmButtonColor: e.detail.confirmButtonColor,
                focusDeny: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit(e.detail.func, e.detail.offer_id);
                }
            });
        });

        window.addEventListener('swalDone', function(e) {
            Swal.fire({
                text: e.detail.text,
                icon: e.detail.icon,
                position: 'top-right',
                showConfirmButton: false,
                toast: true,
                timer: 3000,
                timerProgressBar: true,
            })
        });
        // #### Offer Sweetalert ####
    </script>
@endpush

@extends('layouts.admin.admin', ['activeSection' => 'Products', 'activePage' => '', 'titlePage' =>
__('admin/productsPages.Edit Product')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb text-sm">
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.dashboard') }}">{{ __('admin/productsPages.Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item hover:text-primary">
                        <a href="{{ route('admin.products.index') }}">{{ __('admin/productsPages.All Products') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/productsPages.Edit Product') }}
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
                                        {{ __("admin/productsPages.Through this form you can edit product's data") }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Form Start --}}
                            @livewire('admin.products.product-form', ['product_id' => $product])

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

    {{-- Tinymce --}}
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>

    <script>
        // Basic tinumce config
        let options = {
            inline: true,
            plugins: [
                'lists',
                'autolink',
                'advlist',
                'directionality',
                'table',
                'autoresize',
                // 'fullscreen'
            ],
            toolbar: 'ltr rtl | ' +
                'bold italic backcolor fontsizeselect| alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat',
            statusbar: false,
            menubar: false,
            content_style: '.mce-content-body[data-mce-placeholder]:not(.mce-visualblocks)::before { text-align: center ; width: 100% }'

        }

        // tinymce for Description
        tinymce.init({
            ...options,
            directionality: 'rtl',
            selector: '#description_ar',
            setup: function(editor) {
                editor.on('blur', function(e) {
                    window.livewire.emit('descriptionAr', tinymce.get(e.target.id).getContent())
                });
            }
        });

        // tinymce for Description
        tinymce.init({
            ...options,
            directionality: 'ltr',
            selector: '#description_en',
            setup: function(editor) {
                editor.on('blur', function(e) {
                    window.livewire.emit('descriptionEn', tinymce.get(e.target.id).getContent())
                });
            }
        });

        // tinymce for SEO Description
        tinymce.init({
            ...options,
            directionality: 'rtl',
            selector: '#seo_description',
            setup: function(editor) {
                editor.on('blur', function(e) {
                    window.livewire.emit('descriptionSeo', tinymce.get(e.target.id).getContent())
                });
            }
        });
    </script>
@endpush

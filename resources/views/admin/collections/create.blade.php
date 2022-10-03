@extends('layouts.admin.admin', ['activeSection' => 'Collections', 'activePage' => 'Add Collection', 'titlePage' => __('admin/productsPages.Add Collection')])

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
                        <a href="{{ route('admin.collections.index') }}">{{ __('admin/productsPages.All Collections') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('admin/productsPages.Add Collection') }}
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
                                        {{ __('admin/productsPages.Through this form you can add new collection') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body overflow-hidden">

                            {{-- Form Start --}}
                            @livewire('admin.collections.collection-form')

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
{{-- Extra Scripts --}}
@push('js')
    {{-- Tinymce --}}
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>

    <script>
        var searchInputs = document.getElementsByClassName('searchInput');

        for (let i = 0; i < searchInputs.length; i++) {
            const element = searchInputs[i];
            element.addEventListener('blur', function(event) {
                setTimeout(() => {
                    window.livewire.emitTo(`admin.collections.${element.dataset.name}`, 'clearSearch');
                }, 200);
            })
        }

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
            ],
            toolbar: 'ltr rtl | ' +
                'bold italic backcolor fontsizeselect| alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat',
            statusbar: false,
            menubar: false,
            content_style: `
                .mce-content-body[data-mce-placeholder]:not(.mce-visualblocks)::before {
                    text-align: center ; width: 100%
                    }
                    .mce-content-body ul {
                        padding: 0 10px;
                        list-style-type: disc;
                    }
                `
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
    </script>
@endpush

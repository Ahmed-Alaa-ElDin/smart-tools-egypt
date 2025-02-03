@extends('layouts.front.site', [
    'titlePage' => $subcategory->name,
    'url' => route('front.subcategories.show', $subcategory->id),
    'title' => $subcategory->name,
    'description' => '',
])

@section('content')
    <div class="container px-4 py-2 ">
        {{-- Breadcrumb :: Start --}}
        <nav aria-label="breadcrumb" role="navigation" class="mb-2">
            <ol class="breadcrumb text-sm">
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.homepage') }}">
                        {{ __('front/homePage.Homepage') }}
                    </a>
                </li>
                <li class="breadcrumb-item hover:text-primary">
                    <a href="{{ route('front.subcategories.index') }}">
                        {{ __('front/homePage.All Subcategories') }}
                    </a>
                </li>
                <li class="breadcrumb-item text-gray-700 font-bold" aria-current="page">
                    {{ $subcategory->name }}
                </li>
            </ol>
        </nav>
        {{-- Breadcrumb :: End --}}

        {{-- Subcategories Products :: Start --}}
        @livewire('front.subcategory-page.subcategory-page', [
            'sectionTitle' => __('front/homePage.Subcategory Products', ['subcategory' => $subcategory->name]),
            'subcategoryId' => $subcategory->id,
        ])
        {{-- Subcategories Products :: Start --}}

    </div>
@endsection

{{-- Extra Scripts --}}
{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {
            // ####### Filters Slider :: Start #######
            function toggleFilters() {
                if (document.dir == "rtl") {
                    $('#filters').toggleClass('rtl:translate-x-full');
                    $('#filters-dropshadow').toggleClass('hidden');
                } else {
                    $('#filters').toggleClass('ltr:-translate-x-full');
                    $('#filters-dropshadow').toggleClass('hidden');
                }
            }

            $('#filters-button').on('click', toggleFilters)

            $('#filters-dropshadow').on('click', toggleFilters)

            $('#filters-close').on('click', toggleFilters)
            // ####### Filters Slider :: End #######

            // ####### Show More :: Start #######
            // Brands
            if ($('#brands label[for^="brand"]').length > 3) {
                $('#brands label[for^="brand"]').slice(3).addClass('hidden');
                $('#showAllBrands').parent().removeClass('hidden');
            }
            $('#showAllBrands').on('click', function() {
                $('#brands label[for^="brand"]').removeClass('hidden');
                $('#showLessBrands').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessBrands').on('click', function() {
                $('#brands label[for^="brand"]').slice(3).addClass('hidden');
                $('#showAllBrands').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })

            // SuperCategories
            if ($('#supercategories label[for^="supercategory"]').length > 3) {
                $('#supercategories label[for^="supercategory"]').slice(3).addClass('hidden');
                $('#showAllSupercategories').parent().removeClass('hidden');
            }
            $('#showAllSupercategories').on('click', function() {
                $('#supercategories label[for^="supercategory"]').removeClass('hidden');
                $('#showLessSupercategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessSupercategories').on('click', function() {
                $('#supercategories label[for^="supercategory"]').slice(3).addClass('hidden');
                $('#showAllSupercategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })

            // Categories
            if ($('#categories label[for^="category"]').length > 3) {
                $('#categories label[for^="category"]').slice(3).addClass('hidden');
                $('#showAllCategories').parent().removeClass('hidden');
            }
            $('#showAllCategories').on('click', function() {
                $('#categories label[for^="category"]').removeClass('hidden');
                $('#showLessCategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessCategories').on('click', function() {
                $('#categories label[for^="category"]').slice(3).addClass('hidden');
                $('#showAllCategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })

            // Subcategories
            if ($('#subcategories label[for^="subcategory"]').length > 3) {
                $('#subcategories label[for^="subcategory"]').slice(3).addClass('hidden');
                $('#showAllSubcategories').parent().removeClass('hidden');
            }
            $('#showAllSubcategories').on('click', function() {
                $('#subcategories label[for^="subcategory"]').removeClass('hidden');
                $('#showLessSubcategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessSubcategories').on('click', function() {
                $('#subcategories label[for^="subcategory"]').slice(3).addClass('hidden');
                $('#showAllSubcategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            // ####### Show More :: End #######
        });
    </script>
@endpush

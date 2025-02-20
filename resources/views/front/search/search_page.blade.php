@extends('layouts.front.site', ['titlePage' => __('front/homePage.Search for ') . '"' . $search . '"'])

@section('content')
    @livewire('front.search.search-results')
@endsection

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

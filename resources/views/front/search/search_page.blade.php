@extends('layouts.front.site', ['titlePage' => __('front/homePage.Search for ') . '"' . $search . '"'])

@section('content')
    @livewire('front.search.search-results', ['search' => $search])
@endsection

{{-- Extra Scripts --}}
@push('js')
    <script>
        $(document).ready(function() {
            // ####### Main Slider :: Start #######
            // var main_slider = new Splide('#main-slider', {
            //     @if (LaravelLocalization::getCurrentLocale() == 'ar')
            //         direction: 'rtl',
            //         pagination: 'rtl',
            //     @else
            //         pagination: 'ltr',
            //     @endif
            //     autoplay: true,
            //     type: 'loop',
            //     keyboard: true,
            //     cover: true,
            //     height: "inherit",
            // });
            // main_slider.mount();
            // // ####### Main Slider :: End #######

            // // ####### Products Slider :: Start #######
            // var splide_options = {
            //     @if (LaravelLocalization::getCurrentLocale() == 'ar')
            //         direction: 'rtl',
            //         pagination: 'rtl',
            //     @else
            //         pagination: 'ltr',
            //     @endif
            //     perPage: 5,
            //     perMove: 2,
            //     drag: 'free',
            //     breakpoints: {
            //         1200: {
            //             perPage: 3,
            //         },
            //         770: {
            //             perPage: 2,
            //         }
            //     },
            //     type: 'slide',
            //     keyboard: true,
            //     cover: true,
            //     gap: 15,
            //     height: "inherit",
            // };

            // var product_lists = $('.product_list');

            // for (let i = 0; i < product_lists.length; i++) {
            //     new Splide(product_lists[i], splide_options).mount();
            // }
            // ####### Products Slider :: End #######

            // ####### Show More :: Start #######
                // Brands
            if ($('#brands label[for^="brand"]').length > 3) {
                $('#brands label[for^="brand"]').slice(3).addClass('hidden');
                $('#showAllBrands').parent().removeClass('hidden');
            }
            $('#showAllBrands').on('click', function(){
                $('#brands label[for^="brand"]').removeClass('hidden');
                $('#showLessBrands').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessBrands').on('click', function(){
                $('#brands label[for^="brand"]').slice(3).addClass('hidden');
                $('#showAllBrands').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })

                // SuperCategories
            if ($('#supercategories label[for^="supercategory"]').length > 3) {
                $('#supercategories label[for^="supercategory"]').slice(3).addClass('hidden');
                $('#showAllSupercategories').parent().removeClass('hidden');
            }
            $('#showAllSupercategories').on('click', function(){
                $('#supercategories label[for^="supercategory"]').removeClass('hidden');
                $('#showLessSupercategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessSupercategories').on('click', function(){
                $('#supercategories label[for^="supercategory"]').slice(3).addClass('hidden');
                $('#showAllSupercategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })

                // Categories
            if ($('#categories label[for^="category"]').length > 3) {
                $('#categories label[for^="category"]').slice(3).addClass('hidden');
                $('#showAllCategories').parent().removeClass('hidden');
            }
            $('#showAllCategories').on('click', function(){
                $('#categories label[for^="category"]').removeClass('hidden');
                $('#showLessCategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessCategories').on('click', function(){
                $('#categories label[for^="category"]').slice(3).addClass('hidden');
                $('#showAllCategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })

               // Subcategories
            if ($('#subcategories label[for^="subcategory"]').length > 3) {
                $('#subcategories label[for^="subcategory"]').slice(3).addClass('hidden');
                $('#showAllSubcategories').parent().removeClass('hidden');
            }
            $('#showAllSubcategories').on('click', function(){
                $('#subcategories label[for^="subcategory"]').removeClass('hidden');
                $('#showLessSubcategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            $('#showLessSubcategories').on('click', function(){
                $('#subcategories label[for^="subcategory"]').slice(3).addClass('hidden');
                $('#showAllSubcategories').parent().removeClass('hidden');
                $(this).parent().addClass('hidden');
            })
            // ####### Show More :: End #######

            // ####### Timer :: Start #######
            $('.timer').each(function() {
                var countDownDate = new Date($(this).data('date')).getTime();

                var x = setInterval(() => {
                    var now = new Date().getTime();

                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $(this).find('.days').text(days);
                    $(this).find('.hours').text(hours);
                    $(this).find('.minutes').text(minutes);
                    $(this).find('.seconds').text(seconds);

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        $('.timer').addClass('hidden');
                        $('.expired').removeClass('hidden');
                    }
                }, 1000);
            });
            // ####### Timer :: End #######
        });
    </script>
@endpush

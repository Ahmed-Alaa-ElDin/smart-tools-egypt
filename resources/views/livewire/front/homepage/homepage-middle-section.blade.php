<section class="top-slider container mt-4 mb-3 relative grid grid-cols-12 gap-3 lg:gap-4 items-stretch">

    {{-- All Categories : Start --}}
    @livewire('front.homepage.all-categories-large', ['topSupercategories' => $topSupercategories])
    {{-- All Categories : End --}}

    <div class="col-span-12 lg:col-span-8 flex flex-col gap-3">
        {{-- Main Slider & top categories : Start --}}
        <div class="overflow-hidden h-48 md:h-56 lg:h-64">
            {{-- Main Slider : Start --}}
            @livewire('front.homepage.main-slider', ['banners' => $banners])
            {{-- Main Slider : End --}}
        </div>
        {{-- Main Slider & top categories : End --}}

        {{-- SubSlider Banners : Start --}}
        @livewire('front.homepage.sub-slider-large-banners', ['subsliderBanners' => $subsliderBanners])
        {{-- SubSlider Banners : End --}}

        {{-- Subslider Small Banner : Start --}}
        @livewire('front.homepage.sub-slider-small-banners', ['subsliderSmallBanners' => $subsliderSmallBanners])
        {{-- Subslider Small Banner : End --}}
    </div>

    {{-- Today's Deal : Start --}}
    @livewire('front.homepage.todays-deal', ['section' => $todayDeals])
    {{-- Today's Deal : End --}}
</section>

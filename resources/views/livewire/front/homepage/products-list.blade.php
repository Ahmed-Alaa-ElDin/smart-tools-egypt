<section class="offer-bar mb-3">
    <div class="container">
        <div class="px-2 py-4 md:px-4 md:py-3 bg-white shadow rounded">

            {{-- Header : Start --}}
            <div class="flex flex-wrap mb-3 gap-2 justify-between items-baseline border-b text-center">
                {{-- Title : Start --}}
                <h3 class="h5 font-bold mb-0 w-full text-center md:w-auto">
                    <span class="border-b-2 border-primary pb-3 inline-block">{{ $section->title }}</span>
                </h3>
                {{-- Title : End --}}

                {{-- View More Button : Start --}}
                <div class="w-full md:w-auto">
                    <a href="{{ $section->id }}" {{-- todo --}}
                        class="btn bg-secondary btn-sm shadow-md font-bold mb-3 md:mb-auto m-auto">{{ __('front/homePage.View More') }}</a>
                </div>
                {{-- View More Button : End --}}
            </div>
            {{-- Header : End --}}

            {{-- Slider : Start --}}
            <div class="product_list splide h-full w-full row-span-2 rounded overflow-hidden" wire:ignore>
                <div class="splide__track">
                    {{-- List of Products : Start --}}
                    <ul class="splide__list">
                        @forelse ($products as $product)
                            {{-- Product : Start --}}
                            <x-front.product-box-small :product="$product" />
                            {{-- Product : End --}}
                        @empty
                            <div class="text-center w-full p-4 font-bold">
                                {{ __('front/homePage.No products in this list') }}
                            </div>
                        @endforelse
                    </ul>
                    {{-- List of Products : End --}}

                </div>
            </div>
            {{-- Slider : End --}}

        </div>
    </div>
</section>

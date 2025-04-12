<section class="col-span-12 lg:col-span-2">
    <aside class="hidden lg:block shadow rounded overflow-hidden h-180">
        <div class="flex justify-around items-center px-3 py-5 bg-red-100 rounded-t">
            <span class="text-sm font-bold">
                {{ __("front/homePage.Today's Deal") }}
            </span>
            <span
                class="text-xs font-bold rounded py-0.5 px-1 bg-red-600 text-white">{{ __('front/homePage.Hot') }}</span>
        </div>
        <div
            class="overflow-auto scrollbar scrollbar-thumb-secondary scrollbar-track-primary scrollbar-thin p-2 bg-primary rounded-b h-100">
            <div>

                <ul class="grid grid-cols-2 lg:grid-cols-1 gap-2 ">
                    @foreach ($items as $item)
                        {{-- Product : Start --}}
                        <x-front.product-box-small :item="$item" />
                        {{-- Product : End --}}
                    @endforeach

                    {{-- See All : Start --}}
                    @if ($section->count() > 11)
                        {{-- Product : Start --}}
                        <li
                            class="product overflow-hidden bg-white border border-light rounded hover:shadow-md hover:scale-105 transition cursor-pointer">
                            <div class="carousel-box inline-block w-100">
                                <div class="group mb-2">
                                    <div class="relative overflow-hidden h-40 flex items-center justify-center">

                                        {{-- Fake Image : Start --}}
                                        <div class="w-full h-full flex justify-center items-center bg-gray-200">
                                            <div class="flex justify-center items-center">
                                                <span class="block material-icons text-8xl">
                                                    construction
                                                </span>
                                            </div>
                                        </div>
                                        {{-- Fake Image : End --}}

                                    </div>

                                    <div class="md:p-3 p-2 text-left">

                                        {{-- Product Name : Start --}}
                                        <h3 class="mb-0 text-center">
                                            <span class="block text-gray-800">
                                                {{ __('front/homePage.See All') }}
                                            </span>
                                        </h3>
                                        {{-- Product Name : End --}}

                                    </div>
                                </div>
                            </div>
                        </li>
                        {{-- Product : End --}}
                    @endif
                    {{-- See All : End --}}

                </ul>
            </div>
        </div>
    </aside>

    <div class="lg:hidden">
        <div class="bg-primary shadow rounded">

            {{-- Header : Start --}}
            <div class="px-2 md:px-4 flex flex-wrap mb-3 gap-2 justify-between items-baseline border-b text-center bg-red-100">
                {{-- Title : Start --}}
                <h3
                    class="flex align-center justify-center items-center gap-5 h5 font-bold mb-0 w-full text-center md:w-auto">
                    <span class="py-3 inline-block">{{ $section->title }}</span>

                    <span class="text-white bg-primary rounded px-2 py-1 text-xs">{{ __('front/homePage.Hot') }}</span>
                </h3>
                {{-- Title : End --}}

                {{-- View More Button : Start --}}
                <div class="w-full md:w-auto">
                    <a href="{{ route('front.section-products', [$section->id]) }}"
                        class="btn bg-secondary btn-sm shadow-md font-bold mb-3 md:mb-auto m-auto">{{ __('front/homePage.View More') }}</a>
                </div>
                {{-- View More Button : End --}}
            </div>
            {{-- Header : End --}}

            {{-- Slider : Start --}}
            <div class="product_list px-2 md:px-4 splide h-full w-full row-span-2 rounded overflow-hidden bg-primary" wire:ignore>
                <div class="splide__track">
                    {{-- List of Products : Start --}}
                    <ul class="splide__list">
                        @forelse ($items as $item)
                            {{-- Product : Start --}}
                            <x-front.product-box-small :item="$item" />
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

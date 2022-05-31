    {{-- Top Brands : Start --}}
    <div class="col-span-12 lg:col-span-6 rounded p-4">

        {{-- Header : Start --}}
        <div class="flex flex-wrap mb-3 justify-between items-baseline border-b">
            {{-- Title : Start --}}
            <h3 class="h5 font-bold mb-0 text-center">
                <span class="border-b-2 border-primary pb-3 inline-block">{{ __('front/homePage.Top Brands') }}</span>
            </h3>
            {{-- Title : End --}}

            {{-- View More Button : Start --}}
            <a href="#"
                class="btn bg-secondary btn-sm shadow-md font-bold">{{ __('front/homePage.See All Brands') }}</a>
            {{-- View More Button : End --}}
        </div>
        {{-- Header : End --}}

        {{-- Brands List : Start --}}
        <div class="grid grid-cols-6 gap-3">

            @foreach ($brands as $brand)
            {{-- Brand : Start --}}
                <div class="col-span-6 md:col-span-3">
                    <a href="#" class="bg-white border block rounded p-2 hover:shadow-md">
                        <div class="grid grid-cols-12 items-center">

                            {{-- Image : Start --}}
                            <div class="col-span-3 w-16 h-16">
                                <img src="{{ asset('storage/images/logos/cropped100/' . $brand->logo_path) }}"
                                    alt="{{ $brand->name }}" class="img-fluid img rounded lazyloaded">
                            </div>
                            {{-- Image : End --}}

                            {{-- Category Name : Start --}}
                            <div class="col-span-7">
                                <div class="truncate font-bold text-center px-3">
                                    {{ $brand->name }}
                                </div>
                            </div>
                            {{-- Category Name : End --}}

                            {{-- Arrow : Start --}}
                            <div class="col-span-2 text-center">
                                <span class="material-icons text-primary">
                                    @if (session('locale') == 'en')
                                        chevron_right
                                    @else
                                        chevron_left
                                    @endif
                                </span>
                            </div>
                            {{-- Arrow : End --}}

                        </div>
                    </a>
                </div>
                {{-- Brand : End --}}
            @endforeach

        </div>
        {{-- Brands List : End --}}



    </div>
    {{-- Top Brands : End --}}

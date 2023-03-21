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
            <a href="{{ route('front.brands.index') }}"
                class="btn bg-secondary btn-sm shadow-md font-bold">{{ __('front/homePage.See All Brands') }}</a>
            {{-- View More Button : End --}}
        </div>
        {{-- Header : End --}}

        {{-- Brands List : Start --}}
        <div class="grid grid-cols-6 gap-3">

            @foreach ($brands as $brand)
                {{-- Brand : Start --}}
                <div class="col-span-6 md:col-span-3">
                    <a href="{{ route('front.brands.show', $brand->id) }}"
                        class="bg-white border block rounded p-2 hover:shadow-md">
                        <div class="grid grid-cols-12 items-center">

                            @if ($brand->logo_path)
                                {{-- Image : Start --}}
                                <div class="col-span-3 w-16 h-16">
                                    <img src="{{ asset('storage/images/logos/cropped100/' . $brand->logo_path) }}"
                                        alt="{{ $brand->name }}" class="img-fluid img rounded lazyloaded">
                                </div>
                                {{-- Image : End --}}
                            @else
                                {{-- Image : Start --}}
                                <div class="col-span-3 w-16 h-16 flex justify-center items-center">
                                    <span class="material-icons text-center text-5xl ">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                            width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 64 64">
                                            <path fill="currentColor"
                                                d="M36.604 23.043c-.623-.342-1.559-.512-2.805-.512h-6.693v7.795h6.525c1.295 0 2.268-.156 2.916-.473c1.146-.551 1.721-1.639 1.721-3.268c0-1.757-.555-2.939-1.664-3.542" />
                                            <path fill="currentColor"
                                                d="M32.002 2C15.434 2 2 15.432 2 32s13.434 30 30.002 30s30-13.432 30-30s-13.432-30-30-30m12.82 44.508h-6.693a20.582 20.582 0 0 1-.393-1.555a14.126 14.126 0 0 1-.256-2.5l-.041-2.697c-.023-1.85-.344-3.084-.959-3.701c-.613-.615-1.766-.924-3.453-.924h-5.922v11.377H21.18V17.492h13.879c1.984.039 3.51.289 4.578.748s1.975 1.135 2.717 2.027a9.07 9.07 0 0 1 1.459 2.441c.357.893.537 1.908.537 3.051c0 1.379-.348 2.732-1.043 4.064s-1.844 2.273-3.445 2.826c1.338.537 2.287 1.303 2.844 2.293c.559.99.838 2.504.838 4.537v1.949c0 1.324.053 2.225.16 2.697c.16.748.533 1.299 1.119 1.652v.731z" />
                                        </svg>
                                    </span>
                                </div>
                                {{-- Image : End --}}
                            @endif

                            {{-- Brand Name : Start --}}
                            <div class="col-span-7">
                                <div class="truncate font-bold text-center px-3">
                                    {{ $brand->name }}
                                </div>
                            </div>
                            {{-- Brand Name : End --}}

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

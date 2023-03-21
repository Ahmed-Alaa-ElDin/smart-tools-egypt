    {{-- Top Categories : Start --}}
    <div class="col-span-12 lg:col-span-6 rounded p-4">

        {{-- Header : Start --}}
        <div class="flex flex-wrap mb-3 justify-between items-baseline border-b">
            {{-- Title : Start --}}
            <h3 class="h5 font-bold mb-0 text-center">
                <span
                    class="border-b-2 border-primary pb-3 inline-block">{{ __('front/homePage.Top Categories') }}</span>
            </h3>
            {{-- Title : End --}}

            {{-- View More Button : Start --}}
            <a href="{{ route('front.categories.index') }}"
                class="btn bg-secondary btn-sm shadow-md font-bold">{{ __('front/homePage.See All Categories') }}</a>
            {{-- View More Button : End --}}
        </div>
        {{-- Header : End --}}

        {{-- Categories List : Start --}}
        <div class="grid grid-cols-6 gap-3">

            @foreach ($categories as $category)
                {{-- Category : Start --}}
                <div class="col-span-6 md:col-span-3">
                    <a href="{{ route('front.categories.show', $category->id) }}"
                        class="bg-white border block rounded p-2 hover:shadow-md">
                        <div class="grid grid-cols-12 items-center">

                            @if ($category->images->count())
                                {{-- Image : Start --}}
                                <div class="col-span-3 w-16 h-16">
                                    <img src="{{ asset('storage/images/categories/cropped100/' . $category->images->first()->file_name) }}"
                                        alt="{{ $category->name }}" class="img-fluid img rounded lazyloaded">
                                </div>
                                {{-- Image : End --}}
                            @else
                                {{-- Image : Start --}}
                                <div class="col-span-3 w-16 h-16 flex justify-center items-center">
                                    <span class="material-icons text-center text-5xl ">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                            width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 256 256">
                                            <path fill="currentColor"
                                                d="M238.6 78.6A31.6 31.6 0 0 1 216 88a32.2 32.2 0 0 1-7.6-.9l-26.7 49.4l.9.9a31.9 31.9 0 0 1 0 45.2a31.9 31.9 0 0 1-45.2 0a32 32 0 0 1-5-38.9l-20.1-20.1A32.7 32.7 0 0 1 96 128a32.2 32.2 0 0 1-7.6-.9l-26.7 49.4l.9.9a31.9 31.9 0 0 1 0 45.2a31.9 31.9 0 0 1-45.2 0a31.9 31.9 0 0 1 0-45.2a32.1 32.1 0 0 1 30.2-8.5l26.7-49.4l-.9-.9a31.9 31.9 0 0 1 0-45.2a32 32 0 0 1 50.2 38.9l20.1 20.1a32.4 32.4 0 0 1 23.9-3.5l26.7-49.4l-.9-.9a31.9 31.9 0 0 1 0-45.2a32 32 0 0 1 45.2 45.2Z" />
                                        </svg>
                                    </span>
                                </div>
                                {{-- Image : End --}}
                            @endif


                            {{-- Category Name : Start --}}
                            <div class="col-span-7">
                                <div class="truncate font-bold text-center px-3">
                                    {{ $category->name }}
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
            @endforeach
        </div>
        {{-- Categories List : End --}}

    </div>
    {{-- Top Categories : End --}}

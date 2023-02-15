<div class="grow front-header-search flex items-center bg-white ">
    <div class="relative grow">
        {{-- Search Box :: Start --}}
        <label class="relative block m-0">
            <span class="sr-only">Search</span>
            <span class="absolute inset-y-0 rtl:left-1.5 ltr:right-1.5 flex items-center pl-2">
                <span class="material-icons">
                    search
                </span>
            </span>
            <input
                class="placeholder:italic placeholder:text-slate-400 text-gray-800 block bg-white w-full border border-slate-300 rounded-md py-2 ltr:pr-10 ltr:pl-3 rtl:pl-10 rtl:pr-3 shadow-sm focus:outline-none focus:border-gray-600 focus:ring-gray-600 focus:ring-1 sm:text-xs md:text-sm font-bold"
                placeholder="{{ __("front/homePage.I'm Shopping for ...") }}" type="text" wire:keydown.enter="seeMore"
                wire:model.debounce.300ms="search" name="search" autocomplete="off" />
        </label>
        {{-- Search Box :: Start --}}

        @if ($search)

            <div id="typed-search-box"
                class="stop-propagation bg-white rounded shadow-lg absolute left-0 top-100 w-100 z-50 max-h-56 overflow-y-scroll scrollbar scrollbar-thin scrollbar-thumb-primary hover">
                {{-- Search Results :: Start --}}
                <div id="search-content" class="p-3">
                    {{-- Loading :: Start --}}
                    <div wire:loading.delay wire:target="items" class="w-full">
                        <div class="flex gap-2 justify-center items-center p-4">
                            <span class="text-primary text-xs font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                    class="animate-spin text-9xl" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 50 50">
                                    <path fill="currentColor"
                                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    {{-- Loading :: End --}}

                    {{-- Products List :: Start --}}
                    @forelse ($items as $item)
                        <a @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}"
                            @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @endif
                            class="group flex justify-center items-center gap-4 cursor-pointer rounded transition-all ease-in-out hover:bg-red-100 hover:text-current p-2"
                            wire:key="item-{{ $item['id'] }}-{{ rand() }}"
                            data-iteration="{{ $loop->index }}">

                            {{-- Product Image  --}}
                            @if ($item['thumbnail'])
                                <div
                                    class="flex justify-center items-center min-w-[100px] min-h-[100px] w-[100px] h-[100px] overflow-hidden">
                                    <img @if ($item['type'] == 'Product') src="{{ asset('storage/images/products/original/' . $item['thumbnail']['file_name']) }}"
                                        @elseif ($item['type'] == 'Collection') src="{{ asset('storage/images/collections/original/' . $item['thumbnail']['file_name']) }}" @endif
                                        alt="{{ $item['thumbnail']['file_name'] . '-image' }}"
                                        class="min-w-100 min-h-100">
                                </div>
                            @else
                                <div class="flex justify-center items-center bg-gray-100 w-[100px] h-[100px]">
                                    <span class="block material-icons text-[100px]">
                                        construction
                                    </span>
                                </div>
                            @endif

                            <div class="w-100 overflow-hidden flex flex-col gap-2">
                                {{-- Product's Name --}}
                                <div class="flex flex-col justify-start ltr:text-left rtl:text-right gap-2 grow">
                                    <span class="font-bold text-black">{{ $item['name'][session('locale')] }}</span>
                                    @if (isset($item['brand']))
                                        <span
                                            class="text-sm font-bold text-gray-500">{{ $item['brand'] ? $item['brand']['name'] : '' }}</span>
                                    @endif
                                </div>

                                {{-- Product's Model --}}
                                @if ($item['model'])
                                    <div class="inline-block w-75 truncate text-xs">
                                        <span class="text-gray-500">{{ __('front/homePage.Model') }}:</span>
                                        <span class="text-gray-800">
                                            {{ $item['model'] }}
                                        </span>
                                    </div>
                                @endif

                                {{-- Product's Description --}}
                                @if ($item['description'])
                                    <div class="inline-block w-75 truncate text-xs max-h-4">
                                        <span class="text-gray-500 font-bold">
                                            @if (session('locale') == 'en' && isset($item['description']['en']))
                                                {!! $item['description']['en'] !!}
                                            @elseif (session('locale') == 'ar' && isset($item['description']['ar']))
                                                {!! $item['description']['ar'] !!}
                                            @endif
                                        </span>
                                    </div>
                                @endif

                            </div>

                        </a>

                        @if (!$loop->last)
                            <hr class="my-1">
                        @else
                            <div class="flex justify-center w-full mt-2 ">
                                <a href="#" wire:click="seeMore"
                                    class="inline-block w-full font-bold text-white text-center rounded-xl bg-secondary py-2 ">
                                    {{ __('front/homePage.See More & Apply filter') }}
                                </a>
                            </div>
                        @endif
                    @empty
                        <div class="text-center font-bold">
                            {{ __('admin/offersPages.No Products or Collections Found') }}
                        </div>
                    @endforelse
                    {{-- Products List :: End --}}
                </div>
                {{-- Search Results :: End --}}
            </div>
        @endif

    </div>


</div>

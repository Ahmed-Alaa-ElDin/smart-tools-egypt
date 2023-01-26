<div class="grid grid-cols-12 gap-4 p-3">
    {{-- Filters :: Start --}}
    <div class="col-span-3 d-none md:block">
        <div class="bg-white rounded shadow m-auto px-3">
            <div class="text-center p-3 text-sm font-bold">
                {{ __('front/homePage.Filters') }}
            </div>
            <hr>
            <div>

            </div>
        </div>
    </div>
    {{-- Filters :: End --}}

    {{-- Search Results :: Start --}}
    <div class="col-span-12 md:col-span-9 grid grid-cols-1 gap-2">
        <div class="w-full bg-white rounded shadow m-auto px-3">
            <div class="flex justify-around align-middle">
                <div class="text-center p-3 text-sm font-bold grow">
                    {{ __('front/homePage.Results') }}
                </div>

                {{-- Sorting :: Start --}}
                <div class="flex gap-2 justify-between items-center">
                    <span class="text-xs font-bold text-gray-400">
                        {{ __('front/homePage.Sort by') }}
                    </span>

                    <select wire:model="sort_by"
                        class="text-xs font-bold rounded text-center w-24 text-secondary py-2 rtl:pr-7 truncate border-1 border-gray-200 cursor-pointer focus:ring-primary ">
                        <option value="">{{ __('front/homePage.Default') }}</option>
                        <option value="name">{{ __('front/homePage.Name') }}</option>
                        <option value="final_price">{{ __('front/homePage.Price') }}</option>
                        <option value="reviews_count">{{ __('front/homePage.Reviews count') }}</option>
                        <option value="created_at">{{ __('front/homePage.Date') }}</option>
                    </select>

                    <div class="">
                        <span wire:click="changeDirection"
                            class="material-icons w-8 h-8 text-center bg-primary text-white rounded-circle p-2 text-xs font-bold cursor-pointer">
                            @if ($direction == 'asc')
                                north
                            @else
                                south
                            @endif
                        </span>
                    </div>
                </div>
                {{-- Sorting :: End --}}
            </div>

            <hr>

            <div class="relative">

                {{-- Loading Section :: Start --}}
                <div wire:loading class="absolute w-100 h-100 backdrop-blur z-10">
                    <div class="flex justify-center items-center flex-col">
                        <div class="text-[200px] text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50"
                                class="animate-spin inline-block">
                                <path fill="currentColor"
                                    d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                            </svg>
                        </div>
                        <div class="text-secondary text-4xl font-bold">
                            {{ __('front/homePage.Loading ...') }}
                        </div>
                    </div>
                </div>
                {{-- Loading Section :: End --}}

                {{-- Products :: Start --}}
                <div class="p-3 w-full grid grid-cols-4 gap-3">
                    @forelse ($items as $item)
                        {{-- Reults Count :: Start --}}
                        @if ($loop->first)
                            <div class="col-span-4 text-center text-xs font-bold text-gray-600">
                                {{ trans_choice('front/homePage.Product / Products found', $items->total(), ['no' => $items->total()]) }}
                            </div>
                        @endif
                        {{-- Reults Count :: End --}}

                        <div class="col-span-2 lg:col-span-1">
                            <x-front.product-box-small :item="$item->toArray()" wire:key="item-{{ rand() }}" />
                        </div>

                        {{-- Pagination :: Start --}}
                        @if ($loop->last)
                            <div class="col-span-4">
                                {{ $items->links() }}
                                {{-- <x-front.pagination :totalPages="$totalPages" :currentPage="$currentPage" /> --}}
                            </div>
                        @endif
                        {{-- Pagination :: End --}}
                    @empty
                        <div class="col-span-4 text-center font-bold p-3">
                            {{ __('front/homePage.No results were found for') . '"' . $search . '"' }}
                        </div>
                    @endforelse
                </div>
                {{-- Products :: End --}}

            </div>
        </div>
    </div>
    {{-- Search Results :: End --}}

</div>

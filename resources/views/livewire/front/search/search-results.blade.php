<div class="grid grid-cols-12 gap-4 p-3 items-start">
    {{-- Filters :: Start --}}
    <div id="filters-dropshadow" class="fixed hidden md:hidden w-100 h-100 left- 0 top-0 drop-shadow backdrop-blur z-40">
    </div>
    <div id="filters"
        class="col-span-3 overflow-y-auto fixed top-0 ltr:left-0 rtl:right-0 z-50 h-screen ltr:-translate-x-full rtl:translate-x-full transition-transform w-80 md:static md:block md:top-auto md:left-auto md:z-auto md:h-auto ltr:md:translate-x-0 rtl:md:translate-x-0 md:w-auto md:p-0 md:overflow-hidden">
        <div class="bg-white rounded shadow m-auto px-3">
            <div class="flex justify-center items-center p-3 gap-2">
                <span class="text-sm font-bold grow text-center">
                    {{ __('front/homePage.Filters') }}
                </span>

                <button
                    class="text-white bg-primary hover:bg-primaryDark focus:ring-0  rounded-circle w-5 h-5 p-0 flex items-center justify-center text-center md:hidden"
                    type="button" id="filters-colse">
                    <span class="material-icons text-sm font-bold">
                        close
                    </span>
                </button>

                @if ($filters)
                    <button class="bg-primary px-3 py-1 text-xs text-white font-bold rounded" wire:click="clearFilters">
                        {{ __('front/homePage.Clear Filters') }}
                    </button>
                @endif
            </div>

            <hr>

            <div id="accordion-collapse" data-accordion="open" data-active-classes="bg-transperant">
                <input type="hidden" wire:model="perPage">
                {{-- Brands :: Start --}}
                @if (count($brands))
                    <h2 id="brand-heading-1">
                        <button type="button"
                            class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                            data-accordion-target="#brand-body-1" aria-expanded="true" aria-controls="brand-body-1">
                            <span class="grow text-xs font-bold">{{ __('front/homePage.Brands') }}</span>
                            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h2>
                    <div id="brand-body-1" class="hidden" aria-labelledby="brand-heading-1" wire:ignore.self>
                        <div id="brands" class="p-2 pb-4 font-light border-0 border-b-1 border-gray-200" wire:ignore>
                            @foreach ($brands as $brand)
                                <label for="brand-{{ $brand['id'] }}"
                                    class="flex justify-start items-center gap-2 text-sm m-0 text-gray-900 cursor-pointer">
                                    <input type="checkbox" id="brand-{{ $brand['id'] }}" value="{{ $brand['id'] }}"
                                        wire:model="selectedBrands"
                                        class="rounded-circle cursor-pointer focus:ring-0 checked:bg-secondary">
                                    <span class="inline-block">
                                        <span>
                                            {{ $brand['name'] }}
                                        </span>
                                        <span class="text-gray-600 text-xs">({{ $brand['count'] }})</span>
                                    </span>
                                </label>
                            @endforeach
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showAllBrands">
                                    {{ __('front/homePage.Show All') }}
                                </button>
                            </div>
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showLessBrands">
                                    {{ __('front/homePage.Show Less') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Brands :: End --}}


                {{-- Price :: Start --}}
                <hr>

                <h2 id="price-heading-1">
                    <button type="button"
                        class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                        data-accordion-target="#price-body-1" aria-expanded="true" aria-controls="price-body-1">
                        <span class="grow text-xs font-bold">{{ __('front/homePage.Price') }}</span>
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>

                <div id="price-body-1" class="hidden" aria-labelledby="price-heading-1" wire:ignore>
                    <div class="p-2 border-0 border-b-1 border-gray-200 overflow-x-scroll scrollbar-thin">
                        <div class="price-input flex justify-start items-center gap-3 mb-4 w-100">
                            <div class="field flex items-center justify-center gap-2">
                                <span class="text-xs font-bold">{{ __('front/homePage.Min') }}</span>
                                <input type="number" min="{{ $currentMinPrice }}" max="{{ $currentMaxPrice }}"
                                    step="1" dir="ltr"
                                    class="input-min w-24 text-sm rounded focus:ring-primary focus:border-primary border-gray-300 text-center"
                                    value="{{ $currentMinPrice }}" wire:model.lazy='currentMinPrice'>
                            </div>
                            <div class="text-sm">-</div>
                            <div class="field flex items-center justify-center gap-2">
                                <span class="text-xs font-bold">{{ __('front/homePage.Max') }}</span>
                                <input type="number" min="{{ $currentMinPrice }}" max="{{ $currentMaxPrice }}"
                                    step="1" dir="ltr"
                                    class="input-max w-24 text-sm rounded focus:ring-primary focus:border-primary border-gray-300 text-center"
                                    value="{{ $currentMaxPrice }}" wire:model.lazy='currentMaxPrice'>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Price :: End --}}

                {{-- Supercategories :: Start --}}
                @if (count($supercategories))
                    <hr>

                    <h2 id="supercategory-heading-1">
                        <button type="button"
                            class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                            data-accordion-target="#supercategory-body-1" aria-expanded="true"
                            aria-controls="supercategory-body-1">
                            <span class="grow text-xs font-bold">{{ __('front/homePage.Supercategories') }}</span>
                            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h2>
                    <div id="supercategory-body-1" class="hidden" aria-labelledby="supercategory-heading-1"
                        wire:ignore.self>
                        <div id="supercategories" class="p-2 pb-4 font-light border-0 border-b-1 border-gray-200"
                            wire:ignore>
                            @foreach ($supercategories as $supercategory)
                                <label for="supercategory-{{ $supercategory['id'] }}"
                                    class="flex justify-start items-center gap-2 text-sm m-0 text-gray-900 cursor-pointer">
                                    <input type="checkbox" id="supercategory-{{ $supercategory['id'] }}"
                                        value="{{ $supercategory['id'] }}" wire:model="selectedSupercategories"
                                        class="rounded-circle cursor-pointer focus:ring-0 checked:bg-secondary">
                                    <span class="inline-block">
                                        {{ $supercategory['name'][session('locale')] }}
                                        <span class="text-gray-600 text-xs">({{ $supercategory['count'] }})</span>
                                    </span>
                                </label>
                            @endforeach
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showAllSupercategories">
                                    {{ __('front/homePage.Show All') }}
                                </button>
                            </div>
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showLessSupercategories">
                                    {{ __('front/homePage.Show Less') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Supercategories :: End --}}

                {{-- Categories :: Start --}}
                @if (count($categories))
                    <hr>

                    <h2 id="category-heading-1">
                        <button type="button"
                            class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                            data-accordion-target="#category-body-1" aria-expanded="true"
                            aria-controls="category-body-1">
                            <span class="grow text-xs font-bold">{{ __('front/homePage.Categories') }}</span>
                            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h2>
                    <div id="category-body-1" class="hidden" aria-labelledby="category-heading-1" wire:ignore.self>
                        <div id="categories" class="p-2 pb-4 font-light border-0 border-b-1 border-gray-200"
                            wire:ignore>
                            @foreach ($categories as $category)
                                <label for="category-{{ $category['id'] }}"
                                    class="flex justify-start items-center gap-2 text-sm m-0 text-gray-900 cursor-pointer">
                                    <input type="checkbox" id="category-{{ $category['id'] }}"
                                        value="{{ $category['id'] }}" wire:model="selectedCategories"
                                        class="rounded-circle cursor-pointer focus:ring-0 checked:bg-secondary">
                                    <span class="inline-block">
                                        {{ $category['name'][session('locale')] }}
                                        <span class="text-gray-600 text-xs">({{ $category['count'] }})</span>
                                    </span>
                                </label>
                            @endforeach
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showAllCategories">
                                    {{ __('front/homePage.Show All') }}
                                </button>
                            </div>
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showLessCategories">
                                    {{ __('front/homePage.Show Less') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Categories :: End --}}

                {{-- Subcategories :: Start --}}
                @if (count($subcategories))
                    <hr>

                    <h2 id="subcategory-heading-1">
                        <button type="button"
                            class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                            data-accordion-target="#subcategory-body-1" aria-expanded="true"
                            aria-controls="subcategory-body-1">
                            <span class="grow text-xs font-bold">{{ __('front/homePage.Subcategories') }}</span>
                            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </h2>
                    <div id="subcategory-body-1" class="hidden" aria-labelledby="subcategory-heading-1"
                        wire:ignore.self>
                        <div id="subcategories" class="p-2 pb-4 font-light border-0 border-b-1 border-gray-200"
                            wire:ignore>
                            @foreach ($subcategories as $subcategory)
                                <label for="subcategory-{{ $subcategory['id'] }}"
                                    class="flex justify-start items-center gap-2 text-sm m-0 text-gray-900 cursor-pointer">
                                    <input type="checkbox" id="subcategory-{{ $subcategory['id'] }}"
                                        value="{{ $subcategory['id'] }}" wire:model="selectedSubcategories"
                                        class="rounded-circle cursor-pointer focus:ring-0 checked:bg-secondary">
                                    <span class="inline-block">
                                        {{ $subcategory['name'][session('locale')] }}
                                        <span class="text-gray-600 text-xs">({{ $subcategory['count'] }})</span>
                                    </span>
                                </label>
                            @endforeach
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showAllSubcategories">
                                    {{ __('front/homePage.Show All') }}
                                </button>
                            </div>
                            <div class="text-center mt-2 hidden">
                                <button class="text-secondaryLighter text-xs font-bold" id="showLessSubcategories">
                                    {{ __('front/homePage.Show Less') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Subcategories :: End --}}

                {{-- Rating :: Start --}}
                <hr>

                <h2 id="rating-heading-1">
                    <button type="button"
                        class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                        data-accordion-target="#rating-body-1" aria-expanded="true" aria-controls="rating-body-1">
                        <span class="grow text-xs font-bold">{{ __('front/homePage.Rating') }}</span>
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>
                <div id="rating-body-1" class="hidden" aria-labelledby="rating-heading-1" wire:ignore.self>
                    <div class="p-2 pb-4 font-light border-0 border-b-1 border-gray-200 flex flex-col gap-1 items-center"
                        wire:ignore.self>
                        <label class="flex gap-1 items-center m-0 cursor-pointer">
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 5) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 5) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 5) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 5) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 5) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="text-sm @if ($currentRating == 5) text-black @else text-gray-400 @endif font-bold">&
                                up</span>
                            <span
                                class="text-sm @if ($currentRating == 5) text-black @else text-gray-400 @endif font-bold">({{ $fiveRatingNo }})</span>
                            <input type="radio" wire:model="currentRating" value="5" class="hidden">
                        </label>
                        <label class="flex gap-1 items-center m-0 cursor-pointer">

                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 4) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 4) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 4) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 4) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 4) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="text-sm @if ($currentRating == 4) text-black @else text-gray-400 @endif font-bold">&
                                up</span>
                            <span
                                class="text-sm @if ($currentRating == 4) text-black @else text-gray-400 @endif font-bold">({{ $fourRatingNo }})</span>
                            <input type="radio" wire:model="currentRating" value="4" class="hidden">
                        </label>
                        <label class="flex gap-1 items-center m-0 cursor-pointer">
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 3) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 3) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 3) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 3) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 3) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="text-sm @if ($currentRating == 3) text-black @else text-gray-400 @endif font-bold">&
                                up</span>
                            <span
                                class="text-sm @if ($currentRating == 3) text-black @else text-gray-400 @endif font-bold">({{ $threeRatingNo }})</span>
                            <input type="radio" wire:model="currentRating" value="3" class="hidden">
                        </label>
                        <label class="flex gap-1 items-center m-0 cursor-pointer">
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 2) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 2) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 2) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 2) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 2) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="text-sm @if ($currentRating == 2) text-black @else text-gray-400 @endif font-bold">&
                                up</span>
                            <span
                                class="text-sm @if ($currentRating == 2) text-black @else text-gray-400 @endif font-bold">({{ $twoRatingNo }})</span>
                            <input type="radio" wire:model="currentRating" value="2" class="hidden">
                        </label>
                        <label class="flex gap-1 items-center m-0 cursor-pointer">
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 1) text-yellow-400 @else text-yellow-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 1) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 1) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 1) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="material-icons inline-block text-md @if ($currentRating == 1) text-gray-400 @else text-gray-300 @endif">
                                star </span>
                            <span
                                class="text-sm @if ($currentRating == 1) text-black @else text-gray-400 @endif font-bold">&
                                up</span>
                            <span
                                class="text-sm @if ($currentRating == 1) text-black @else text-gray-400 @endif font-bold">({{ $oneRatingNo }})</span>
                            <input type="radio" wire:model="currentRating" value="1" class="hidden">
                        </label>
                    </div>
                </div>
                {{-- Rating :: End --}}

                <hr>

                {{-- Other :: Start --}}
                <h2 id="other-heading-1">
                    <button type="button"
                        class="flex items-center justify-between w-full p-2 font-bold text-center border-0 border-b-1 border-gray-200"
                        data-accordion-target="#other-body-1" aria-expanded="true" aria-controls="other-body-1">
                        <span class="grow text-xs font-bold">{{ __('front/homePage.Other') }}</span>
                        <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </h2>
                <div id="other-body-1" class="hidden" aria-labelledby="other-heading-1" wire:ignore.self>
                    <div
                        class="p-2 pb-4 font-light border-0 border-b-1 border-gray-200 flex flex-col gap-2 items-start">

                        {{-- Available --}}
                        <label
                            class="relative inline-flex items-center cursor-pointer items-center justify-center gap-2 m-0">
                            <span class="text-gray-800 text-xs font-bold">{{ __('front/homePage.Available') }}</span>
                            <input type="checkbox" wire:model="currentAvailable" value="true"
                                class="sr-only peer">
                            <div
                                class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-secondaryLighter rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-secondary after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary">
                            </div>
                        </label>

                        {{-- Free Shipping --}}
                        <label
                            class="relative inline-flex items-center cursor-pointer items-center justify-center gap-2 m-0">
                            <span
                                class="text-gray-800 text-xs font-bold">{{ __('front/homePage.Free Shipping') }}</span>
                            <input type="checkbox" wire:model="currentFreeShipping" value="true"
                                class="sr-only peer">
                            <div
                                class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-secondaryLighter rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-secondary after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary">
                            </div>
                        </label>

                        {{-- Returnable --}}
                        <label
                            class="relative inline-flex items-center cursor-pointer items-center justify-center gap-2 m-0">
                            <span class="text-gray-800 text-xs font-bold">{{ __('front/homePage.Returnable') }}</span>
                            <input type="checkbox" wire:model="currentReturnable" value="true"
                                class="sr-only peer">
                            <div
                                class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-secondaryLighter rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-secondary after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary">
                            </div>
                        </label>
                    </div>
                </div>
                {{-- Other :: End --}}
            </div>
        </div>
    </div>
    {{-- Filters :: End --}}

    {{-- Search Results :: Start --}}
    <div class="col-span-12 md:col-span-9 grid grid-cols-1 gap-2">
        <div class="w-full bg-white rounded shadow m-auto px-3">
            <div class="flex justify-around items-center">
                {{-- Mobile Filter Button :: Start --}}
                <button
                    class="text-white bg-primary hover:bg-primaryDark focus:ring-0 font-bold rounded-lg text-xs px-3 py-2 md:hidden flex items-center justify-center gap-1"
                    type="button" id="filters-button">
                    <span>
                        {{ __('front/homePage.Filters') }}
                    </span>
                    <span class="material-icons text-sm">
                        filter_alt
                    </span>
                </button>
                {{-- Mobile Filter Button :: End --}}

                {{-- Title :: Start --}}
                <div class="text-center p-3 text-sm font-bold grow">
                    {{ __('front/homePage.Results') }}
                </div>
                {{-- Title :: End --}}

                {{-- Sorting :: Start --}}
                <div class="flex gap-2 justify-between items-center">
                    <span class="text-xs font-bold text-gray-400">
                        {{ __('front/homePage.Sort by') }}
                    </span>

                    <select wire:model="sort_by"
                        class="text-xs font-bold rounded text-center w-24 text-secondary py-2 rtl:pr-7 truncate border-1 border-gray-200 cursor-pointer focus:ring-primary">
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

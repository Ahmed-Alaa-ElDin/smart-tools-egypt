<div class="grid grid-cols-12 gap-3 items-start">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-gray-100 p-4 text-center  rounded shadow">

        <div class="col-span-12 font-bold text-black mb-2">
            {{ __("admin/offersPages.Offer's Banner") }}
        </div>

        {{-- Banner Images Start --}}
        <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">

            {{-- Loading Spinner --}}
            <div wire:loading wire:target="banner" class="col-span-12 my-2">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                    <path fill="currentColor"
                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                </svg>
                <span> &nbsp;&nbsp; {{ __('admin/offersPages.Uploading ...') }}</span>
            </div>

            @if ($banner_name != null)
                {{-- preview --}}
                <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                    <div class="text-center flex flex-wrap gap-3 justify-around">
                        <div class="relative w-25">
                            <span
                                class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                wire:click="deleteBanner"
                                title="{{ __('admin/offersPages.Delete Image') }}">clear</span>
                            <img src="{{ asset('storage/images/banners/original/' . $banner_name) }}"
                                class="rounded-xl m-auto">
                        </div>
                    </div>

                </div>
            @else
                {{-- Upload New Image --}}
                <input
                    class="col-span-12 md:col-span-6 md:col-start-4 form-control block w-full px-2 py-1 text-sm font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                    id="banner" type="file" type="image" wire:model.lazy="banner">
                <span class="col-span-12 text-xs text-gray-400">
                    {{ __('admin/offersPages.Use 800x250 sizes image') }}</span>
                @error('banner')
                    <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                @enderror
            @endif
        </div>
    </div>
    {{-- Banner Images End --}}

    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    {{-- Offer Information Start --}}
    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center  rounded shadow">

        <div class="col-span-12 font-bold text-black mb-2">
            {{ __("admin/offersPages.Offer's Information") }}
        </div>

        {{-- Name Start --}}
        <div class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 rounded text-center">
            <label for="title"
                class="col-span-12 md:col-span-2 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __("admin/offersPages.offer's Title") }}</label>
            {{-- Name Ar --}}
            <div class="col-span-6 md:col-span-5">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('title.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="title.ar" id="title" dir="rtl"
                    placeholder="{{ __('admin/offersPages.in Arabic') }}" maxlength="100" required>
                @error('title.ar')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
            {{-- Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('title.en') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="title.en" placeholder="{{ __('admin/offersPages.in English') }}"
                    dir="ltr" maxlength="100">
                @error('title.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Name End --}}

        {{-- Date Range Start --}}
        <div class="col-span-12 md:col-span-9 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
            <label for="date_range"
                class="col-span-12 md:col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Date Range') }}</label>
            <div class="col-span-12 md:col-span-9">
                <input id="date_range" name="date_range"
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('date_range') border-red-900 border-2 @enderror"
                    type="text" dir="ltr" placeholder="{{ __('admin/offersPages.Select Date Range') }}">

                @error('date_range.*')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Date Range End --}}


        {{-- Free Shipping Start --}}
        <div class="col-span-6 col-start-4 md:col-span-3 grid grid-cols-2 gap-y-2 gap-x-2 items-center w-full">
            <label wire:click="freeShipping"
                class="col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Free Shipping') }}</label>
            <div class="col-span-1">
                {!! $free_shipping ? '<span class="block cursor-pointer material-icons text-green-600 select-none" wire:click="freeShipping">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="freeShipping">toggle_off</span>' !!}

                @error('free_shipping')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Free Shipping End --}}

    </div>
    {{-- Offer Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    {{-- New Offer Items Start --}}
    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-gray-100 p-4 text-center  rounded shadow">

        <div class="col-span-12 font-bold text-black mb-2">
            {{ __("admin/offersPages.Offer's Items") }}
        </div>

        @foreach ($items as $item_key => $item)
            {{-- Item Start --}}
            <div class="col-span-12 bg-gray-200 w-full h-full p-3 grid grid-cols-12 gap-x-4 gap-y-4 rounded-xl items-center"
                wire:key="item-{{ $item_key }}">

                <div class="col-span-12 bg-gray-700 p-3 rounded-xl flex flex-wrap justify-around items-center gap-4">

                    {{-- Model Type : Start --}}
                    <div class="flex items-center justify-between gap-x-4">
                        <div class="flex gap-x-3 items-center">
                            <label for="items.{{ $item_key }}.category"
                                class="text-white m-0 font-bold cursor-pointer select-none">{{ __('admin/offersPages.Website Categories') }}</label>
                            <input id="items.{{ $item_key }}.category" type="radio" value="category"
                                wire:change="modelChanged({{ $item_key }})"
                                class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                                wire:model="items.{{ $item_key }}.item_type">
                        </div>
                        <div class="flex gap-x-3 items-center">
                            <label for="items.{{ $item_key }}.brand"
                                class="text-white m-0 font-bold cursor-pointer select-none">{{ __('admin/offersPages.Brands') }}</label>
                            <input id="items.{{ $item_key }}.brand" type="radio" value="brand"
                                wire:change="modelChanged({{ $item_key }})"
                                class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                                wire:model="items.{{ $item_key }}.item_type">
                        </div>

                        <div class="flex gap-x-3 items-center">
                            <label for="items.{{ $item_key }}.order"
                                class="text-white m-0 font-bold cursor-pointer select-none">{{ __('admin/offersPages.Orders') }}</label>
                            <input id="items.{{ $item_key }}.order" type="radio" value="order"
                                wire:change="modelChanged({{ $item_key }})"
                                class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                                wire:model="items.{{ $item_key }}.item_type">
                        </div>
                    </div>
                    {{-- Model Type : End --}}

                    {{-- Select All button : Start --}}
                    <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn @if (empty($items[$item_key]['products'])) hidden @endif "
                        wire:click="selectAll({{ $item_key }})"
                        title="{{ __('admin/offersPages.Select All') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="inline-block w-6 h-6">
                            <path fill="currentColor"
                                d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Zm-7.665 7.858L13.47 7.47a.75.75 0 0 1 1.133.976l-.073.084l-4.5 4.5a.75.75 0 0 1-1.056.004L8.9 12.95l-1.5-2a.75.75 0 0 1 1.127-.984l.073.084l.981 1.308L13.47 7.47l-3.89 3.888Z" />
                        </svg>
                    </div>
                    {{-- Select All button : End --}}

                    {{-- Deselect All button : Start --}}
                    <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn
                    @if (empty($items[$item_key]['products_id'])) hidden @endif
                    "
                        wire:click="deselectAll({{ $item_key }})"
                        title="{{ __('admin/offersPages.Deselect All') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="inline-block w-6 h-6">
                            <path fill="currentColor"
                                d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Z" />
                        </svg>
                    </div>
                    {{-- Deselect All button : End --}}

                    {{-- Delete Item : Start --}}
                    <div class="text-black bg-white rounded-full shadow w-6 h-6 flex justify-center items-center cursor-pointer"
                        wire:click="deleteItem({{ $item_key }})"
                        title="{{ __('admin/offersPages.Delete Item') }}">
                        <span class="material-icons font-bold text-sm">
                            close
                        </span>
                    </div>
                    {{-- Delete Item : End --}}

                </div>

                @if ($items[$item_key]['item_type'] == 'brand')
                    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-4" wire:key="brand-{{ $item_key }}">

                        {{-- Type Start --}}
                        <div class="col-span-6 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="brand-{{ $item_key }}-type">
                            <label for="brand-{{ $item_key }}-type"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __("admin/offersPages.Discount's Type") }}</label>
                            <div class="col-span-3">

                                <select wire:model="items.{{ $item_key }}.type"
                                    id="brand-{{ $item_key }}-type"
                                    class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.type') border-red-900 border-2 @enderror">
                                    <option value="0">{{ __('admin/offersPages.Percentage') }}</option>
                                    <option value="1">{{ __('admin/offersPages.Fixed Discount') }}</option>
                                    <option value="2">{{ __('admin/offersPages.Points') }}</option>
                                </select>

                                @error('items.' . $item_key . '.type')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Type End --}}

                        {{-- Value Start --}}
                        <div class="col-span-6 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="brand-{{ $item_key }}-value">
                            <label for="brand-{{ $item_key }}-value"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Value') }}</label>
                            <div class="col-span-3">
                                <input id="brand-{{ $item_key }}-value"
                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.value') border-red-900 border-2 @enderror"
                                    type="number" min="0" wire:model.lazy="items.{{ $item_key }}.value"
                                    placeholder="{{ __('admin/offersPages.Enter Value') }}" maxlength="100">

                                @error('items.' . $item_key . '.value')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Value End --}}

                        {{-- Times : Start --}}
                        <div class="col-span-12 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="brand-{{ $item_key }}-offer_number">
                            <label for="brand-{{ $item_key }}-offer_number"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Number of offers') }}</label>
                            <div class="col-span-3">
                                <input id="brand-{{ $item_key }}-offer_number"
                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.offer_number') border-red-900 border-2 @enderror"
                                    type="number" min="0" wire:model.lazy="items.{{ $item_key }}.offer_number"
                                    placeholder="{{ __('admin/offersPages.Unlimited') }}">

                                @error('items.' . $item_key . '.offer_number')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Times : End --}}

                        {{-- Brands Start --}}
                        <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="brand-{{ $item_key }}-block">
                            <label for="item-{{ $item_key }}-brand_id" wire:key="brand-{{ $item_key }}-label"
                                class="col-span-12 sm:col-span-6 sm:col-start-4 lg:col-span-4 lg:col-start-5 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">
                                {{ __('admin/offersPages.Brand') }}
                            </label>

                            <div class="col-span-12 sm:col-span-6 sm:col-start-4 lg:col-span-4 lg:col-start-5">
                                <select wire:model="items.{{ $item_key }}.brand_id"
                                    wire:change="brandUpdated({{ $item_key }})"
                                    wire:key="brand-{{ $item_key }}-select" id="item-{{ $item_key }}-brand_id"
                                    class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.brand_id') border-red-900 border-2 @enderror">
                                    <option value="all">
                                        {{ __('admin/offersPages.All Products in Website') }}
                                    </option>
                                    @forelse ($brands as $brand)
                                        <option value="{{ $brand['id'] }}">
                                            {{ $brand['name'] }}
                                        </option>
                                    @empty
                                        <option value="0">
                                            {{ __('admin/offersPages.No Brands in Database') }}
                                        </option>
                                    @endforelse
                                </select>

                                @error('items.' . $item_key . '.brand_id')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Brands End --}}

                        {{-- Products : Start --}}
                        @if (!empty($items[$item_key]['products']))
                            <div class="col-span-12 grid grid-cols-3 items-center gap-3"
                                wire:key="item-{{ $item_key }}-products">
                                <label
                                    class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">
                                    {{ __('admin/offersPages.Products') }}
                                </label>

                                <div class="flex flex-wrap col-span-3 gap-2 px-2 justify-center">
                                    @foreach ($items[$item_key]['products'] as $product_key => $product)
                                        <label for="item-{{ $item_key }}-product-{{ $product_key }}"
                                            wire:key="item-{{ $item_key }}-product-{{ $product_key }}-label"
                                            class="bg-red-200 px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($product['id'], $items[$item_key]['products_id'])) bg-green-200 @endif select-none">
                                            {{ $product['name'][session('locale')] }}
                                            <input type="checkbox" wire:model="items.{{ $item_key }}.products_id"
                                                wire:key="item-{{ $item_key }}-product-{{ $product_key }}-input"
                                                id="item-{{ $item_key }}-product-{{ $product_key }}"
                                                value="{{ $product['id'] }}" class="hidden">
                                        </label>
                                    @endforeach
                                </div>

                                @error('items.' . $item_key . '.products_id')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        {{-- Products : End --}}
                    </div>
                @elseif ($items[$item_key]['item_type'] == 'category')
                    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-4" wire:key="category-{{ $item_key }}">

                        {{-- Type Start --}}
                        <div class="col-span-6 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="category-{{ $item_key }}-type">
                            <label for="category-{{ $item_key }}-type"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __("admin/offersPages.Discount's Type") }}</label>
                            <div class="col-span-3">

                                <select wire:model="items.{{ $item_key }}.type"
                                    id="category-{{ $item_key }}-type"
                                    class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.type') border-red-900 border-2 @enderror">
                                    <option value="0">{{ __('admin/offersPages.Percentage') }}</option>
                                    <option value="1">{{ __('admin/offersPages.Fixed Discount') }}</option>
                                    <option value="2">{{ __('admin/offersPages.Points') }}</option>
                                </select>

                                @error('items.' . $item_key . '.type')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Type End --}}

                        {{-- Value Start --}}
                        <div class="col-span-6 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="category-{{ $item_key }}-value">
                            <label for="category-{{ $item_key }}-value"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Value') }}</label>
                            <div class="col-span-3">
                                <input id="category-{{ $item_key }}-value"
                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.value') border-red-900 border-2 @enderror"
                                    type="number" min="0" wire:model.lazy="items.{{ $item_key }}.value"
                                    placeholder="{{ __('admin/offersPages.Enter Value') }}" maxlength="100">

                                @error('items.' . $item_key . '.value')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Value End --}}

                        {{-- Times : Start --}}
                        <div class="col-span-12 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="category-{{ $item_key }}-offer_number">
                            <label for="category-{{ $item_key }}-offer_number"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Number of offers') }}</label>
                            <div class="col-span-3">
                                <input id="category-{{ $item_key }}-offer_number"
                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.offer_number') border-red-900 border-2 @enderror"
                                    type="number" min="0" wire:model.lazy="items.{{ $item_key }}.offer_number"
                                    placeholder="{{ __('admin/offersPages.Unlimited') }}">

                                @error('items.' . $item_key . '.offer_number')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Times : End --}}

                        {{-- Supercategory Start --}}
                        <div class="col-span-12 md:col-span-6 lg:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="supercategory-{{ $item_key }}-block">
                            <label for="supercategory-{{ $item_key }}"
                                wire:key="supercategory-{{ $item_key }}-label"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Supercategory') }}</label>
                            <div class="col-span-3" wire:key="supercategory-{{ $item_key }}-select">
                                <select wire:model="items.{{ $item_key }}.supercategory_id"
                                    wire:change="supercategoryUpdated({{ $item_key }})"
                                    id="supercategory-{{ $item_key }}"
                                    class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.supercategory_id') border-red-900 border-2 @enderror">
                                    <option value="all">
                                        {{ __('admin/offersPages.All Products in Website') }}
                                    </option>
                                    @forelse ($supercategories as $supercategory)
                                        <option value="{{ $supercategory['id'] }}">
                                            {{ $supercategory['name'][session('locale')] }}
                                        </option>
                                    @empty
                                        <option value="0">
                                            {{ __('admin/offersPages.No Supercategries in Database') }}
                                        </option>
                                    @endforelse
                                </select>

                                @error('items.' . $item_key . '.supercategory_id')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Supercategory End --}}

                        {{-- Category Start --}}
                        @if (!empty($items[$item_key]['categories']))
                            <div
                                class="col-span-12 md:col-span-6 lg:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                                <label for="category-{{ $item_key }}"
                                    wire:key="category-{{ $item_key }}-label"
                                    class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Category') }}</label>
                                <div class="col-span-3">
                                    <select wire:model="items.{{ $item_key }}.category_id"
                                        wire:change="categoryUpdated({{ $item_key }})"
                                        id="category-{{ $item_key }}"
                                        class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.category_id') border-red-900 border-2 @enderror">
                                        <option value="all">
                                            {{ __('admin/offersPages.All Products in the Supercategory') }}
                                        </option>
                                        @foreach ($items[$item_key]['categories'] as $category)
                                            <option value="{{ $category['id'] }}">
                                                {{ $category['name'][session('locale')] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('items.' . $item_key . '.category_id')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        {{-- Category End --}}

                        {{-- Subcategory Start --}}
                        @if (!empty($items[$item_key]['subcategories']))
                            <div
                                class="col-span-12 md:col-span-6 md:col-start-4 lg:col-span-4 lg:col-start-0 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                                <label for="subcategory-{{ $item_key }}"
                                    wire:key="subcategory-{{ $item_key }}-label"
                                    class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Subcategory') }}</label>
                                <div class="col-span-3">
                                    <select wire:model="items.{{ $item_key }}.subcategory_id"
                                        wire:change="subcategoryUpdated({{ $item_key }})"
                                        id="subcategory-{{ $item_key }}"
                                        class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('items.' . $item_key . '.subcategory_id') border-red-900 border-2 @enderror">
                                        <option value="all">
                                            {{ __('admin/offersPages.All Products in the Category') }}
                                        </option>
                                        @foreach ($items[$item_key]['subcategories'] as $subcategory)
                                            <option value="{{ $subcategory['id'] }}">
                                                {{ $subcategory['name'][session('locale')] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('items.' . $item_key . '.subcategory_id')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        {{-- Subcategory End --}}

                        {{-- Products : Start --}}
                        @if (!empty($items[$item_key]['products']))
                            <div class="col-span-12 grid grid-cols-3 items-center gap-3"
                                wire:key="item-{{ $item_key }}-products">
                                <label
                                    class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">
                                    {{ __('admin/offersPages.Products') }}
                                </label>

                                <div class="flex flex-wrap col-span-3 gap-2 px-2 justify-center">
                                    @foreach ($items[$item_key]['products'] as $product_key => $product)
                                        <label for="item-{{ $item_key }}-product-{{ $product_key }}"
                                            wire:key="item-{{ $item_key }}-product-{{ $product_key }}-label"
                                            class="bg-red-200 px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($product['id'], $items[$item_key]['products_id'])) bg-green-200 @endif select-none">
                                            {{ $product['name'][session('locale')] }}
                                            <input type="checkbox" wire:model="items.{{ $item_key }}.products_id"
                                                wire:key="item-{{ $item_key }}-product-{{ $product_key }}-input"
                                                id="item-{{ $item_key }}-product-{{ $product_key }}"
                                                value="{{ $product['id'] }}" class="hidden">
                                        </label>
                                    @endforeach
                                </div>

                                @error('items.' . $item_key . '.products_id')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        {{-- Products : End --}}
                    </div>
                @elseif ($items[$item_key]['item_type'] == 'order')
                    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-4" wire:key="offer-{{ $item_key }}">

                        {{-- Type Start --}}
                        <div class="col-span-6 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="offer-{{ $item_key }}-type">
                            <label for="offer-{{ $item_key }}-type"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __("admin/offersPages.Discount's Type") }}</label>
                            <div class="col-span-3">

                                <select wire:model="type" id="offer-{{ $item_key }}-type"
                                    class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('type') border-red-900 border-2 @enderror">
                                    <option value="0">{{ __('admin/offersPages.Percentage') }}</option>
                                    <option value="1">{{ __('admin/offersPages.Fixed Discount') }}</option>
                                    <option value="2">{{ __('admin/offersPages.Points') }}</option>
                                </select>

                                @error('type')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Type End --}}

                        {{-- Value Start --}}
                        <div class="col-span-6 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="offer-{{ $item_key }}-value">
                            <label for="offer-{{ $item_key }}-value"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Value') }}</label>
                            <div class="col-span-3">
                                <input id="offer-{{ $item_key }}-value"
                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('value') border-red-900 border-2 @enderror"
                                    type="number" min="0" wire:model.lazy="value"
                                    placeholder="{{ __('admin/offersPages.Enter Value') }}" maxlength="100">

                                @error('value')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Value End --}}

                        {{-- Times : Start --}}
                        <div class="col-span-12 md:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="offer-{{ $item_key }}-offer_number">
                            <label for="offer-{{ $item_key }}-offer_number"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Number of offers') }}</label>
                            <div class="col-span-3">
                                <input id="offer-{{ $item_key }}-offer_number"
                                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('offer_number') border-red-900 border-2 @enderror"
                                    type="number" min="0" wire:model.lazy="offer_number"
                                    placeholder="{{ __('admin/offersPages.Unlimited') }}">

                                @error('offer_number')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- Times : End --}}
                    </div>
                @endif



            </div>
            {{-- Item End --}}
        @endforeach

        {{-- Add Item Start --}}
        <div class="col-span-12">
            <div class="text-center">
                <a href="#" wire:click.prevent="addItem"
                    class="btn btn-sm bg-gray-600 hover:bg-gray-700 focus:bg-gray-600 active:bg-gray-600 font-bold">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/offersPages.Add Item') }}</a>
            </div>
        </div>
        {{-- Add Item End --}}

    </div>
    {{-- New Offer Items End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Old Items Start --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    @if ($offer_id)
        <div
            class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center  rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/offersPages.Old Items') }}
            </div>

            <div class="table-responsive  col-span-12">
                <table class="w-100 table-bordered table-hover">
                    <tbody class="text-center">
                        @if (isset($oldSupercategories) && count($oldSupercategories))
                            <tr>
                                <th class="bg-primary text-white w-1/4">
                                    {{ __('admin/offersPages.Supercategories') }}</th>
                                <td class="p-2 bg-white">
                                    <div class="flex flex-wrap justify-center gap-3 items-center h-100">
                                        @foreach ($oldSupercategories as $supercategory_key => $supercategory)
                                            <label for="old-supercategory-{{ $supercategory_key }}"
                                                wire:key="old-supercategory-{{ $supercategory_key }}"
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($supercategory['id'], $deleteSupercategories_id)) bg-red-200 @else bg-green-200 @endif select-none m-0">
                                                <span>
                                                    {{ $supercategory['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $supercategory['pivot']['value'] }}
                                                    {{ $supercategory['pivot']['type'] == 0? '%': ($supercategory['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($supercategory['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $supercategory['pivot']['value'], ['points' => $supercategory['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $supercategory['pivot']['number'] }}
                                                </span>

                                                <input type="checkbox" wire:model="deleteSupercategories_id"
                                                    id="old-supercategory-{{ $supercategory_key }}"
                                                    value="{{ $supercategory['id'] }}" class="hidden">
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @if (isset($oldCategories) && count($oldCategories))
                            <tr>
                                <th class="bg-primary text-white w-1/4">{{ __('admin/offersPages.Categories') }}
                                </th>
                                <td class="p-2 bg-white">
                                    <div class="flex flex-wrap justify-center gap-3 items-center h-100">
                                        @foreach ($oldCategories as $category_key => $category)
                                            <label for="old-category-{{ $category_key }}"
                                                wire:key="old-category-{{ $category_key }}"
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($category['id'], $deleteCategories_id)) bg-red-200 @else bg-green-200 @endif select-none m-0">
                                                <span>
                                                    {{ $category['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $category['pivot']['value'] }}
                                                    {{ $category['pivot']['type'] == 0? '%': ($category['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($category['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $category['pivot']['value'], ['points' => $category['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $category['pivot']['number'] }}
                                                </span>

                                                <input type="checkbox" wire:model="deleteCategories_id"
                                                    id="old-category-{{ $category_key }}"
                                                    value="{{ $category['id'] }}" class="hidden">
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @if (isset($oldSubcategories) && count($oldSubcategories))
                            <tr>
                                <th class="bg-primary text-white w-1/4">{{ __('admin/offersPages.Subcategories') }}
                                </th>
                                <td class="p-2 bg-white">
                                    <div class="flex flex-wrap justify-center gap-3 items-center h-100">
                                        @foreach ($oldSubcategories as $subcategory_key => $subcategory)
                                            <label for="old-subcategory-{{ $subcategory_key }}"
                                                wire:key="old-subcategory-{{ $subcategory_key }}"
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($subcategory['id'], $deleteSubcategories_id)) bg-red-200 @else bg-green-200 @endif select-none m-0">
                                                <span>
                                                    {{ $subcategory['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $subcategory['pivot']['value'] }}
                                                    {{ $subcategory['pivot']['type'] == 0? '%': ($subcategory['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($subcategory['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $subcategory['pivot']['value'], ['points' => $subcategory['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $subcategory['pivot']['number'] }}
                                                </span>

                                                <input type="checkbox" wire:model="deleteSubcategories_id"
                                                    id="old-subcategory-{{ $subcategory_key }}"
                                                    value="{{ $subcategory['id'] }}" class="hidden">
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @if (isset($oldBrands) && count($oldBrands))
                            <tr>
                                <th class="bg-primary text-white w-1/4">{{ __('admin/offersPages.Brands') }}</th>
                                <td class="p-2 bg-white">
                                    <div class="flex flex-wrap justify-center gap-3 items-center h-100">
                                        @foreach ($oldBrands as $brand_key => $brand)
                                            <label for="old-brand-{{ $brand_key }}"
                                                wire:key="old-brand-{{ $brand_key }}"
                                                class="px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($brand['id'], $deleteBrands_id)) bg-red-200 @else bg-green-200 @endif select-none m-0">
                                                <span>
                                                    {{ $brand['name'] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $brand['pivot']['value'] }}
                                                    {{ $brand['pivot']['type'] == 0? '%': ($brand['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($brand['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $brand['pivot']['value'], ['points' => $brand['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $brand['pivot']['number'] }}
                                                </span>

                                                <input type="checkbox" wire:model="deleteBrands_id"
                                                    id="old-brand-{{ $brand_key }}" value="{{ $brand['id'] }}"
                                                    class="hidden">
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @if (isset($oldProducts) && count($oldProducts))
                            <tr>
                                <th class="bg-primary text-white w-1/4">{{ __('admin/offersPages.Products') }}</th>
                                <td class="p-2 bg-white">
                                    <div class="flex flex-wrap justify-center gap-3 items-center h-100">
                                        @foreach ($oldProducts as $product_key => $product)
                                            <label for="old-product-{{ $product_key }}"
                                                wire:key="old-product-{{ $product_key }}"
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($product['id'], $deleteProducts_id)) bg-red-200 @else bg-green-200 @endif select-none m-0">
                                                <span>
                                                    {{ $product['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $product['pivot']['value'] }}
                                                    {{ $product['pivot']['type'] == 0? '%': ($product['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($product['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $product['pivot']['value'], ['points' => $product['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $product['pivot']['number'] }}
                                                </span>

                                                <input type="checkbox" wire:model="deleteProducts_id"
                                                    id="old-product-{{ $product_key }}"
                                                    value="{{ $product['id'] }}" class="hidden">
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif

                        @if ($on_orders)
                            <tr>
                                <th class="bg-primary text-white w-1/4">{{ __('admin/offersPages.Orders') }}</th>
                                <td class="p-2 bg-white">
                                    <div class="flex flex-wrap justify-center gap-3 items-center h-100">
                                        <input
                                            class="appearance-none border-red-900 rounded-full checked:bg-primary outline-none ring-0 cursor-pointer"
                                            type="checkbox" wire:model="on_orders" value="1">
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    @endif
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    {{-- Old Items End --}}


    {{-- Buttons Section Start --}}
    <div class="col-span-12 w-full flex flex-wrap mt-2 justify-around">
        @if ($offer_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Save and Add New Offer') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.offers.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Back') }}</a>

    </div>
    {{-- Buttons Section End --}}

    {{-- extra Js : Start --}}
    @if ($offer_id != null)
        @push('js')
            @livewireScripts

            <script src="{{ asset('assets/js/plugins/daterangepicker-master/daterangepicker.js') }}"></script>

            <script>
                $(function() {
                    $('input[name="date_range"]').daterangepicker({
                        "minYear": 2022,
                        "timePicker": true,
                        "startDate": "{{ $date_range['start'] }}",
                        "endDate": "{{ $date_range['end'] }}",
                        "opens": "center",
                        "drops": "auto",
                        "applyButtonClasses": "btn-success",
                        "cancelClass": "btn-danger",
                        "showDropdowns": true,
                        locale: {
                            format: 'YYYY-MM-DD hh:mm A',
                        }
                    }, function(start, end, label) {
                        Livewire.emit('daterangeUpdated', start.format('YYYY-MM-DD H:mm'), end.format('YYYY-MM-DD H:mm'));
                    });
                });
            </script>
        @endpush
    @endif
    {{-- extra Js : End --}}

</div>

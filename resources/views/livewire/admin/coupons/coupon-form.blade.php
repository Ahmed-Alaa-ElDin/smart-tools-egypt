<div class="grid grid-cols-12 gap-3 items-start">

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    {{-- Coupon Information Start --}}
    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center  rounded shadow">

        <div class="col-span-12 font-bold text-black mb-2">
            {{ __("admin/offersPages.Coupon's Information") }}
        </div>

        {{-- Code Start --}}
        <div class="col-span-12 md:col-span-5 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
            <label for="code"
                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Code') }}</label>
            <div class="col-span-3">
                <input id="code"
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('code') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="code" placeholder="{{ __('admin/offersPages.Enter Code') }}"
                    maxlength="100">

                @error('code')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Code End --}}

        {{-- Expiration Date Start --}}
        <div class="col-span-6 md:col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
            <label for="expire_at"
                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Expiration Date') }}</label>
            <div class="col-span-3">
                <input id="expire_at"
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('expire_at') border-red-900 border-2 @enderror"
                    type="date" wire:model.lazy="expire_at" placeholder="{{ __('admin/offersPages.Select Date') }}">

                @error('expire_at')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Expiration Date End --}}

        {{-- Times Start --}}
        <div class="col-span-6 md:col-span-2 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
            <label for="number"
                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Number of coupons') }}</label>
            <div class="col-span-3">
                <input id="number"
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('number') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="number" placeholder="{{ __('admin/offersPages.Unlimited') }}">

                @error('number')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Times End --}}

        {{-- Free Shipping Start --}}
        <div class="col-span-6 col-start-4 md:col-span-2 grid grid-cols-2 gap-y-2 gap-x-2 items-center w-full">
            <label wire:click="freeShipping"
                class="col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Free Shipping') }}</label>
            <div class="col-span-2">
                {!! $free_shipping ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="freeShipping">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="freeShipping">toggle_off</span>' !!}

                @error('free_shipping')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Free Shipping End --}}

    </div>
    {{-- Coupon Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    {{-- New Coupon Items Start --}}
    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-gray-100 p-4 text-center  rounded shadow">

        <div class="col-span-12 font-bold text-black mb-2">
            {{ __("admin/offersPages.Coupon's Items") }}
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
                    <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn @if (empty($items[$item_key]['products'])) hidden @endif"
                        wire:click="selectAll({{ $item_key }})" data-toggle="tooltip"
                        data-title="{{ __('admin/offersPages.Select All') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="inline-block w-6 h-6">
                            <path fill="currentColor"
                                d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Zm-7.665 7.858L13.47 7.47a.75.75 0 0 1 1.133.976l-.073.084l-4.5 4.5a.75.75 0 0 1-1.056.004L8.9 12.95l-1.5-2a.75.75 0 0 1 1.127-.984l.073.084l.981 1.308L13.47 7.47l-3.89 3.888Z" />
                        </svg>
                    </div>
                    {{-- Select All button : End --}}

                    {{-- Deselect All button : Start --}}
                    <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn
                    @if (empty($items[$item_key]['products'])) hidden @endif"
                        wire:click="deselectAll({{ $item_key }})" data-toggle="tooltip"
                        data-title="{{ __('admin/offersPages.Deselect All') }}">
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
                    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-4">

                        {{-- Type Start --}}
                        <div class="col-span-6 md:col-span-4 md:col-start-3 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
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

                        {{-- Brands Start --}}
                        <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="brand-{{ $item_key }}-block">
                            <label for="item-{{ $item_key }}-brand_id" wire:key="brand-{{ $item_key }}-label"
                                class="col-span-12 sm:col-span-6 sm:col-start-4 lg:col-span-4 lg:col-start-5 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">
                                {{ __('admin/offersPages.Brand') }}
                            </label>

                            <div class="col-span-12 sm:col-span-6 sm:col-start-4 lg:col-span-4 lg:col-start-5">
                                <select wire:model="items.{{ $item_key }}.brand_id"
                                    wire:change="$emit('brandUpdated',{{ $item_key }})"
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
                                            class="bg-red-200 px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($product['id'], $items[$item_key]['products_id'])) bg-green-300 @endif select-none">
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
                    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-4">
                        {{-- Type Start --}}
                        <div class="col-span-6 md:col-span-4 md:col-start-3 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
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

                        {{-- Supercategory Start --}}
                        <div class="col-span-12 md:col-span-6 lg:col-span-4 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
                            wire:key="supercategory-{{ $item_key }}-block">
                            <label for="supercategory-{{ $item_key }}"
                                wire:key="supercategory-{{ $item_key }}-label"
                                class="col-span-3 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/offersPages.Supercategory') }}</label>
                            <div class="col-span-3" wire:key="supercategory-{{ $item_key }}-select">
                                <select wire:model="items.{{ $item_key }}.supercategory_id"
                                    wire:change="$emit('supercategoryUpdated',{{ $item_key }})"
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
                                        wire:change="$emit('categoryUpdated',{{ $item_key }})"
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
                                        wire:change="$emit('subcategoryUpdated',{{ $item_key }})"
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
                                            class="bg-red-200 px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($product['id'], $items[$item_key]['products_id'])) bg-green-300 @endif select-none">
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
                        <div class="col-span-6 md:col-span-4 md:col-start-3 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full"
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
    {{-- New Coupon Items End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Old Items Start --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    @if ($coupon_id)
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
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($supercategory['id'], $deleteSupercategories_id)) bg-red-200 @else bg-green-300 @endif select-none m-0">
                                                <span>
                                                    {{ $supercategory['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $supercategory['pivot']['value'] }}
                                                    {{ $supercategory['pivot']['type'] == 0? '%': ($supercategory['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($supercategory['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $supercategory['pivot']['value'], ['points' => $supercategory['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
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
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($category['id'], $deleteCategories_id)) bg-red-200 @else bg-green-300 @endif select-none m-0">
                                                <span>
                                                    {{ $category['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $category['pivot']['value'] }}
                                                    {{ $category['pivot']['type'] == 0? '%': ($category['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($category['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $category['pivot']['value'], ['points' => $category['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
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
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($subcategory['id'], $deleteSubcategories_id)) bg-red-200 @else bg-green-300 @endif select-none m-0">
                                                <span>
                                                    {{ $subcategory['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $subcategory['pivot']['value'] }}
                                                    {{ $subcategory['pivot']['type'] == 0? '%': ($subcategory['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($subcategory['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $subcategory['pivot']['value'], ['points' => $subcategory['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
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
                                                class="px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($brand['id'], $deleteBrands_id)) bg-red-200 @else bg-green-300 @endif select-none m-0">
                                                <span>
                                                    {{ $brand['name'] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $brand['pivot']['value'] }}
                                                    {{ $brand['pivot']['type'] == 0? '%': ($brand['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($brand['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $brand['pivot']['value'], ['points' => $brand['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
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
                                                class=" px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($product['id'], $deleteProducts_id)) bg-red-200 @else bg-green-300 @endif select-none m-0">
                                                <span>
                                                    {{ $product['name'][session('locale')] }}
                                                </span>
                                                <br>
                                                <span>
                                                    {{ $product['pivot']['value'] }}
                                                    {{ $product['pivot']['type'] == 0? '%': ($product['pivot']['type'] == 1? __('admin/offersPages.EPG'): ($product['pivot']['type'] == 2? trans_choice('admin/offersPages.Points value', $product['pivot']['value'], ['points' => $product['pivot']['value']]): __('admin/offersPages.Free Shipping'))) }}
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
        @if ($coupon_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Save and Add New Coupon') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.coupons.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/offersPages.Back') }}</a>

    </div>
    {{-- Buttons Section End --}}

</div>

<div class="grid grid-cols-12 gap-3 items-start">

    {{-- Big Side Start --}}
    <div class="col-span-12 lg:col-span-8 w-full grid gap-3">

        {{-- Media Start --}}
        <div
            class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-4 text-center justify-items-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">{{ __('admin/productsPages.Product Media') }}</div>

            {{-- Loading Spinner --}}
            <div wire:loading wire:target="gallery_images" class="col-span-12 my-2">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                    <path fill="currentColor"
                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                </svg>
                <span> &nbsp;&nbsp; {{ __('admin/productsPages.Uploading ...') }}</span>
            </div>

            {{-- preview --}}
            @if (!empty($temp_path))
                <div class="col-span-12  w-full">
                    <div class="text-center flex flex-wrap gap-3 justify-around">
                        @foreach ($temp_path as $key[] => $temp_path_image)
                        <div class="relative w-25">
                            <span class="absolute rounded-circle bg-red-500 w-5 h-5 text-white left-2 top-2 text-bold">X</span>
                            <img src="{{ $temp_path_image }}" class="rounded-xl">
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3 text-center">
                        <button class="btn btn-danger btn-sm text-bold"
                            wire:click.prevent='removePhoto'>{{ __('admin/productsPages.Remove / Replace Product Image') }}</button>
                    </div>
                </div>
            @elseif (!empty($oldImage))
                <div class="col-span-12">
                    <div class="col-span-12 text-center">
                        <img src="{{ asset('storage/images/products/original/' . $oldImage) }}"
                            class="rounded-xl m-auto">
                    </div>
                    <div class="col-span-12 text-center">
                        <button class="btn btn-danger btn-sm text-bold"
                            wire:click.prevent='removePhoto'>{{ __('admin/productsPages.Remove / Replace Product Image') }}</button>
                    </div>
                </div>
            @else
                {{-- Upload New Image --}}
                <label for="gallery_images" class="col-span-12 text-xs text-gray-700 font-bold m-0 text-center">
                    {{ __('admin/productsPages.Gallery Images') }} </label>
                <input
                    class="col-span-12 form-control block w-full px-2 py-1 text-sm font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                    id="gallery_images" type="file" type="image" wire:model.lazy="gallery_images" multiple>

                @error('gallery_images')
                    <span class="bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                @enderror
            @endif
        </div>
        {{-- Images End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}


        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Product Information Start --}}
        <div
            class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center justify-items-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">{{ __('admin/productsPages.Product Information') }}
            </div>

            {{-- Name Start --}}
            <div
                class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 rounded text-center">
                <label for="name"
                    class="col-span-12 md:col-span-2 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Name') }}</label>
                {{-- Name Ar --}}
                <div class="col-span-6 md:col-span-5">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('name.ar') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="name.ar" id="name"
                        placeholder="{{ __('admin/productsPages.in Arabic') }}">
                    @error('name.ar')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Name En --}}
                <div class="col-span-6 md:col-span-5 ">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('name.en') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="name.en"
                        placeholder="{{ __('admin/productsPages.in English') }}">
                    @error('name.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Name End --}}

            {{-- Brand Start --}}
            <div class="col-span-6 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                <label for="brand_id"
                    class="col-span-12 sm:col-span-4 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Brand') }}</label>
                <div class="col-span-12 sm:col-span-8 ">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('brand_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="brand_id" id="brand_id">
                        @if ($categories->count())
                            <option value="">
                                {{ __('admin/productsPages.Choose a brand') }}
                            </option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ __($brand->name) }}
                                </option>
                            @endforeach
                        @else
                            <option value="">
                                {{ __('admin/productsPages.No Brands in the database') }}
                            </option>
                        @endif
                    </select>

                    @error('brand_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Brand End --}}

            {{-- Category Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="subcategory_id"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Subcategory') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('subcategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="subcategory_id" id="subcategory_id">
                        @if ($categories->count())
                            <option value="">
                                {{ __('admin/productsPages.Choose a subcategories') }}
                            </option>
                            @foreach ($categories as $category)
                                <option disabled>
                                    {{ __($category->name) }}
                                </option>
                                @foreach ($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">
                                        {{ __($subcategory->name) }}
                                    </option>
                                @endforeach
                            @endforeach
                        @else
                            <option value="">
                                {{ __('admin/productsPages.No Subcategories in the database') }}
                            </option>
                        @endif
                    </select>

                    @error('subcategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Category End --}}

            {{-- Model Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="model"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Model') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input id="model"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('model') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="model" placeholder="{{ __('admin/productsPages.Model') }}">

                    @error('model')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Model End --}}

            {{-- Barcode Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="barcode"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Barcode') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input id="barcode"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('barcode') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="barcode" placeholder="{{ __('admin/productsPages.Barcode') }}">

                    @error('barcode')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Barcode End --}}

            {{-- Weight Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="weight"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Weight') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('weight') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="weight" id="weight"
                        placeholder="{{ __('admin/productsPages.Weight in Kg.') }}">

                    @error('weight')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Weight End --}}

            {{-- Publish Start --}}
            <div class="col-span-3 grid grid-cols-2 gap-y-2 gap-x-2 items-center w-full">
                <label wire:click="publish"
                    class="col-span-2 md:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Publish') }}</label>
                <div class="col-span-2 md:col-span-1">
                    {!! $publish ? '<span class="block cursor-pointer material-icons text-green-600 select-none" wire:click="publish">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="publish">toggle_off</span>' !!}

                    @error('publish')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>

            </div>
            {{-- Publish End --}}

            {{-- Refundable Start --}}
            <div class="col-span-3 grid grid-cols-2 gap-y-2 items-center w-full">
                <label wire:click="refund"
                    class="col-span-2 md:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Refundable') }}</label>
                <div class="col-span-2 md:col-span-1">
                    {!! $refundable ? '<span class="block cursor-pointer material-icons text-green-600 select-none" wire:click="refund">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="refund">toggle_off</span>' !!}

                    @error('refundable')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Refundable End --}}

            {{-- Description Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                <label
                    class="col-span-12 md:col-span-2 font-bold m-0 text-center font-bold text-xs text-gray-700">{{ __('admin/productsPages.Description') }}</label>

                {{-- Description Ar --}}
                <div class="col-span-6 md:col-span-5">
                    <div wire:ignore
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 cursor-text @error('description.ar') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="description.ar" id="description_ar"
                        placeholder="{{ __('admin/productsPages.in Arabic') }}">
                    </div>

                    @error('description.ar')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Description En --}}
                <div wire:ignore class="col-span-6 md:col-span-5 ">
                    <div class="py-1 w-full px-6 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 cursor-text @error('description.en') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="description.en" id="description_en"
                        placeholder="{{ __('admin/productsPages.in English') }}">
                    </div>
                </div>

                @error('description.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            {{-- Description End --}}

        </div>
    </div>
    {{-- Product Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Big Side End --}}

    {{-- Small Side Start --}}
    <div class="col-span-12 lg:col-span-4 w-full grid gap-3">

        {{-- Pricing and Stock Start --}}
        <div
            class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center justify-items-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">{{ __('admin/productsPages.Pricing and Stock') }}
            </div>

            {{-- Base Price Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="base_price"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Base Price') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('base_price') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="base_price" id="base_price"
                        placeholder="{{ __('admin/productsPages.EGP') }}">
                    @error('base_price')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Base Price End --}}

            {{-- Discount Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="discount"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Discount') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('discount') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="discount" id="discount"
                        placeholder="{{ __('admin/productsPages.Percentage') }}">
                    @error('discount')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Discount Start --}}

            {{-- Final Price Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-12 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="final_price"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Final Price') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('final_price') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="final_price" id="final_price"
                        placeholder="{{ __('admin/productsPages.EGP') }}">
                    @error('final_price')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Final Price End --}}

            {{-- Points Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="points"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Points') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('points') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="points" id="points"
                        placeholder="{{ __('admin/productsPages.Points') }}">
                    @error('points')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Points End --}}

            {{-- Free Shipping Start --}}
            <div
                class="col-span-12 sm:col-span-12 md:col-span-6 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="free_shipping" wire:click="free_shipping"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer select-none">{{ __('admin/productsPages.Free Shipping') }}</label>

                <div class="col-span-3">
                    <div class="col-span-2 md:col-span-1">
                        {!! $free_shipping ? '<span class="block cursor-pointer material-icons text-green-600 select-none" wire:click="free_shipping">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="free_shipping">toggle_off</span>' !!}

                        @error('free_shipping')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- Free Shipping End --}}

            {{-- Quantity Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="amount"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Quantity') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('amount') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="amount" id="amount"
                        placeholder="{{ __('admin/productsPages.Piece') }}">
                    @error('amount')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Quantity End --}}

            {{-- Low Stock Limit Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="low_stock"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Low Stock Limit') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('low_stock') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="low_stock" id="low_stock"
                        placeholder="{{ __('admin/productsPages.Piece') }}">
                    @error('low_stock')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Low Stock Limit End --}}

        </div>
        {{-- Pricing and Stock End --}}

        {{-- SEO Start --}}
        <div
            class="grid grid-cols-3 gap-x-6 gap-y-2 items-center bg-gray-100 p-4 text-center justify-items-center rounded shadow">

            <div class="col-span-3 font-bold text-black mb-2">{{ __('admin/productsPages.SEO') }}</div>

            {{-- SEO Title Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="seo_title"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Title') }}</label>
                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <input
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('seo_title') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="seo_title" id="seo_title"
                        placeholder="{{ __('admin/productsPages.in English') }}">
                    @error('seo_title')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- SEO Title End --}}

            {{-- SEO Description Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="seo_description"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Description') }}</label>

                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <div wire:ignore
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 cursor-text @error('seo_description') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="description.ar" id="seo_description"
                        placeholder="{{ __('admin/productsPages.in English') }}">
                    </div>

                    @error('seo_description')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            {{-- SEO Description End --}}
        </div>
        {{-- SEO End --}}
    </div>
    {{-- Small Side End --}}



</div>

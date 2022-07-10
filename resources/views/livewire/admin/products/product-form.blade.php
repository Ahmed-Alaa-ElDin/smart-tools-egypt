<div class="grid grid-cols-12 gap-3 items-start">

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Big Side Start --}}
    <div class="col-span-12 lg:col-span-8 w-full grid gap-3">

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Media Start --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-4 text-center  rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">{{ __('admin/productsPages.Product Media') }}</div>

            {{-- Gallery Images Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="gallery_images" class="col-span-12 my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                        <path fill="currentColor"
                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                    </svg>
                    <span> &nbsp;&nbsp; {{ __('admin/productsPages.Uploading ...') }}</span>
                </div>

                {{-- Gallery Image Label --}}
                <label for="gallery_images" class="col-span-12 text-xs text-gray-700 font-bold text-center">
                    {{ __('admin/productsPages.Gallery Images') }} </label>

                @if (!empty($gallery_images_name))
                    {{-- preview --}}
                    <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                        <div class="text-center flex flex-wrap gap-3 justify-around">
                            @foreach ($gallery_images_name as $key => $gallery_image_name)
                                <div class="relative w-25">
                                    <span
                                        class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                        wire:click="deleteImage({{ $key }})"
                                        title="{{ __('admin/productsPages.Delete Image') }}">clear</span>
                                    <span
                                        class="material-icons absolute rounded-circle w-6 h-6 select-none
                                        @if ($featured == $key) border-0 border-success text-white bg-success
                                        @else
                                        border-2 border-gray-500 text-gray-500 @endif
                                        right-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center"
                                        wire:click="setFeatured({{ $key }})"
                                        title="{{ __('admin/productsPages.Make Featured') }}">done</span>
                                    <img src="{{ asset('storage/images/products/original/' . $gallery_image_name) }}"
                                        class="rounded-xl">
                                </div>
                            @endforeach
                        </div>

                        {{-- Upload More Image --}}
                        <label for="gallery_images" class="col-span-1 mt-2 text-xs text-gray-700 font-bold text-center">
                            {{ __('admin/productsPages.Add more images') }} </label>

                        <input
                            class="col-span-1 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                            id="gallery_images" type="file" type="image" wire:model.lazy="gallery_images" multiple>
                        <span class="col-span-1 text-xs text-gray-400">
                            {{ __('admin/productsPages.Use 600x600 sizes images') }}</span>
                        @error('gallery_images.*')
                            <span
                                class="col-span-1 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                        @enderror

                        <div class="mt-1 text-center">
                            <button class="btn btn-danger btn-sm text-bold"
                                wire:click.prevent='removePhoto'>{{ __('admin/productsPages.Remove / Replace All Product Image') }}</button>
                        </div>
                    </div>
                @else
                    {{-- Upload New Image --}}
                    <input
                        class="col-span-12 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        id="gallery_images" type="file" type="image" wire:model.lazy="gallery_images" multiple>
                    <span class="col-span-12 text-xs text-gray-400">
                        {{ __('admin/productsPages.Use 600x600 sizes images') }}</span>
                    @error('gallery_images.*')
                        <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                    @enderror

                @endif
            </div>
            {{-- Gallery Images End --}}

            <hr class="col-span-12 w-full my-2">

            {{-- Thumbnail Images Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">

                {{-- Loading Spinner --}}
                <div wire:loading wire:target="thumbnail_image" class="col-span-12 my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50"
                        class="animate-spin inline-block">
                        <path fill="currentColor"
                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                    </svg>
                    <span> &nbsp;&nbsp; {{ __('admin/productsPages.Uploading ...') }}</span>
                </div>

                {{-- Thumbnail Image Label --}}
                <label for="thumbnail_image" class="col-span-12 text-xs text-gray-700 font-bold text-center">
                    {{ __('admin/productsPages.Thumbnail Image') }} </label>

                @if ($thumbnail_image_name != null)
                    {{-- preview --}}
                    <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                        <div class="text-center flex flex-wrap gap-3 justify-around">
                            <div class="relative w-25">
                                <span
                                    class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                    wire:click="deleteThumbnail"
                                    title="{{ __('admin/productsPages.Delete Image') }}">clear</span>
                                <img src="{{ asset('storage/images/products/original/' . $thumbnail_image_name) }}"
                                    class="rounded-xl">
                            </div>
                        </div>

                    </div>
                @else
                    {{-- Upload New Image --}}
                    <input
                        class="col-span-12 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        id="thumbnail_image" type="file" type="image" wire:model.lazy="thumbnail_image">
                    <span class="col-span-12 text-xs text-gray-400">
                        {{ __('admin/productsPages.Use 300x300 sizes image') }}</span>
                    @error('thumbnail_image')
                        <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                    @enderror
                @endif
            </div>
            {{-- Thumbnail Images End --}}

            <hr class="col-span-12 w-full my-2">

            {{-- Video Link Start --}}
            <div class="col-span-12 grid grid-cols-6 gap-x-4 gap-y-2 items-center w-full">
                <label for="video"
                    class="col-span-6 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Video URL') }}</label>
                <div class="col-span-6 sm:col-span-5">
                    <input id="video"
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('video') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="video"
                        placeholder="{{ __('admin/productsPages.Youtube Link') }}">

                    @error('video')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Video Link End --}}

        </div>
        {{-- Media End --}}
        {{-- ######################################################### --}}
        {{-- ######################################################### --}}


        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Product Information Start --}}
        <div class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center  rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Product Information') }}
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
                        type="text" wire:model.lazy="name.ar" id="name" dir="rtl"
                        placeholder="{{ __('admin/productsPages.in Arabic') }}" maxlength="100" required>
                    @error('name.ar')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
                {{-- Name En --}}
                <div class="col-span-6 md:col-span-5 ">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('name.en') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="name.en" dir="ltr"
                        placeholder="{{ __('admin/productsPages.in English') }}" maxlength="100">
                    @error('name.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Name End --}}

            {{-- Model Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="model"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Model') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input id="model"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('model') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="model"
                        placeholder="{{ __('admin/productsPages.Model') }}" maxlength="100">

                    @error('model')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Model End --}}

            {{-- Brand Start --}}
            <div class="col-span-6 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                <label for="brand_id"
                    class="col-span-12 sm:col-span-4 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Brand') }}</label>
                <div class="col-span-12 sm:col-span-8 ">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('brand_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="brand_id" id="brand_id" required>
                        @if ($brands->count())
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
            <div class="col-span-12 bg-red-200 w-full rounded-xl p-2 grid grid-cols-6 gap-1">

                @foreach ($parentCategories as $key => $parentCategory)
                    {{-- Subcategory Item --}}
                    <div class="col-span-6 bg-red-300 rounded-xl p-3 grid grid-cols-12 gap-2"
                        wire:key="{{ 'parentCategories-' . $key }}">

                        {{-- SelectBoxes --}}
                        <div class="col-span-10 sm:col-span-11 grid grid-cols-12 gap-x-4 gap-y-1">
                            {{-- Supercategory --}}
                            <div class="col-span-12 sm:col-span-6 items-center w-full">
                                <select
                                    class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('parentCategories.' . $key . '.supercategory_id') border-red-900 border-2 @enderror"
                                    wire:model.lazy="parentCategories.{{ $key }}.supercategory_id"
                                    wire:change="supercategoryUpdated({{ $key }})" required>
                                    @if ($parentCategory['supercategories'])
                                        <option value="0">
                                            {{ __('admin/productsPages.Choose a supercategory') }}
                                        </option>
                                        @foreach ($parentCategory['supercategories'] as $supercategory)
                                            <option value="{{ $supercategory['id'] }}">
                                                {{ $supercategory['name'][session('locale')] }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="0">
                                            {{ __('admin/productsPages.No Supercategories in the database') }}
                                        </option>
                                    @endif
                                </select>

                                @error('parentCategories.' . $key . '.supercategory_id')
                                    <div
                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Supercategory --}}

                            {{-- Category --}}
                            @if ($parentCategory['supercategory_id'])
                                <div class="col-span-12 sm:col-span-6 items-center w-full">
                                    <select
                                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('parentCategories.' . $key . '.category_id') border-red-900 border-2 @enderror"
                                        wire:model.lazy="parentCategories.{{ $key }}.category_id"
                                        wire:change="categoryUpdated({{ $key }})" required>
                                        @if ($parentCategory['categories'])
                                            <option value="0">
                                                {{ __('admin/productsPages.Choose a category') }}
                                            </option>
                                            @foreach ($parentCategory['categories'] as $category)
                                                <option value="{{ $category['id'] }}">
                                                    {{ $category['name'][session('locale')] }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="0">
                                                {{ __('admin/productsPages.No Categories in the database') }}
                                            </option>
                                        @endif
                                    </select>

                                    @error('parentCategories.' . $key . '.category_id')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            {{-- Category --}}

                            {{-- Subcategory --}}
                            @if ($parentCategory['category_id'])
                                <div class="col-span-12 sm:col-span-6 sm:col-start-4 items-center w-full">
                                    <select
                                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('parentCategories.' . $key . '.subcategory_id') border-red-900 border-2 @enderror"
                                        wire:model.lazy="parentCategories.{{ $key }}.subcategory_id"
                                        required>
                                        @if ($parentCategory['subcategories'])
                                            <option value="0">
                                                {{ __('admin/productsPages.Choose a subcategory') }}
                                            </option>
                                            @foreach ($parentCategory['subcategories'] as $subcategory)
                                                <option value="{{ $subcategory['id'] }}">
                                                    {{ $subcategory['name'][session('locale')] }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="o">
                                                {{ __('admin/productsPages.No Subcategories in the database') }}
                                            </option>
                                        @endif
                                    </select>

                                    @error('parentCategories.' . $key . '.subcategory_id')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            {{-- Subcategory --}}

                        </div>
                        {{-- SelectBoxes --}}

                        {{-- Delete Subcategory --}}
                        <div
                            class="text-center col-span-2 sm:col-span-1 bg-red-700 rounded-full p-1 flex items-center justify-center">
                            <a href="#" wire:click.prevent="deleteSubcategory({{ $key }})"
                                class="inline-block w-6 h-6 bg-white hover:bg-white focus:bg-white active:bg-white font-bold rounded-full">
                                <span class="material-icons text-red-500 text-xs font-bold shadow-xl">
                                    remove
                                </span>
                            </a>
                        </div>
                        {{-- Delete Subcategory --}}
                    </div>
                    {{-- Subcategory Item --}}
                @endforeach

                {{-- Add New Subcategory --}}
                <div class="text-center col-span-6">
                    <a href="#" wire:click.prevent="addSubcategory"
                        class="btn btn-sm bg-success hover:bg-green-700 focus:bg-success active:bg-success font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            add
                        </span>
                        {{ __('admin/productsPages.Add Subcategory') }}</a>
                </div>
                {{-- Add New Subcategory --}}

            </div>
            {{-- Category End --}}

            {{-- Barcode Start --}}
            <div class="col-span-6 grid grid-cols-3 gap-x-4 gap-y-2 items-center w-full">
                <label for="barcode"
                    class="col-span-3 sm:col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Barcode') }}</label>
                <div class="col-span-3 sm:col-span-2">
                    <input id="barcode"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('barcode') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="barcode"
                        placeholder="{{ __('admin/productsPages.Barcode') }}" maxlength="200">

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
            <div
                class="col-span-6 md:col-span-3 md:col-start-4 lg:col-span-6 lg:col-start-1 grid grid-cols-2 gap-y-2 gap-x-2 items-center w-full">
                <label wire:click="publish"
                    class="col-span-1 lg:col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Publish') }}</label>
                <div class="col-span-1 lg:col-span-2">
                    {!! $publish ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="publish">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="publish">toggle_off</span>' !!}

                    @error('publish')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>

            </div>
            {{-- Publish End --}}

            {{-- Refundable Start --}}
            <div class="col-span-6 md:col-span-3 lg:col-span-6 grid grid-cols-2 gap-y-2 items-center w-full">
                <label wire:click="refund"
                    class="col-span-1 lg:col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Refundable') }}</label>
                <div class="col-span-1 lg:col-span-2">
                    {!! $refundable ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="refund">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="refund">toggle_off</span>' !!}

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

                <div class="col-span-12 md:col-span-10 grid grid-cols-3 gap-3">
                    {{-- Description Ar --}}
                    <div class="col-span-6 md:col-span-5">
                        <div wire:ignore
                            class="py-1 w-full px-6 rounded text-right border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 cursor-text @error('description.ar') border-red-900 border-2 @enderror"
                            type="text" id="description_ar" wire:model="description.ar"
                            placeholder="{{ __('admin/productsPages.in Arabic') }}">
                            @if ($description['ar'])
                                {!! $description['ar'] !!}
                            @else
                                <ul class="list-disc">
                                    <li></li>
                                </ul>
                            @endif
                        </div>

                        @error('description.ar')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Description En --}}
                    <div wire:ignore class="col-span-6 md:col-span-5 ">
                        <div class="py-1 w-full px-6 rounded text-left border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 cursor-text @error('description.en') border-red-900 border-2 @enderror"
                            type="text" id="description_en"
                            placeholder="{{ __('admin/productsPages.in English') }}">
                            @if ($description['en'])
                                {!! $description['en'] !!}
                            @else
                                <ul class="list-disc">
                                    <li></li>
                                </ul>
                            @endif
                        </div>
                    </div>

                    @error('description.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            {{-- Description End --}}

            {{-- Specifications :: Start --}}
            <div class="col-span-12 bg-red-200 w-full rounded-xl p-2 flex flex-col justify-center items-center gap-2">
                @foreach ($specs as $key => $spec)
                    <div class="grid grid-cols-12 gap-2 bg-red-300 rounded-xl p-2" wire:key="{{ 'spec-' . $key }}">
                        <div class="col-span-11 flex flex-col gap-1">
                            <div class="grid grid-cols-6 justify-center items-center gap-1">
                                <input wire:model.lazy="specs.{{ $key }}.ar.title"
                                    placeholder="{{ __('admin/productsPages.Title (ar)') }}" type="text"
                                    class="col-span-2 py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('') border-red-900 border-2 @enderror">
                                <input wire:model.lazy="specs.{{ $key }}.ar.value"
                                    placeholder="{{ __('admin/productsPages.Value (ar)') }}" type="text"
                                    class="col-span-4 py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('') border-red-900 border-2 @enderror">
                            </div>
                            <div class="grid grid-cols-6 justify-center items-center gap-1">
                                <input wire:model.lazy="specs.{{ $key }}.en.title"
                                    placeholder="{{ __('admin/productsPages.Title (en)') }}" type="text"
                                    dir="ltr"
                                    class="col-span-2 py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('') border-red-900 border-2 @enderror">
                                <input wire:model.lazy="specs.{{ $key }}.en.value"
                                    placeholder="{{ __('admin/productsPages.Value (en)') }}" type="text"
                                    dir="ltr"
                                    class="col-span-4 py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('') border-red-900 border-2 @enderror">
                            </div>
                        </div>
                        <div class="col-span-1 flex flex-col justify-center items-center gap-1">
                            <a href="#" wire:click.prevent="deleteSpec({{ $key }})"
                                class="box-content inline-flex justify-center items-center w-6 h-6 bg-white hover:bg-white focus:bg-white active:bg-white font-bold rounded-full border-4 border-red-700">
                                <span class="material-icons text-red-500 text-xs font-bold shadow-xl">
                                    remove
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Add New Specification --}}
                <div class="text-center col-span-6">
                    <a href="#" wire:click.prevent="addSpec"
                        class="btn btn-sm bg-success hover:bg-green-700 focus:bg-success active:bg-success font-bold">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            add
                        </span>
                        {{ __('admin/productsPages.Add Specification') }}</a>
                </div>
                {{-- Add New Specification --}}

            </div>
        </div>
    </div>
    {{-- Product Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Big Side End --}}

    {{-- Small Side Start --}}
    <div class="col-span-12 lg:col-span-4 w-full grid gap-3">

        {{-- Pricing and Stock Start --}}
        <div class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center  rounded shadow">

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
                        placeholder="{{ __('admin/productsPages.EGP') }}" required>
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
                class="col-span-6 sm:col-span-6 md:col-span-3 lg:col-span-3 w-full grid grid-cols-3 gap-x-6 gap-y-1 items-center rounded text-center">
                <label for="free_shipping" wire:click="free_shipping"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer select-none">{{ __('admin/productsPages.Free Shipping') }}</label>

                <div class="col-span-3">
                    <div class="col-span-2 md:col-span-1">
                        {!! $free_shipping ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="free_shipping">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="free_shipping">toggle_off</span>' !!}

                        @error('free_shipping')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- Free Shipping End --}}

            {{-- under reviewing Start --}}
            <div
                class="col-span-6 sm:col-span-6 md:col-span-3 lg:col-span-3 w-full grid grid-cols-3 gap-x-6 gap-y-1 items-center rounded text-center">
                <label for="reviewing" wire:click="reviewing"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer select-none">{{ __('admin/productsPages.Under Reviewing') }}</label>

                <div class="col-span-3">
                    <div class="col-span-2 md:col-span-1">
                        {!! $reviewing ? '<span class="block cursor-pointer material-icons text-success select-none" wire:click="reviewing">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 select-none" wire:click="reviewing">toggle_off</span>' !!}

                        @error('reviewing')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            {{-- under reviewing End --}}

            {{-- Quantity Start --}}
            <div
                class="col-span-12 sm:col-span-6 md:col-span-3 lg:col-span-6 w-full grid grid-cols-3 gap-x-6 gap-y-2 items-center rounded text-center">
                <label for="quantity"
                    class="col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Quantity') }}</label>

                <div class="col-span-3">
                    <input
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('quantity') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="quantity" id="quantity"
                        placeholder="{{ __('admin/productsPages.Piece') }}">
                    @error('quantity')
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
        <div class="grid grid-cols-3 gap-x-6 gap-y-2 items-center bg-gray-100 p-4 text-center  rounded shadow">

            <div class="col-span-3 font-bold text-black mb-2">{{ __('admin/productsPages.SEO') }}</div>

            {{-- SEO Title Start --}}
            {{-- <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="title"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Title') }}</label>
                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <input
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('title') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="title" id="title">
                    @error('title')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div> --}}
            {{-- SEO Title End --}}

            {{-- SEO Description Start --}}
            {{-- <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="seo_description"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Description') }}</label>

                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <div wire:ignore
                        class="py-1 w-full px-6 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 cursor-text @error('seo_description') border-red-900 border-2 @enderror"
                        type="text" id="seo_description">
                        {!! $description_seo !!}
                    </div>

                    @error('seo_description')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div> --}}
            {{-- SEO Description End --}}

            {{-- SEO Keywords :: Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="seo_keywords"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">
                    {{ __('admin/productsPages.Keywords') }}
                </label>

                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <textarea wire:model="seo_keywords" placeholder="{{ __('admin/productsPages.Keywords ("Comma Separated")') }}"
                        class="py-1 w-full px-6 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 cursor-text @error('seo_keywords') border-red-900 border-2 @enderror"
                        type="text" id="seo_keywords">
                    </textarea>

                    @error('seo_keywords')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            {{-- SEO Keywords :: End --}}
        </div>
        {{-- SEO End --}}
    </div>
    {{-- Small Side End --}}

    {{-- Buttons Section Start --}}
    <div class="col-span-12 w-full flex mt-2 justify-around">
        @if ($product_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-success hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save and Add New Product') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.products.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Back') }}</a>

    </div>
    {{-- Buttons Section End --}}

</div>

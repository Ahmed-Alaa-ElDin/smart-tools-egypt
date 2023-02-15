<div class="grid grid-cols-12 gap-3 items-start">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Big Side Start --}}
    <div class="col-span-12 lg:col-span-8 w-full grid gap-3">

        <div
            class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-gray-100 p-4 text-center  rounded shadow">
            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Image') }}
            </div>

            {{-- Images Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">

                {{-- Loading Spinner --}}
                <div wire:loading wire:target="image" class="col-span-12 my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                        <path fill="currentColor"
                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                    </svg>
                    <span> &nbsp;&nbsp; {{ __('admin/productsPages.Uploading ...') }}</span>
                </div>

                @if ($image_name != null)
                    {{-- preview --}}
                    <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                        <div class="text-center flex flex-wrap gap-3 justify-around">
                            <div class="relative w-25">
                                <span
                                    class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                    wire:click="deleteImage"
                                    title="{{ __('admin/productsPages.Delete Image') }}">clear</span>
                                <img src="{{ asset('storage/images/categories/original/' . $image_name) }}"
                                    class="rounded-xl m-auto">
                            </div>
                        </div>

                    </div>
                @else
                    {{-- Upload New Image --}}
                    <input
                        class="col-span-12 md:col-span-6 md:col-start-4 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        id="image" type="file" type="image" wire:model.lazy="image">
                    <span class="col-span-12 text-xs text-gray-400">
                        {{ __('admin/productsPages.Use 300x300 sizes image') }}</span>
                    @error('image')
                        <span
                            class="col-span-12 md:col-span-6 md:col-start-4 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                    @enderror
                @endif
            </div>
            {{-- Banner Images End --}}
        </div>

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Category Information Start --}}
        <div
            class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Category Information') }}
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
                        type="text" wire:model.lazy="name.en"
                        placeholder="{{ __('admin/productsPages.in English') }}" maxlength="100">
                    @error('name.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Name End --}}

            {{-- Supercategory Start --}}
            <div class="col-span-12 sm:col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                <label for="supercategory_id"
                    class="col-span-12 md:col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Supercategory') }}</label>
                <div class="col-span-12 md:col-span-10">
                    <select
                        class="rounded w-full cursor-pointer py-1 text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('supercategory_id') border-red-900 border-2 @enderror"
                        wire:model.lazy="supercategory_id" id="supercategory_id" required>
                        @if ($supercategories->count())
                            <option value="">
                                {{ __('admin/productsPages.Choose a supercategory') }}
                            </option>
                            @foreach ($supercategories as $supercategory)
                                <option value="{{ $supercategory->id }}">
                                    {{ __($supercategory->name) }}
                                </option>
                            @endforeach
                        @else
                            <option value="">
                                {{ __('admin/productsPages.No Supercategories in the database') }}
                            </option>
                        @endif
                    </select>

                    @error('supercategory_id')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Supercategory End --}}

        </div>
    </div>
    {{-- Category Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Big Side End --}}

    {{-- Small Side Start --}}
    <div class="col-span-12 lg:col-span-4 w-full grid gap-3">

        {{-- SEO Start --}}
        <div class="grid grid-cols-3 gap-x-6 gap-y-2 items-center bg-gray-100 p-4 text-center  rounded shadow">

            <div class="col-span-3 font-bold text-black mb-2">{{ __('admin/productsPages.SEO') }}</div>

            {{-- SEO Title Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center rounded text-center">
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
            </div>
            {{-- SEO Title End --}}

            {{-- SEO Description Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center rounded text-center">
                <label for="seo_description"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __("admin/productsPages.Category's Description") }}</label>

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
            </div>
            {{-- SEO Description End --}}
        </div>
        {{-- SEO End --}}
    </div>
    {{-- Small Side End --}}

    {{-- Buttons Section Start --}}
    <div class="col-span-12 w-full flex flex-wrap mt-2 justify-around">
        @if ($category_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save and Add New Category') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.categories.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Back') }}</a>

    </div>
    {{-- Buttons Section End --}}

</div>

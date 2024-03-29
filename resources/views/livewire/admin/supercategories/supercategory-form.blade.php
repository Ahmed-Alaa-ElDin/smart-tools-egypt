<div class="grid grid-cols-12 gap-3 items-start">

    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Big Side Start --}}
    <div class="col-span-12 lg:col-span-8 w-full grid gap-3">

        {{-- ######################################################### --}}
        {{-- ######################################################### --}}
        {{-- Supercategory Information Start --}}
        <div class="grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center  rounded shadow">

            <div class="col-span-12 font-bold text-black mb-2">
                {{ __('admin/productsPages.Supercategory Information') }}
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
                        type="text" wire:model.live.blur="name.ar" id="name"
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
                        type="text" wire:model.live.blur="name.en" placeholder="{{ __('admin/productsPages.in English') }}"
                        maxlength="100">
                    @error('name.en')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Name End --}}

            {{-- Icon Start --}}
            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center w-full">
                <label for="icon"
                    class="col-span-12 md:col-span-2 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/productsPages.Icon') }}</label>
                <div class="col-span-12 md:col-span-10">
                    <textarea name="icon" id="icon"
                        class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('icon') border-red-900 border-2 @enderror"
                        wire:model.live.blur="icon"
                        placeholder="{{ __('admin/productsPages.Past the SVG icon here') }}"></textarea>

                    @error('icon')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Icon End --}}

        </div>
    </div>
    {{-- Supercategory Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Big Side End --}}

    {{-- Small Side Start --}}
    <div class="col-span-12 lg:col-span-4 w-full grid gap-3">

        {{-- SEO Start --}}
        <div class="grid grid-cols-3 gap-x-6 gap-y-2 items-center bg-gray-100 p-4 text-center  rounded shadow">

            <div class="col-span-3 font-bold text-black mb-2">{{ __('admin/productsPages.SEO') }}</div>

            {{-- SEO Title Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="title"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/productsPages.Title') }}</label>
                <div class="col-span-12 sm:col-span-10 md:col-span-6 lg:col-span-12">
                    <input
                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('title') border-red-900 border-2 @enderror"
                        type="text" wire:model.live.blur="title" id="title">
                    @error('title')
                        <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                            {{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- SEO Title End --}}

            {{-- SEO Description Start --}}
            <div class="col-span-3 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center    rounded text-center">
                <label for="seo_description"
                    class="col-span-12 sm:col-span-2 md:col-start-3 lg:col-span-12 lg:col-start-1 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __("admin/productsPages.Supercategory's Description") }}</label>

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
        @if ($supercategory_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Save and Add New Supercategory') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.supercategories.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/productsPages.Back') }}</a>

    </div>
    {{-- Buttons Section End --}}

</div>

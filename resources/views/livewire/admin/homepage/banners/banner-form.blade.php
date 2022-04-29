<div class="grid grid-cols-12 gap-3 items-start">
    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-gray-100 p-4 text-center  rounded shadow">
        <div class="col-span-12 font-bold text-black mb-2">
            {{ __('admin/sitePages.Banner Image') }}
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
                <span> &nbsp;&nbsp; {{ __('admin/sitePages.Uploading ...') }}</span>
            </div>

            @if ($banner_name != null)
                {{-- preview --}}
                <div class="col-span-12 grid grid-cols-1 gap-3 items-center w-full">
                    <div class="text-center flex flex-wrap gap-3 justify-around">
                        <div class="relative w-25">
                            <span
                                class="material-icons absolute rounded-circle bg-red-500 w-6 h-6 text-white left-2 top-2 text-sm font-bold cursor-pointer flex items-center justify-center select-none"
                                wire:click="deleteBanner"
                                title="{{ __('admin/sitePages.Delete Image') }}">clear</span>
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
                    {{ __('admin/sitePages.Use 700x250 sizes image') }}</span>
                @error('banner')
                    <span
                        class="col-span-12 md:col-span-6 md:col-start-4 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                @enderror
                @error('banner_name')
                    <span
                        class="col-span-12 md:col-span-6 md:col-start-4 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                @enderror
            @endif
        </div>
        {{-- Banner Images End --}}
    </div>

    {{-- ######################################################### --}}
    {{-- ######################################################### --}}
    {{-- Banner Information Start --}}
    <div class="col-span-12 grid grid-cols-12 gap-y-3 gap-x-4 items-center bg-red-100 p-4 text-center rounded shadow">

        <div class="col-span-12 font-bold text-black mb-2">
            {{ __("admin/sitePages.Banner's Information") }}
        </div>

        {{-- Description Start --}}
        <div class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 rounded text-center">
            <label for="description"
                class="col-span-12 md:col-span-2 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __("admin/sitePages.Banner's Description") }}</label>
            {{-- Description Ar --}}
            <div class="col-span-12 md:col-span-5">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('description.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="description.ar" id="description" dir="rtl"
                    placeholder="{{ __('admin/sitePages.in Arabic') }}" maxlength="100" required>
                @error('description.ar')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
            {{-- Description En --}}
            <div class="col-span-12 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('description.en') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="description.en" placeholder="{{ __('admin/sitePages.in English') }}"
                    maxlength="100" dir="ltr">
                @error('description.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Description End --}}

        {{-- Link Start --}}
        <div
            class="col-span-12 md:col-span-9 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 rounded text-center">
            <label for="link"
                class="col-span-12 md:col-span-3 font-bold m-0 text-center font-bold text-xs text-gray-700 cursor-pointer">{{ __('admin/sitePages.Link') }}</label>
            {{-- Link --}}
            <div class="col-span-12 md:col-span-9">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('link') border-red-900 border-2 @enderror"
                    type="url" wire:model.lazy="link" id="link" placeholder="{{ __('admin/sitePages.Link') }}" dir="ltr">
                @error('link')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Link End --}}

        {{-- Rank Start --}}
        <div class="col-span-6 col-start-4 md:col-span-3 grid grid-cols-2 gap-y-2 gap-x-2 items-center w-full">
            <label for="rank"
                class="col-span-1 select-none cursor-pointer m-0 font-bold text-xs text-gray-700">{{ __('admin/sitePages.Rank') }}</label>
            <div class="col-span-1">
                <input type="number" id="rank" min="0" max="127" wire:model.lazy="rank" class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('rank') border-red-900 border-2 @enderror">

                @error('rank')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>

        </div>
        {{-- Rank End --}}

    </div>
    {{-- Banner Information End --}}
    {{-- ######################################################### --}}
    {{-- ######################################################### --}}

    {{-- Buttons Section Start --}}
    <div class="col-span-12 w-full flex flex-wrap mt-2 justify-around">
        @if ($banner_id != null)
            <button type="button" wire:click.prevent="update"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Save and Add New Banner') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.site.banners.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Back') }}</a>

    </div>
    {{-- Buttons Section End --}}

</div>

<div class="flex flex-col gap-3">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="bg-gray-100 rounded shadow p-3 grid grid-cols-12 gap-5 overflow-auto scrollbar scrollbar-hidden">
        {{-- Name Start --}}
        <div class="col-span-12 w-full grid grid-cols-12 gap-x-4 gap-y-2 items-center text-center">
            <label for="title"
                class="col-span-12 md:col-span-2 cursor-pointer text-black font-bold m-0 text-center select-none">{{ __("admin/sitePages.Sections's Title") }}</label>
            {{-- Name Ar --}}
            <div class="col-span-6 md:col-span-5">
                <input
                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('title.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="title.ar" id="title" dir="rtl"
                    placeholder="{{ __('admin/sitePages.in Arabic') }}" maxlength="100" required>
                @error('title.ar')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
            {{-- Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('title.en') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="title.en" placeholder="{{ __('admin/sitePages.in English') }}"
                    dir="ltr" maxlength="100" required>
                @error('title.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- Name End --}}

        {{-- Active : Start --}}
        <div class="col-span-4 col-start-5 w-full grid grid-cols-6 gap-x-4 gap-y-2 items-center text-center">
            <label for="active"
                class="col-span-6 md:col-span-2 md:col-start-2 cursor-pointer text-black font-bold m-0 text-center select-none">{{ __('admin/sitePages.Active') }}</label>

            {{-- Active --}}
            <div class="col-span-6 md:col-span-2 text-center flex items-center justify-center">
                <input class="appearance-none rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer"
                    type="checkbox" id="active" wire:model.lazy="active" value="1">
            </div>

            @error('active')
                <div class="inline-block mt-2 col-span-6 md:col-span-4 bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- Active : End --}}

        {{-- Section Type : Start --}}
        <div class="col-span-12 flex flex-wrap justify-center items-center gap-4 ">
            <label for="type" class="text-black font-bold m-0 text-center select-none">
                {{ __('admin/sitePages.Section Type') }}</label>
            <div class="flex flex-wrap justify-center items-center gap-3">

                {{-- Products List : Start --}}
                <label for="products_list" class="text-black m-0 cursor-pointer select-none">
                    <span class="mx-2">
                        {{ __('admin/sitePages.Products List') }}
                    </span>
                    <input class="appearance-none checked:bg-secondary outline-none ring-0 cursor-pointer"
                        id="products_list" type="radio" name="type" wire:model="type" value="0">
                </label>
                {{-- Products List : End --}}

                {{-- Offers : Start --}}
                <label for="offer" class="text-black m-0 cursor-pointer select-none">
                    <span class="mx-2">
                        {{ __('admin/sitePages.Offer') }}
                    </span>
                    <input class="appearance-none checked:bg-secondary outline-none ring-0 cursor-pointer" id="offer"
                        type="radio" name="type" wire:model="type" value="1">
                </label>
                {{-- Offers : End --}}

                {{-- Flash Sale : Start --}}
                <label for="flash_sale" class="text-black m-0 cursor-pointer select-none">
                    <span class="mx-2">
                        {{ __('admin/sitePages.Flash Sale') }}
                    </span>
                    <input class="appearance-none checked:bg-secondary outline-none ring-0 cursor-pointer"
                        id="flash_sale" type="radio" name="type" wire:model="type" value="2">
                </label>
                {{-- Flash Sale : End --}}

                {{-- Banners List : Start --}}
                <label for="banners_list" class="text-black m-0 cursor-pointer select-none">
                    <span class="mx-2">
                        {{ __('admin/sitePages.Banners List') }}
                    </span>
                    <input class="appearance-none checked:bg-secondary outline-none ring-0 cursor-pointer"
                        id="banners_list" type="radio" name="type" wire:model="type" value="3">
                </label>
                {{-- Banners List : End --}}

            </div>
        </div>
        {{-- Section Type : End --}}
    </div>

    <div class="">
        @if ($type == 0)
            @error('selected_products')
                <div class="flex items-center justify-center" wire:key="selected_products">
                    <div
                        class="inline-block max-w-max m-auto col-span-12 bg-red-700 rounded text-white shadow px-3 py-1 w-full mb-2 text-center">
                        {{ $message }}
                    </div>
                </div>
            @enderror
            {{-- Products List : Start --}}
            @livewire('admin.homepage.sections.products-list-form', ['products' => $selected_products ?? []])
            {{-- Products List : End --}}
        @elseif ($type == 1 || $type == 2)
            @error('selected_offer')
                <div class="flex items-center justify-center" wire:key="selected_offer">
                    <div
                        class="inline-block max-w-max m-auto col-span-12 bg-red-700 rounded text-white shadow px-3 py-1 w-full mb-2 text-center">
                        {{ $message }}
                    </div>
                </div>
            @enderror
            {{-- Offers List : Start --}}
        @livewire('admin.homepage.sections.offers-list-form', ['selected_offer' => $selected_offer])
            {{-- Offers List : End --}}
        @elseif ($type == 3)
            @error('selected_banners')
                <div class="flex items-center justify-center" wire:key="selected_offer">
                    <div
                        class="inline-block max-w-max m-auto col-span-12 bg-red-700 rounded text-white shadow px-3 py-1 w-full mb-2 text-center">
                        {{ $message }}
                    </div>
                </div>
            @enderror
            {{-- Offers List : Start --}}
            @livewire('admin.homepage.sections.banners-list-form', ['banners' => $selected_banners ?? []])
            {{-- Offers List : End --}}
        @endif
    </div>

    {{-- Buttons Section Start --}}
    <div class="col-span-12 w-full flex flex-wrap justify-around">
        @if ($section_id != null)
            <button type="button" wire:click.prevent="update" wire:loading.attr="disabled"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Update') }}</button>
        @else
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save" wire:loading.attr="disabled"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')" wire:loading.attr="disabled"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Save and Add New Section') }}</button>
        @endif
        {{-- Back --}}
        <a href="{{ route('admin.homepage') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/sitePages.Back') }}</a>
    </div>
    {{-- Buttons Section End --}}


</div>

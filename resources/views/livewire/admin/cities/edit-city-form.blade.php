<div class="grid grid-cols-12 gap-3">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    {{-- Governorate New Name --}}
    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 items-center bg-red-100 p-2 rounded text-center">
        <label
            class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Name') }}</label>
        {{-- Name Ar --}}
        <div class="col-span-6 md:col-span-5">
            <input
                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" wire:model.lazy="name.ar" placeholder="{{ __('admin/deliveriesPages.in Arabic') }}"
                required>
            @error('name.ar')
                <div class="inline-block mt-2 col-span-12 w-full text-center bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
        {{-- Name En --}}
        <div class="col-span-6 md:col-span-5 ">
            <input
                class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                type="text" wire:model.lazy="name.en" placeholder="{{ __('admin/deliveriesPages.in English') }}">
            @error('name.en')
                <div class="inline-block mt-2 col-span-12 w-full text-center bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Country & Governorate Names --}}
    <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 items-center bg-gray-100 p-2 rounded text-center">
        <label class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center"
            for="country">{{ __('admin/deliveriesPages.Country & Governorate') }}</label>

        {{-- Country Name --}}
        <div class="col-span-12 md:col-span-5">
            <select
                class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('role') border-red-900 border-2 @enderror"
                wire:model.lazy="country_id" id="country" tabindex="8">
                @if ($countries->count())
                    <option value="">{{ __('admin/deliveriesPages.Choose a country') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" wire:key='{{ $country->id . '_' . $country->name }}'>
                            {{ $country->name }}</option>
                    @endforeach
                @else
                    <option value="">{{ __('admin/deliveriesPages.No Countries in the database') }}
                    </option>
                @endif
            </select>

            @error('country_id')
                <div
                    class="inline-block mt-2 col-span-12 w-full text-center bg-red-700 rounded text-white shadow px-3 py-1">
                    {{ $message }}</div>
            @enderror
        </div>

        {{-- Governorate Name --}}
        @if ($country_id)
            <div class="col-span-12 md:col-span-5">
                <select
                    class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('role') border-red-900 border-2 @enderror"
                    wire:model.lazy="governorate_id" id="governorate" tabindex="8">
                    @if ($governorates->count())
                        <option value="">{{ __('admin/deliveriesPages.Choose a governorate') }}</option>
                        @foreach ($governorates as $governorate)
                            <option value="{{ $governorate->id }}"
                                wire:key='{{ $governorate->id . '_' . $governorate->name }}'>
                                {{ $governorate->name }}</option>
                        @endforeach
                    @else
                        <option value="">{{ __('admin/deliveriesPages.No Governorates in the database') }}
                        </option>
                    @endif
                </select>

                @error('governorate_id')
                    <div
                        class="inline-block mt-2 col-span-12 w-full text-center bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        @endif

    </div>

    {{-- Buttons --}}
    <div class="col-span-12 flex flex-wrap gap-3 justify-around mt-4">
        {{-- Save and Back --}}
        <button type="button" wire:click.prevent="save"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Update') }}</button>
        {{-- Back --}}
        <a href="{{ route('admin.cities.index') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Back') }}</a>
    </div>

</div>

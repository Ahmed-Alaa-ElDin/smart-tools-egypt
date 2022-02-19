<div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12 md:col-span-10">

    {{-- Country --}}
    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
        <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
            for="country">{{ __('admin/usersPages.Country') }}</label>
        <select
            class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
            wire:model='choosedCountry' id="country">
            @forelse ($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @empty
                <option value="">{{ __('admin/usersPages.No Countries in Database') }}</option>
            @endforelse
        </select>
    </div>

    {{-- Governorate --}}
    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
        <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
            for="governorate">{{ __('admin/usersPages.Governorate') }}</label>
        <select
            class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
            wire:model='choosedGovernorate' id="governorate">
            @forelse ($governorates as $governorate)
                <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
            @empty
                @if ($choosedCountry == null)
                    <option value="">{{ __('admin/usersPages.Please Choose Country First') }}</option>
                @else
                    <option value="">{{ __('admin/usersPages.No Governorates in Database') }}</option>
                @endif
            @endforelse
        </select>
    </div>

    {{-- City --}}
    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
        <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
            for="city">{{ __('admin/usersPages.City') }}</label>

        <select
            class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
            wire:model='choosedCity' id="city">
            @forelse ($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
            @empty
                @if ($choosedGovernorate == null)
                    <option value="">{{ __('admin/usersPages.Please Choose Governorate First') }}</option>
                @else
                    <option value="">{{ __('admin/usersPages.No Cities in Database') }}</option>
                @endif
            @endforelse
        </select>
    </div>

    {{-- Details --}}
    <div class="details col-span-3 grid grid-cols-6 justify-between items-center m-0">
        <label class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
            for="details">{{ __('admin/usersPages.Address Details') }}</label>
        <div
            class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden">
            <textarea name="details" id="details" rows="2"></textarea>
        </div>
    </div>

    {{-- Special Marque --}}
    <div class="special_marque col-span-3 grid grid-cols-6 justify-between items-center">
        <label class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
            for="special_marque">{{ __('admin/usersPages.Special Marque') }}</label>
        <div
            class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300">

            <textarea name="special_marque" id="special_marque" rows="2"></textarea>
        </div>
    </div>
</div>

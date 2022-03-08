<div>
    <form enctype="multipart/form-data">
        {{-- Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Company Name') }}</label>
            {{-- Name Ar --}}
            <div class="col-span-6 md:col-span-5">
                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                    wire:model.lazy="name.ar" disabled>
            </div>
            {{-- Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input class="py-1 w-full rounded text-center border-gray-300 text-gray-500" type="text"
                    wire:model.lazy="name.en" disabled>
            </div>
        </div>

        {{-- Zones --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Zones') }}</label>

            <div class="grid grid-cols-12 gap-x-4 gap-y-2 col-span-12 md:col-span-10">

                @foreach ($zones as $zone_index => $zone)

                {{-- ########### Zone Start ########### --}}
                    <div class="bg-gray-200 rounded-xl col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 " wire:key="{{ 'zone_'.$zone_index }}">

                        {{-- toolbar --}}
                        <div class="col-span-12 flex justify-between items-center bg-gray-300 py-1 px-2 rounded-xl">

                            {{-- Zone Name Preview --}}
                            <div
                                class="bg-white w-1/2 p-1 rounded-full @if (!$zone['max']) hidden @endif">
                                {{ $zone['name'][session('locale')] != '' ? $zone['name'][session('locale')] : __('admin/deliveriesPages.N/A') }}
                            </div>

                            {{-- Activation button --}}
                            <div class="text-sm text-gray-900 flex items-center bg-white px-3 rounded-full cursor-pointer"
                                wire:click="activate({{ $zone_index }})">
                                <span
                                    class="inline-block rtl:ml-2 ltr:mr-2 font-bold text-xs">{{ __('admin/deliveriesPages.Activate') }}</span>
                                {!! $zone['is_active'] ? '<span class="inline-block cursor-pointer material-icons text-green-600 text-3xl">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600 text-3xl">toggle_off</span>' !!}
                            </div>


                            <div>
                                {{-- Maximize / Minimize --}}
                                <div class="btn bg-white p-2 rounded-full text-black"
                                    wire:click="maximize({{ $zone_index }})">
                                    @if (!$zone['max'])
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                            width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 1024 1024">
                                            <path fill="currentColor"
                                                d="M858.9 689L530.5 308.2c-9.4-10.9-27.5-10.9-37 0L165.1 689c-12.2 14.2-1.2 35 18.5 35h656.8c19.7 0 30.7-20.8 18.5-35z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                            width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 1024 1024">
                                            <path fill="currentColor"
                                                d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z" />
                                        </svg>
                                    @endif
                                </div>

                                {{-- Remove button --}}
                                <button
                                    class=" bg-red-500 hover:bg-red-700 text-white p-1 rounded-full shadow btn btn-xs"
                                    wire:click.prevent='removeZone({{ $zone_index }})'
                                    title="{{ __('admin/deliveriesPages.Delete Zone') }}">
                                    <span class="material-icons">
                                        close
                                    </span>
                                </button>

                            </div>
                        </div>

                        <div
                            class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 @if ($zone['max']) hidden @endif">

                            {{-- Zone Name --}}
                            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 items-center">
                                <label
                                    class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Zone Name') }}</label>
                                {{-- Name Ar --}}
                                <div class="col-span-6 md:col-span-5">
                                    <input
                                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                        type="text" wire:model.lazy="zones.{{ $zone_index }}.name.ar"
                                        placeholder="{{ __('admin/usersPages.in Arabic') }}">
                                    @error('zones.*.name.ar')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Name En --}}
                                <div class="col-span-6 md:col-span-5 ">
                                    <input
                                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                        type="text" wire:model.lazy="zones.{{ $zone_index }}.name.en"
                                        placeholder="{{ __('admin/usersPages.in English') }}">
                                    @error('zones.*.name.en')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="col-span-12">

                            {{-- Zone Fees --}}
                            <div class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 p-2 items-center">
                                <label
                                    class="col-span-12 md:col-span-3 text-black font-bold m-0 text-center">{{ __('admin/deliveriesPages.Shipping Fees') }}</label>
                                {{-- Base Fees --}}
                                <div class="col-span-12 sm:col-span-4 md:col-span-3">
                                    <input
                                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                        type="text" wire:model.lazy="zones.{{ $zone_index }}.min_charge"
                                        placeholder="{{ __('admin/deliveriesPages.Base Fees (EGP)') }}">
                                    @error('zones.*.min_charge')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Base Weight --}}
                                <div class="col-span-6 sm:col-span-4 md:col-span-3">
                                    <input
                                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                        type="text" wire:model.lazy="zones.{{ $zone_index }}.min_size"
                                        placeholder="{{ __('admin/deliveriesPages.Base Weight (Kg)') }}">
                                    @error('zones.*.min_size')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror

                                </div>

                                {{-- Weight by Kg --}}
                                <div class="col-span-6 sm:col-span-4 md:col-span-3">
                                    <input
                                        class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                        type="text" wire:model.lazy="zones.{{ $zone_index }}.kg_charge"
                                        placeholder="{{ __('admin/deliveriesPages.Fees by Kg') }}">
                                    @error('zones.*.kg_charge')
                                        <div
                                            class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                            {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="col-span-12">
                            {{-- Zone's Destinations --}}

                            @foreach ($zone['destinations'] as $des_index => $destination)
                                <div class="col-span-12 grid grid-cols-12 bg-gray-300 p-2 gap-x-2 gap-y-2 rounded-xl" wire:key="{{ 'zone_'.$zone_index.'_des_'.$des_index }}">

                                    {{-- Destination toolbar --}}
                                    <div class="col-span-12 sm:col-span-4 sm:order-2 flex items-start md:p-1">
                                        <div
                                            class="flex justify-around items-center bg-gray-400 py-1 rounded-xl w-full">

                                            {{-- Select All button --}}
                                            <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn @if (empty($zones[$zone_index]['destinations'][$des_index]['allCities'])) hidden @endif"
                                                wire:click="selectAll({{ $zone_index }},{{ $des_index }})"
                                                title="{{ __('admin/deliveriesPages.Select All') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                                    viewBox="0 0 24 24" class="inline-block w-6 h-6">
                                                    <path fill="currentColor"
                                                        d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Zm-7.665 7.858L13.47 7.47a.75.75 0 0 1 1.133.976l-.073.084l-4.5 4.5a.75.75 0 0 1-1.056.004L8.9 12.95l-1.5-2a.75.75 0 0 1 1.127-.984l.073.084l.981 1.308L13.47 7.47l-3.89 3.888Z" />
                                                </svg>
                                            </div>

                                            {{-- Deselect All button --}}
                                            <div class="text-gray-900 bg-white p-1 m-0 shadow rounded cursor-pointer btn @if (empty($zones[$zone_index]['destinations'][$des_index]['allCities'])) hidden @endif"
                                                wire:click="deselectAll({{ $zone_index }},{{ $des_index }})"
                                                title="{{ __('admin/deliveriesPages.Deselect All') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img"
                                                    width="1em" height="1em" preserveAspectRatio="xMidYMid meet"
                                                    viewBox="0 0 24 24" class="inline-block w-6 h-6">
                                                    <path fill="currentColor"
                                                        d="M20.496 5.627A2.25 2.25 0 0 1 22 7.75v10A4.25 4.25 0 0 1 17.75 22h-10a2.25 2.25 0 0 1-2.123-1.504l2.097.004H17.75a2.75 2.75 0 0 0 2.75-2.75v-10l-.004-.051V5.627ZM17.246 2a2.25 2.25 0 0 1 2.25 2.25v12.997a2.25 2.25 0 0 1-2.25 2.25H4.25A2.25 2.25 0 0 1 2 17.247V4.25A2.25 2.25 0 0 1 4.25 2h12.997Zm0 1.5H4.25a.75.75 0 0 0-.75.75v12.997c0 .414.336.75.75.75h12.997a.75.75 0 0 0 .75-.75V4.25a.75.75 0 0 0-.75-.75Z" />
                                                </svg>
                                            </div>

                                            {{-- Remove Destination button --}}
                                            <div>
                                                <button
                                                    class="bg-red-500 hover:bg-red-700 text-white p-1 rounded-full shadow btn btn-xs"
                                                    wire:click.prevent='removeDestination({{ $zone_index }},{{ $des_index }})'
                                                    title="{{ __('admin/deliveriesPages.Delete Destination') }}">
                                                    <span class="material-icons">
                                                        close
                                                    </span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    <div
                                        class="col-span-12 sm:col-span-8 grid grid-cols-12 gap-x-2 gap-y-2 sm:order-1 ">

                                        {{-- Country --}}
                                        <div class="col-span-12 lg:col-span-12 grid grid-cols-3 items-center">
                                            <label
                                                class="col-span-1 rtl:text-xs select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                for="destination_{{ $zone_index }}_country_{{ $des_index }}">{{ __('admin/usersPages.Country') }}</label>
                                            <select
                                                class="col-span-2 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                                wire:model='zones.{{ $zone_index }}.destinations.{{ $des_index }}.country_id'
                                                id="destination_{{ $zone_index }}_country_{{ $des_index }}"
                                                wire:change='countryUpdated({{ $zone_index }},{{ $des_index }})'>
                                                <option value="">{{ __('admin/deliveriesPages.Select Country') }}
                                                </option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country['id'] }}">
                                                        {{ $country['name'][session('locale')] }}</option>
                                                @endforeach
                                            </select>
                                            @error('zones.*.destinations.*.country_id')
                                                <div
                                                    class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                                    {{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Governorate --}}
                                        @if (!empty($zones[$zone_index]['destinations'][$des_index]['governorates']))
                                            <div class="col-span-12 lg:col-span-12 grid grid-cols-3 items-center">
                                                <label
                                                    class="col-span-1 rtl:text-xs select-none cursor-pointer text-black font-medium m-0 mx-3"
                                                    for="destination_{{ $zone_index }}_governorate_{{ $des_index }}">{{ __('admin/usersPages.Governorate') }}</label>
                                                <select
                                                    class="col-span-2 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                                    wire:model='zones.{{ $zone_index }}.destinations.{{ $des_index }}.governorate_id'
                                                    id="destination_{{ $zone_index }}_governorate_{{ $des_index }}"
                                                    wire:change='governorateUpdated({{ $zone_index }},{{ $des_index }})'>
                                                    <option value="">
                                                        {{ __('admin/deliveriesPages.Select Governorate') }}</option>
                                                    @foreach ($zones[$zone_index]['destinations'][$des_index]['governorates'] as $governorate)
                                                        <option value="{{ $governorate['id'] }}">
                                                            {{ $governorate['name'][session('locale')] }}</option>
                                                    @endforeach
                                                </select>
                                                @error('zones.*.destinations.*.governorate_id')
                                                    <div
                                                        class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                                        {{ $message }}</div>
                                                @enderror

                                            </div>
                                        @endif

                                    </div>

                                    {{-- Cities --}}
                                    @if (!empty($zones[$zone_index]['destinations'][$des_index]['allCities']))
                                        <div class="col-span-12 lg:col-span-12 grid grid-cols-3 items-center order-3">
                                            <label
                                                class="col-span-3 rtl:text-xs select-none cursor-pointer text-black font-medium my-3 mx-3">{{ __('admin/deliveriesPages.Cities') }}</label>

                                            <div class="flex flex-wrap col-span-3 gap-2 px-2 justify-center">
                                                @foreach ($zones[$zone_index]['destinations'][$des_index]['allCities'] as $city_index => $city)
                                                    <label
                                                        for="zone_{{ $zone_index }}_destination_{{ $des_index }}_city_{{ $city_index }}"
                                                        class="bg-red-200 px-3 py-1 min-w-max rounded-full text-black shadow cursor-pointer @if (in_array($city['id'], $zones[$zone_index]['destinations'][$des_index]['cities'])) bg-green-200 @endif select-none"
                                                        {{-- wire:click="$emit('citySelected',{{ $zone_index }},{{ $des_index }},{{ $city['id'] }})" --}}
                                                        >
                                                        {{ $city['name'][session('locale')] }}
                                                        <input type="checkbox"
                                                            wire:model="zones.{{ $zone_index }}.destinations.{{ $des_index }}.cities"
                                                            id="zone_{{ $zone_index }}_destination_{{ $des_index }}_city_{{ $city_index }}"
                                                            value="{{ $city['id'] }}" class="hidden">
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                            {{-- Add New Destination Button --}}
                            <div class="col-start-4 col-span-6 sm:col-start-5 sm:col-span-4  order-4"
                                wire:click.prevent="addDestination({{ $zone_index }})"
                                title="{{ __('admin/deliveriesPages.Add Destination') }}">
                                <div
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs">
                                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                                        add
                                    </span>
                                    {{ __('admin/deliveriesPages.Add Destination') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Add New Zone Button --}}
                <div class="col-start-4 col-span-6 sm:col-start-5 sm:col-span-4 " wire:click.prevent="addZone"
                    title="{{ __('admin/deliveriesPages.Add Zone') }}">
                    <div
                        class="bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs">
                        <span class="material-icons rtl:ml-1 ltr:mr-1">
                            add
                        </span>
                        {{ __('admin/deliveriesPages.Add Zone') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex flex-wrap gap-3 justify-around mt-4">
            {{-- Save and Back --}}
            <button type="button" wire:click.prevent="save"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Save') }}</button>
            {{-- Back --}}
            <a href="{{ route('admin.deliveries.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/deliveriesPages.Back to Companies') }}</a>
        </div>

    </form>
</div>

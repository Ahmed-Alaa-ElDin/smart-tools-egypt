<div>
    <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data" class="">
        @csrf
        {{-- Image --}}
        <div
            class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 text-center my-2 justify-items-center	">

            {{-- Loading Spinner --}}
            <div wire:loading wire:target="photo" class="col-span-12 my-2">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
                <span> &nbsp;&nbsp; {{ __('admin/usersPages.Uploading ...') }}</span>
            </div>

            {{-- preview --}}
            @if ($temp_path)
                <div class="col-span-12 text-center w-1/2 md:w-1/4 my-2">
                    <img src="{{ $temp_path }}" class="rounded-xl">
                </div>
                <div class="col-span-12 text-center">
                    <button class="btn btn-danger btn-sm text-bold"
                        wire:click.prevent='removePhoto'>{{ __('admin/usersPages.Remove / Replace Profile Image') }}</button>
                </div>
            @else
                <label for="photo" class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">
                    {{ __('admin/usersPages.Profile Image') }} </label>
                <input
                    class="form-control block w-full md:w-50 px-2 py-1 text-sm font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none col-span-12 md:col-span-10 py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                    id="photo" type="file" type="image" wire:model.lazy="photo">

                @error('photo')
                    <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                @enderror
            @endif

        </div>

        {{-- First Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.First Name') }}</label>
            {{-- First Name Ar --}}
            <div class="col-span-6 md:col-span-5">
                <input
                    class="first_input py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('f_name.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="f_name.ar" placeholder="{{ __('admin/usersPages.in Arabic') }}"
                    tabindex="1">
                @error('f_name.ar')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
            {{-- First Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('f_name.en') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="f_name.en" placeholder="{{ __('admin/usersPages.in English') }}"
                    tabindex="3">
                @error('f_name.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Last Name --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Last Name') }}</label>

            {{-- Last Name Ar --}}
            <div class="col-span-6 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('l_name.ar') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="l_name.ar" placeholder="{{ __('admin/usersPages.in Arabic') }}"
                    tabindex="2">
                @error('l_name.ar')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror

            </div>

            {{-- Last Name En --}}
            <div class="col-span-6 md:col-span-5 ">
                <input
                    class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('l_name.en') border-red-900 border-2 @enderror"
                    type="text" wire:model.lazy="l_name.en" placeholder="{{ __('admin/usersPages.in English') }}"
                    tabindex="4">
                @error('l_name.en')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror

            </div>
        </div>

        {{-- Contacts --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Contacts') }}</label>

            {{-- Email --}}
            <div class="col-span-12 sm:col-span-8 sm:col-start-3 md:col-span-5">
                <input
                    class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('email') border-red-900 border-2 @enderror"
                    type="email" wire:model.lazy="email" placeholder="{{ __('admin/usersPages.Email') }}" dir="ltr"
                    tabindex="5">
                @error('email')
                    <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="col-span-12 md:col-span-5 grid grid-cols-6 gap-y-2">
                @forelse ($phones as $index => $phone)
                    {{-- Add remove button if their are more than one phone number --}}
                    @if (count($phones) > 1)
                        <div class="col-span-1">
                            <button
                                class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full shadow btn btn-xs"
                                wire:click.prevent='removePhone({{ $index }})'><i
                                    class="fa fa-minus"></i></button>
                        </div>
                    @endif

                    {{-- phone input field --}}
                    <input
                        class="@if (count($phones) > 1) col-span-4 @else col-span-5 @endif py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('phone') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="phones.{{ $index }}.phone"
                        placeholder="{{ __('admin/usersPages.Phone') }}" dir="ltr" tabindex="6">

                    {{-- Default Radio Button --}}
                    <div class="col-span-1  flex flex-column justify-center items-center gap-1">
                        <label for="default{{ $index }}"
                            class="text-xs text-black m-0">{{ __('admin/usersPages.Default') }}</label>
                        <input type="radio" id="default{{ $index }}" wire:model.lazy="defaultPhone"
                            value="{{ $index }}" class="appearance-none checked:bg-primary outline-none ring-0">
                    </div>

                @empty
                    <input
                        class="col-span-5 py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('phone') border-red-900 border-2 @enderror"
                        type="text" wire:model.lazy="phone" placeholder="{{ __('admin/usersPages.Phone') }}"
                        dir="ltr" tabindex="6">
                    <div class="col-span-1  flex flex-column justify-center items-center gap-1">
                        <label for="default"
                            class="text-xs text-black m-0">{{ __('admin/usersPages.Default') }}</label>
                        <input type="radio" name="default" checked
                            class="appearance-none checked:bg-primary outline-none ring-0">
                    </div>
                @endforelse

                {{-- Error Messages --}}
                @error('phones.*.phone')
                    <div class="inline-block mt-2 col-span-6 bg-red-700 rounded text-white shadow px-3 py-1">
                        {{ $message }}</div>
                @enderror
                {{-- Add New Phone Button --}}
                <button
                    class="col-start-3 col-span-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                    wire:click.prevent="addPhone"><i
                        class="fa fa-plus rtl:ml-2 ltr:mr-2"></i>{{ __('admin/usersPages.Add') }}</button>
            </div>
        </div>

        {{-- Other Information --}}
        <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
            <label
                class="col-span-12 lg:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Other Information') }}</label>

            <div class="col-span-12 lg:col-span-10 grid grid-cols-12 gap-x-4 gap-y-2 items-center">
                {{-- Gender --}}
                <div
                    class="col-span-12 sm:col-span-6  xl:col-span-3 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                    <label for="gender"
                        class="col-span-1 select-none cursor-pointer text-black font-medium m-0">{{ __('admin/usersPages.Gender') }}</label>

                    <div class="col-span-2">
                        <select
                            class="col-span-2 rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('gender') border-red-900 border-2 @enderror"
                            wire:model.lazy="gender" id="gender" tabindex="7">
                            <option value="0">{{ __('admin/usersPages.Male') }}</option>
                            <option value="1">{{ __('admin/usersPages.Female') }}</option>
                        </select>
                        @error('gender')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div
                    class="col-span-12 sm:col-span-6  xl:col-span-3 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                    <label for="role"
                        class="col-span-1 select-none cursor-pointer text-black font-medium m-0">{{ __('admin/usersPages.Role') }}</label>
                    <div class="col-span-2">
                        <select
                            class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('role') border-red-900 border-2 @enderror"
                            wire:model.lazy="role" id="role" tabindex="8">
                            @forelse ($roles as $role)
                                <option value="{{ $role->id }}">
                                    {{ __('admin/usersPages.' . $role->name) }}</option>
                            @empty
                                <option value="">{{ __('admin/usersPages.No Roles in the database') }}
                                </option>
                            @endforelse
                        </select>

                        @error('role')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Birth Date --}}
                <div
                    class="col-span-12 sm:col-span-12 xl:col-span-6 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                    <label for="birth_date"
                        class="col-span-1 select-none cursor-pointer text-black font-medium m-0">{{ __('admin/usersPages.Birth Date') }}</label>
                    <div class="col-span-2">
                        <input
                            class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('birth_date') border-red-900 border-2 @enderror"
                            type="date" wire:model.lazy="birth_date" id="birth_date" tabindex="9">
                        @error('birth_date')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}

        @php
            dump($addresses);
        @endphp
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Address') }}</label>
            {{-- User Address Select Boxes --}}
            <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12 md:col-span-10">
                @forelse ($addresses as $index => $address)
                    <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2">

                        <div class="col-span-3 flex justify-around bg-red-300 p-2 rounded-xl">

                            {{-- Default Radio Button --}}
                            <div class="flex flex-column justify-center items-center gap-1">
                                <label for="default{{ $index }}"
                                    class="text-xs text-black m-0">{{ __('admin/usersPages.Default') }}</label>
                                <input type="radio" id="default{{ $index }}" wire:model.lazy="defaultAddress"
                                    value="{{ $index }}"
                                    class="appearance-none checked:bg-primary outline-none ring-0">
                            </div>

                            {{-- Add remove button if their are more than one address --}}
                            @if (count($addresses) > 1)
                                <div>
                                    <button
                                        class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-full shadow btn btn-xs"
                                        wire:click.prevent='removeAddress({{ $index }})'><i
                                            class="fa fa-minus"></i></button>
                                </div>
                            @endif

                        </div>

                        {{-- Country --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="country{{ $index }}">{{ __('admin/usersPages.Country') }}</label>
                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('country') border-red-900 border-2 @enderror"
                                wire:model.lazy='addresses.{{ $index }}.country_id'
                                wire:change='$emit("countryUpdated")' id="country{{ $index }}">
                                @forelse ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @empty
                                    <option value="">{{ __('admin/usersPages.No Countries in Database') }}</option>
                                @endforelse
                            </select>

                            @error('addresses.*.country_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Governorate --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label
                                class="col-span-1 rtl:text-xs select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="governorate">{{ __('admin/usersPages.Governorate') }}</label>
                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('governorate') border-red-900 border-2 @enderror"
                                wire:model.lazy='addresses.{{ $index }}.governorate_id' id="governorate"
                                wire:change='$emit("governorateUpdated")'>
                                @forelse ($governorates[$index] as $governorate)
                                    <option value="{{ $governorate['id'] }}">
                                        {{ $governorate['name'][session('locale')] }}</option>
                                @empty
                                    @if ($country == null)
                                        <option value="">{{ __('admin/usersPages.Please Choose Country First') }}
                                        </option>
                                    @else
                                        <option value="">{{ __('admin/usersPages.No Governorates in Database') }}
                                        </option>
                                    @endif
                                @endforelse
                            </select>

                            @error('addresses.*.governorate_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- City --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="city">{{ __('admin/usersPages.City') }}</label>

                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('city  ') border-red-900 border-2 @enderror"
                                wire:model.lazy='addresses.{{ $index }}.city_id' id="city">
                                @forelse ($cities[$index] as $city)
                                    <option value="{{ $city['id'] }}">{{ $city['name'][session('locale')] }}
                                    </option>
                                @empty
                                    @if ($addresses[$index]['governorate_id'] == null)
                                        <option value="">{{ __('admin/usersPages.Please Choose Governorate First') }}
                                        </option>
                                    @else
                                        <option value="">{{ __('admin/usersPages.No Cities in Database') }}</option>
                                    @endif
                                @endforelse
                            </select>

                            @error('addresses.*.city_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Details --}}
                        <div class="details col-span-3 grid grid-cols-6 justify-between items-center m-0" wire:ignore>
                            <label
                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="details">{{ __('admin/usersPages.Address Details') }}</label>
                            <textarea id="details" rows="2" wire:model.lazy="addresses.{{ $index }}.details"
                                dir="rtl"
                                placeholder="{{ __('admin/usersPages.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                            @error('addresses.*.details')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Special Marque --}}
                        <div class="special_marque col-span-3 grid grid-cols-6 justify-between items-center"
                            wire:ignore>
                            <label
                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="special_marque">{{ __('admin/usersPages.Special Marque') }}</label>
                            <textarea id="special_marque" rows="2"
                                wire:model.lazy="addresses.{{ $index }}.special_marque" dir="rtl"
                                placeholder="{{ __('admin/usersPages.Please mention any special marque such as mosque, grocery, ... etc.') }}"
                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"></textarea>
                            @error('addresses.*.special_marque')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                @empty
                    <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2">

                        <div class="col-span-3 flex justify-around bg-red-300 p-2 rounded-xl">

                            {{-- Default Radio Button --}}
                            <div class="flex flex-column justify-center items-center gap-1">
                                <label for="default{{ $index }}"
                                    class="text-xs text-black m-0">{{ __('admin/usersPages.Default') }}</label>
                                <input type="radio" id="default{{ $index }}" wire:model.lazy="defaultAddress"
                                    value="{{ $index }}"
                                    class="appearance-none checked:bg-primary outline-none ring-0">
                            </div>

                            {{-- Add remove button if their are more than one address --}}
                            @if (count($addresses) > 1)
                                <div>
                                    <button
                                        class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-full shadow btn btn-xs"
                                        wire:click.prevent='removeAddress({{ $index }})'><i
                                            class="fa fa-minus"></i></button>
                                </div>
                            @endif

                        </div>

                        {{-- Country --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="country{{ $index }}">{{ __('admin/usersPages.Country') }}</label>
                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('country') border-red-900 border-2 @enderror"
                                wire:model.lazy='addresses.{{ $index }}.country_id'
                                wire:change='$emit("countryUpdated")' id="country{{ $index }}">
                                @forelse ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @empty
                                    <option value="">{{ __('admin/usersPages.No Countries in Database') }}</option>
                                @endforelse
                            </select>

                            @error('addresses.*.country_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Governorate --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label
                                class="col-span-1 rtl:text-xs select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="governorate">{{ __('admin/usersPages.Governorate') }}</label>
                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('governorate') border-red-900 border-2 @enderror"
                                wire:model.lazy='addresses.{{ $index }}.governorate_id' id="governorate"
                                wire:change='$emit("governorateUpdated")'>
                                @forelse ($governorates[$index] as $governorate)
                                    <option value="{{ $governorate['id'] }}">
                                        {{ $governorate['name'][session('locale')] }}</option>
                                @empty
                                    @if ($country == null)
                                        <option value="">{{ __('admin/usersPages.Please Choose Country First') }}
                                        </option>
                                    @else
                                        <option value="">{{ __('admin/usersPages.No Governorates in Database') }}
                                        </option>
                                    @endif
                                @endforelse
                            </select>

                            @error('addresses.*.governorate_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- City --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="city">{{ __('admin/usersPages.City') }}</label>

                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('city  ') border-red-900 border-2 @enderror"
                                wire:model.lazy='addresses.{{ $index }}.city_id' id="city">
                                @forelse ($cities[$index] as $city)
                                    <option value="{{ $city['id'] }}">{{ $city['name'][session('locale')] }}
                                    </option>
                                @empty
                                    @if ($addresses[$index]['governorate_id'] == null)
                                        <option value="">
                                            {{ __('admin/usersPages.Please Choose Governorate First') }}
                                        </option>
                                    @else
                                        <option value="">{{ __('admin/usersPages.No Cities in Database') }}</option>
                                    @endif
                                @endforelse
                            </select>

                            @error('addresses.*.city_id')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Details --}}
                        <div class="details col-span-3 grid grid-cols-6 justify-between items-center m-0" wire:ignore>
                            <label
                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="details">{{ __('admin/usersPages.Address Details') }}</label>
                            <textarea id="details" rows="2" wire:model.lazy="addresses.{{ $index }}.details"
                                dir="rtl"
                                placeholder="{{ __('admin/usersPages.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                            @error('addresses.*.details')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Special Marque --}}
                        <div class="special_marque col-span-3 grid grid-cols-6 justify-between items-center"
                            wire:ignore>
                            <label
                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="special_marque">{{ __('admin/usersPages.Special Marque') }}</label>
                            <textarea id="special_marque" rows="2"
                                wire:model.lazy="addresses.{{ $index }}.special_marque" dir="rtl"
                                placeholder="{{ __('admin/usersPages.Please mention any special marque such as mosque, grocery, ... etc.') }}"
                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"></textarea>
                            @error('addresses.*.special_marque')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror

                        </div>
                    </div>

                @endforelse

                {{-- Add New Address Button --}}
                <button
                    class="col-start-2 col-span-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                    wire:click.prevent="addAddress"><i
                        class="fa fa-plus rtl:ml-2 ltr:mr-2"></i>{{ __('admin/usersPages.Add') }}</button>
            </div>

        </div>
</div>

{{-- Password Notification --}}
<div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-yellow-100 p-2 rounded text-center">
    <label
        class="col-span-12 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Password Notification') }}</label>
</div>

{{-- Buttons --}}
<div class="flex flex-wrap gap-3 justify-around mt-4">
    {{-- Save and Back --}}
    <button type="button" wire:click.prevent="save"
        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Update') }}</button>
    {{-- Back --}}
    <a href="{{ route('admin.users.index') }}"
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Back') }}</a>
</div>

</form>
</div>

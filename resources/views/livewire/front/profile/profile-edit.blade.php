<div class="col-span-12">
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <form enctype="multipart/form-data">

        <div class="grid grid-cols-12 grid-flow-row  gap-3 justify-center items-start">

            {{-- Image :: Start --}}
            <div class="col-span-12 md:col-span-4 bg-white rounded-xl p-4 text-center">
                <label for="image" class="text-gray-800 font-bold m-0 text-lg mb-2">
                    {{ __('front/homePage.Profile Image') }}
                </label>

                {{-- Loading Spinner --}}
                <div wire:loading wire:target="photo" class="col-span-12 my-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50"
                        class="animate-spin inline-block">
                        <path fill="currentColor"
                            d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                    </svg>
                    <span> &nbsp;&nbsp; {{ __('front/homePage.Uploading ...') }}</span>
                </div>

                {{-- preview --}}
                @if ($image_name || $oldImage)
                    <div class="col-span-12 text-center w-full my-2">
                        <img src="{{ $image_name ? asset('storage/images/profiles/original/' . $image_name) : asset('storage/images/profiles/original/' . $oldImage) }}"
                            class="rounded-xl w-1/2 m-auto">
                    </div>
                    <div class="col-span-12 text-center">
                        <button class="btn btn-danger btn-sm text-bold"
                            wire:click.prevent='removePhoto'>{{ __('front/homePage.Remove / Replace Profile Image') }}</button>
                    </div>
                @else
                    <input
                        class="col-span-12 md:col-span-10 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                        id="photo" type="file" type="image" wire:model.live="photo">

                    @error('photo')
                        <span class="col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">{{ $message }}</span>
                    @enderror
                @endif
            </div>
            {{-- Image :: End --}}

            {{-- Basic Info :: Start --}}
            <div class="col-span-12 md:col-span-8 bg-white rounded-xl p-4 text-center">
                {{-- Title --}}
                <label for="basic-info" class="col-span-12 md:col-span-2 text-gray-800 font-bold m-0 text-lg mb-2">
                    {{ __('front/homePage.Basic Information') }}
                </label>

                {{-- Name --}}
                <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center my-2">
                    <label
                        class="col-span-12 md:col-span-2 text-black m-0 text-center">{{ __('front/homePage.First Name') }}</label>
                    {{-- First Name Ar --}}
                    <div class="col-span-6 md:col-span-5">
                        <input
                            class="first_input py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('f_name.ar') border-red-900 border-2 @enderror"
                            type="text" wire:model.live.blur="f_name.ar" dir="rtl"
                            placeholder="{{ __('front/homePage.in Arabic') }}" tabindex="1" required>
                        @error('f_name.ar')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                    {{-- First Name En --}}
                    <div class="col-span-6 md:col-span-5 ">
                        <input
                            class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('f_name.en') border-red-900 border-2 @enderror"
                            type="text" wire:model.live.blur="f_name.en" dir="ltr"
                            placeholder="{{ __('front/homePage.in English') }}" tabindex="3">
                        @error('f_name.en')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                    <label
                        class="col-span-12 md:col-span-2 text-black m-0 text-center">{{ __('front/homePage.Last Name') }}</label>

                    {{-- Last Name Ar --}}
                    <div class="col-span-6 md:col-span-5 ">
                        <input
                            class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('l_name.ar') border-red-900 border-2 @enderror"
                            type="text" wire:model.live.blur="l_name.ar" dir="rtl"
                            placeholder="{{ __('front/homePage.in Arabic') }}" tabindex="2" required>
                        @error('l_name.ar')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Last Name En --}}
                    <div class="col-span-6 md:col-span-5 ">
                        <input
                            class="py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('l_name.en') border-red-900 border-2 @enderror"
                            type="text" wire:model.live.blur="l_name.en" dir="ltr"
                            placeholder="{{ __('front/homePage.in English') }}" tabindex="4">
                        @error('l_name.en')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror

                    </div>
                </div>

                {{-- Contacts --}}
                <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
                    <label
                        class="col-span-12 text-black font-bold m-0 text-center">{{ __('front/homePage.Contacts') }}</label>

                    {{-- Email --}}
                    <div class="col-span-12 sm:col-span-8 sm:col-start-3 md:col-span-10 lg:col-span-6">
                        <input
                            class="py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 @error('email') border-red-900 border-2 @enderror"
                            type="email" wire:model.live.blur="email" dir="ltr"
                            placeholder="{{ __('front/homePage.Email') }}" dir="ltr" tabindex="5">
                        @error('email')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-span-12 lg:col-span-6 grid grid-cols-6 gap-y-2">
                        @foreach ($phones as $index => $phone)
                            {{-- Add remove button if their are more than one phone number --}}
                            @if (count($phones) > 1)
                                <div class="col-span-1">
                                    <button
                                        class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full shadow btn btn-xs"
                                        wire:click.prevent='removePhone({{ $index }})'
                                        title="{{ __('front/homePage.Delete') }}">
                                        <span class="material-icons">
                                            close
                                        </span>
                                    </button>
                                </div>
                            @endif

                            {{-- phone input field --}}
                            <input
                                class="@if (count($phones) > 1) col-span-4 @else col-span-5 @endif py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                type="text" wire:model.live.blur="phones.{{ $index }}.phone"
                                placeholder="{{ __('front/homePage.Phone') }}" dir="ltr" tabindex="6">

                            {{-- Default Radio Button --}}
                            <div class="col-span-1  flex flex-column justify-center items-center gap-1">
                                <label for="defaultPhone{{ $index }}"
                                    class="text-xs text-black m-0 cursor-pointer">{{ __('front/homePage.Default') }}</label>
                                <input type="radio" id="defaultPhone{{ $index }}"
                                    wire:model.live.blur="defaultPhone" value="{{ $index }}"
                                    class="appearance-none checked:bg-primary outline-none ring-0 cursor-pointer">
                            </div>
                        @endforeach

                        {{-- Error Messages --}}
                        @error('phones.*.phone')
                            <div class="inline-block mt-2 col-span-6 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror

                        @error('defaultPhone')
                            <div class="inline-block mt-2 col-span-3 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror


                        {{-- Add New Phone Button --}}
                        <button
                            class="col-start-3 col-span-2 bg-primary hover:bg-primaryDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                            wire:click.prevent="addPhone" title="{{ __('front/homePage.Add') }}">
                            <span class="material-icons rtl:ml-1 ltr:mr-1">
                                add
                            </span>
                            {{ __('front/homePage.Add') }}
                        </button>
                    </div>
                </div>
            </div>
            {{-- Basic Info :: End --}}

            {{-- Other Info :: Start --}}
            <div class="col-span-12 md:col-span-4  bg-white rounded-xl p-4 text-center">
                {{-- Title --}}
                <label for="basic-info" class="text-gray-800 font-bold m-0 text-lg mb-2">
                    {{ __('front/homePage.Other Information') }}
                </label>

                <div class="grid grid-cols-6 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center">
                    {{-- Gender --}}
                    <div class="col-span-3 md:col-span-6 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                        <label for="gender"
                            class="col-span-1 md:col-span-3 select-none cursor-pointer text-black font-medium m-0">{{ __('front/homePage.Gender') }}</label>

                        <div class="col-span-2 md:col-span-3">
                            <select
                                class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('gender') border-red-900 border-2 @enderror"
                                wire:model.live.blur="gender" id="gender" tabindex="7">
                                <option value="0">{{ __('front/homePage.Male') }}</option>
                                <option value="1">{{ __('front/homePage.Female') }}</option>
                            </select>
                            @error('gender')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Birth Date --}}
                    <div class="col-span-3 md:col-span-6 py-1 grid grid-cols-3 gap-x-4 gap-y-2 items-center">
                        <label for="birth_date"
                            class="col-span-1 md:col-span-3 select-none cursor-pointer text-black font-medium m-0">{{ __('front/homePage.Birth Date') }}</label>
                        <div class="col-span-2 md:col-span-3">
                            <input
                                class="rounded w-full cursor-pointer py-1 text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('birth_date') border-red-900 border-2 @enderror"
                                type="date" wire:model.live.blur="birth_date" id="birth_date" tabindex="9"
                                required>
                            @error('birth_date')
                                <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                    {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            {{-- Other Info :: End --}}

            {{-- Addresses :: Start --}}
            <div class="col-span-12 md:col-span-8 bg-white rounded-xl p-4 text-center">
                {{-- Title --}}
                <label for="basic-info" class="col-span-12 text-gray-800 font-bold m-0 text-lg mb-2">
                    {{ __('front/homePage.Addresses') }}
                </label>

                {{-- Address --}}
                <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
                    {{-- User Address Select Boxes --}}
                    <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12">
                        @foreach ($addresses as $index => $address)
                            <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2 ">
                                <div class="col-span-3 flex justify-around bg-red-300 p-2 rounded-xl md:p-1">

                                    {{-- Default Radio Button --}}
                                    <div class="flex flex-column md:flex-row justify-center items-center gap-1">
                                        <label for="defaultAddress{{ $index }}"
                                            class="text-xs text-black m-0 cursor-pointer">{{ __('front/homePage.Default') }}</label>
                                        <input type="radio" id="defaultAddress{{ $index }}"
                                            wire:model.live.blur="defaultAddress" value="{{ $index }}"
                                            class="appearance-none checked:bg-primary outline-none ring-0 cursor-pointer">
                                    </div>

                                    {{-- Add remove button if their are more than one address --}}
                                    @if (count($addresses) > 1)
                                        <div>
                                            <button
                                                class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-full shadow btn btn-xs"
                                                wire:click.prevent='removeAddress({{ $index }})'
                                                title="{{ __('front/homePage.Delete') }}"><span
                                                    class="material-icons">
                                                    close
                                                </span>
                                            </button>
                                        </div>
                                    @endif

                                </div>

                                {{-- Country --}}
                                <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                    <label
                                        class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                        for="country{{ $index }}">{{ __('front/homePage.Country') }}</label>
                                    <select
                                        class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                        wire:model.live='addresses.{{ $index }}.country_id'
                                        wire:change='$dispatch("countryUpdated",{"index":{{ $index }}})'
                                        id="country{{ $index }}">
                                        @forelse ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @empty
                                            <option value="">
                                                {{ __('front/homePage.No Countries in Database') }}
                                            </option>
                                        @endforelse
                                    </select>
                                </div>

                                {{-- Governorate --}}
                                <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                    <label
                                        class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                        for="governorate{{ $index }}">{{ __('front/homePage.Governorate') }}</label>
                                    <select
                                        class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                        wire:model.live='addresses.{{ $index }}.governorate_id'
                                        id="governorate{{ $index }}"
                                        wire:change='$dispatch("governorateUpdated",{"index":{{ $index }}})'>
                                        @forelse ($governorates[$index] as $governorate)
                                            <option value="{{ $governorate['id'] }}">
                                                {{ $governorate['name'][session('locale')] }}</option>
                                        @empty
                                            @if ($country == null)
                                                <option value="">
                                                    {{ __('front/homePage.Please Choose Country First') }}
                                                </option>
                                            @else
                                                <option value="">
                                                    {{ __('front/homePage.No Governorates in Database') }}
                                                </option>
                                            @endif
                                        @endforelse
                                    </select>
                                </div>

                                {{-- City --}}
                                <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                    <label
                                        class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                        for="city{{ $index }}">{{ __('front/homePage.City') }}</label>

                                    <select
                                        class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                        wire:model.live='addresses.{{ $index }}.city_id'
                                        id="city{{ $index }}"
                                        wire:change='$dispatch("cityUpdated",{"index":{{ $index }}})'>
                                        @forelse ($cities[$index] as $city)
                                            <option value="{{ $city['id'] }}">
                                                {{ $city['name'][session('locale')] }}
                                            </option>
                                        @empty
                                            @if ($addresses[$index]['governorate_id'] == null)
                                                <option value="">
                                                    {{ __('front/homePage.Please Choose Governorate First') }}
                                                </option>
                                            @else
                                                <option value="">
                                                    {{ __('front/homePage.No Cities in Database') }}
                                                </option>
                                            @endif
                                        @endforelse
                                    </select>
                                </div>

                                {{-- Details --}}
                                <div class="details col-span-3 grid grid-cols-6 justify-between items-center m-0">
                                    <label
                                        class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                        for="details{{ $index }}">{{ __('front/homePage.Address Details') }}</label>
                                    <textarea id="details{{ $index }}" rows="2"
                                        wire:model.live.blur="addresses.{{ $index }}.details" dir="rtl"
                                        placeholder="{{ __('front/homePage.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                        class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                                </div>

                                {{-- Landmarks --}}
                                <div class="landmarks col-span-3 grid grid-cols-6 justify-between items-center">
                                    <label
                                        class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                        for="landmarks{{ $index }}">{{ __('front/homePage.Landmarks') }}</label>
                                    <textarea id="landmarks{{ $index }}" rows="2"
                                        wire:model.live.blur="addresses.{{ $index }}.landmarks" dir="rtl"
                                        placeholder="{{ __('front/homePage.Please mention any landmarks such as mosque, grocery, ... etc.') }}"
                                        class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"></textarea>
                                </div>
                            </div>
                        @endforeach

                        @error('addresses.*')
                            <div
                                class="inline-block mt-2 col-span-3 md:col-span-1 md:col-start-2 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                        @error('defaultAddress')
                            <div
                                class="inline-block mt-2 col-span-3 md:col-span-1 md:col-start-2 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror


                        {{-- Add New Address Button --}}
                        <button
                            class="col-start-2 col-span-1 bg-primary hover:bg-primaryDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                            wire:click.prevent="addAddress" title="{{ __('front/homePage.Add') }}"> <span
                                class="material-icons rtl:ml-1 ltr:mr-1">
                                add
                            </span>
                            {{ __('front/homePage.Add') }}</button>
                    </div>
                </div>
            </div>
            {{-- Addresses :: End --}}

            {{-- Change Password :: Start --}}
            <div class="col-span-12 md:col-start-5 md:col-span-8  bg-white rounded-xl p-4 text-center">
                {{-- Title --}}
                <label for="basic-info" class="col-span-12 text-gray-800 font-bold m-0 text-lg mb-2">
                    {{ __('front/homePage.Change Password') }}
                </label>

                <div class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 rounded text-center my-2">
                    {{-- Old Password--}}
                    <label
                        class="col-span-4 md:col-span-3 text-black m-0 text-center">{{ __('front/homePage.Old Password') }}</label>
                    <div class="col-span-8 md:col-span-9">
                        <input
                            class="first_input py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('old_password') border-red-900 border-2 @enderror"
                            type="password" wire:model.live.blur="old_password" dir="ltr"
                            placeholder="{{ __('front/homePage.Old Password') }}" tabindex="1" required>
                        @error('old_password')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password--}}
                    <label
                        class="col-span-4 md:col-span-3 text-black m-0 text-center">{{ __('front/homePage.New Password') }}</label>
                    <div class="col-span-8 md:col-span-9">
                        <input
                            class="first_input py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('new_password') border-red-900 border-2 @enderror"
                            type="password" wire:model.live.blur="new_password" dir="ltr"
                            placeholder="{{ __('front/homePage.New Password') }}" tabindex="1" required>
                        @error('new_password')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password Confirmation --}}
                    <label
                        class="col-span-4 md:col-span-3 text-black m-0 text-center">{{ __('front/homePage.New Password Confirmation') }}</label>
                    <div class="col-span-8 md:col-span-9">
                        <input
                            class="first_input py-1 w-full rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 @error('new_password_confirmation') border-red-900 border-2 @enderror"
                            type="password" wire:model.live.blur="new_password_confirmation" dir="ltr"
                            placeholder="{{ __('front/homePage.New Password Confirmation') }}" tabindex="1" required>
                        @error('new_password_confirmation')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
            {{-- Change Password :: End --}}
        </div>

        {{-- Buttons Section Start --}}
        <div class="col-span-12 w-full flex mt-2 justify-around">
            <button type="button" wire:click.prevent="update"
                class="bg-success hover:bg-successDark text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('front/homePage.Update') }}</button>

            {{-- Back --}}
            <a href="{{ route('front.profile.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('front/homePage.Back') }}</a>
        </div>
        {{-- Buttons Section End --}}

    </form>
</div>

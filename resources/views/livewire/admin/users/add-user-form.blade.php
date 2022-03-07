<div>
    <form enctype="multipart/form-data">
        {{-- Image --}}
        <div
            class="grid grid-cols-12 gap-x-6 gap-y-2 items-center bg-gray-100 p-2 text-center my-2 justify-items-center	rounded">

            {{-- Loading Spinner --}}
            <div wire:loading wire:target="photo" class="col-span-12 my-2">
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 50 50" class="animate-spin inline-block">
                    <path fill="currentColor"
                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                </svg>
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
                    id="photo" type="file" type="image" wire:model="photo">

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
                    tabindex="1" required>
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
                    tabindex="2" required>
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
                @foreach ($phones as $index => $phone)
                    {{-- Add remove button if their are more than one phone number --}}
                    @if (count($phones) > 1)
                        <div class="col-span-1">
                            <button
                                class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full shadow btn btn-xs"
                                wire:click.prevent='removePhone({{ $index }})'
                                title="{{ __('admin/usersPages.Delete') }}">
                                <span class="material-icons">
                                    close
                                </span>
                            </button>
                        </div>
                    @endif

                    {{-- phone input field --}}
                    <input
                        class="@if (count($phones) > 1) col-span-4 @else col-span-5 @endif py-1 w-full rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                        type="text" wire:model.lazy="phones.{{ $index }}.phone"
                        placeholder="{{ __('admin/usersPages.Phone') }}" dir="ltr" tabindex="6">

                    {{-- Default Radio Button --}}
                    <div class="col-span-1  flex flex-column justify-center items-center gap-1">
                        <label for="defaultPhone{{ $index }}"
                            class="text-xs text-black m-0 cursor-pointer">{{ __('admin/usersPages.Default') }}</label>
                        <input type="radio" id="defaultPhone{{ $index }}" wire:model.lazy="defaultPhone"
                            value="{{ $index }}"
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
                    class="col-start-3 col-span-2 bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                    wire:click.prevent="addPhone" title="{{ __('admin/usersPages.Add') }}">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/usersPages.Add') }}</button>
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
                            type="date" wire:model.lazy="birth_date" id="birth_date" tabindex="9" required>
                        @error('birth_date')
                            <div class="inline-block mt-2 col-span-12 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
            <label
                class="col-span-12 md:col-span-2 text-black font-bold m-0 text-center">{{ __('admin/usersPages.Address') }}</label>
            {{-- User Address Select Boxes --}}
            <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12 md:col-span-10">
                @foreach ($addresses as $index => $address)
                    <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2 ">
                        <div class="col-span-3 flex justify-around bg-red-300 p-2 rounded-xl md:p-1">

                            {{-- Default Radio Button --}}
                            <div class="flex flex-column md:flex-row justify-center items-center gap-1">
                                <label for="defaultAddress{{ $index }}"
                                    class="text-xs text-black m-0 cursor-pointer">{{ __('admin/usersPages.Default') }}</label>
                                <input type="radio" id="defaultAddress{{ $index }}"
                                    wire:model.lazy="defaultAddress" value="{{ $index }}"
                                    class="appearance-none checked:bg-primary outline-none ring-0 cursor-pointer">
                            </div>

                            {{-- Add remove button if their are more than one address --}}
                            @if (count($addresses) > 1)
                                <div>
                                    <button
                                        class=" bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-full shadow btn btn-xs"
                                        wire:click.prevent='removeAddress({{ $index }})'
                                        title="{{ __('admin/usersPages.Delete') }}"><span class="material-icons">
                                            close
                                        </span>
                                    </button>
                                </div>
                            @endif

                        </div>

                        {{-- Country --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="country{{ $index }}">{{ __('admin/usersPages.Country') }}</label>
                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                wire:model='addresses.{{ $index }}.country_id'
                                wire:change='$emit("countryUpdated",{{ $index }})'
                                id="country{{ $index }}">
                                @forelse ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @empty
                                    <option value="">{{ __('admin/usersPages.No Countries in Database') }}</option>
                                @endforelse
                            </select>
                        </div>

                        {{-- Governorate --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label
                                class="col-span-1 rtl:text-xs select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="governorate{{ $index }}">{{ __('admin/usersPages.Governorate') }}</label>
                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                wire:model='addresses.{{ $index }}.governorate_id'
                                id="governorate{{ $index }}"
                                wire:change='$emit("governorateUpdated",{{ $index }})'>
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
                        </div>

                        {{-- City --}}
                        <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                            <label class="col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="city{{ $index }}">{{ __('admin/usersPages.City') }}</label>

                            <select
                                class="col-span-2 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                wire:model='addresses.{{ $index }}.city_id' id="city{{ $index }}"
                                wire:change='$emit("cityUpdated",{{ $index }})'>
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
                        </div>

                        {{-- Details --}}
                        <div class="details col-span-3 grid grid-cols-6 justify-between items-center m-0">
                            <label
                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="details{{ $index }}">{{ __('admin/usersPages.Address Details') }}</label>
                            <textarea id="details{{ $index }}" rows="2"
                                wire:model.lazy="addresses.{{ $index }}.details" dir="rtl"
                                placeholder="{{ __('admin/usersPages.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                        </div>

                        {{-- Special Marque --}}
                        <div class="special_marque col-span-3 grid grid-cols-6 justify-between items-center">
                            <label
                                class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                for="special_marque{{ $index }}">{{ __('admin/usersPages.Special Marque') }}</label>
                            <textarea id="special_marque{{ $index }}" rows="2"
                                wire:model.lazy="addresses.{{ $index }}.special_marque" dir="rtl"
                                placeholder="{{ __('admin/usersPages.Please mention any special marque such as mosque, grocery, ... etc.') }}"
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
                    class="col-start-2 col-span-1 bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm text-center text-xs"
                    wire:click.prevent="addAddress" title="{{ __('admin/usersPages.Add') }}"> <span
                        class="material-icons rtl:ml-1 ltr:mr-1">
                        add
                    </span>
                    {{ __('admin/usersPages.Add') }}</button>
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
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Save') }}</button>
            {{-- Save and New --}}
            <button type="button" wire:click.prevent="save('true')"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Save and Add New User') }}</button>
            {{-- Back --}}
            <a href="{{ route('admin.users.index') }}"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl shadow btn btn-sm">{{ __('admin/usersPages.Back') }}</a>
        </div>

    </form>
</div>

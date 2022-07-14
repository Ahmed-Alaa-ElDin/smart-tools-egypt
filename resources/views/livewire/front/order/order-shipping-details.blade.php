<div>
    <div class="grid grid-cols-2 justify-center items-center gap-4 p-5">
        @forelse ($user->addresses as $address)
            <div wire:click="selectAddress({{ $address->id }})" wire:key="address-{{ $address->id }}"
                class="relative select-none col-span-2 lg:col-span-1 cursor-pointer @if ($address->default) shadow-inner bg-green-100 hover:shadow @else hover:shadow-inner shadow bg-gray-100 @endif rounded-xl flex flex-col items-center justify-center gap-2 w-full p-2">
                @if ($address->default)
                    <span class="text-xs font-bold text-success">
                        {{ __('front/homePage.Default Shipping Address') }}
                    </span>
                @else
                    <span wire:click.stop="removeAddress({{ $address->id }})"
                        class="absolute top-3 left-3 material-icons text-sm font-bold text-danger"
                        title="{{ __('front/homePage.Remove Address') }}">
                        cancel
                    </span>
                @endif
                <div class="flex justify-center items-center max-w-max m-auto">
                    <h3 class="text-2xl font-bold text-center">
                        {{ $address->address }}
                    </h3>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <p class="text-lg font-bold text-center text-gray-700">
                        {{ $address->country->name }}
                    </p>
                    <span>
                        -
                    </span>
                    <p class="text-lg font-bold text-center text-gray-700">
                        {{ $address->governorate->name }}
                    </p>
                    <span>
                        -
                    </span>
                    <p class="text-lg font-bold text-center text-gray-700">
                        {{ $address->city->name }}
                    </p>
                </div>
                @if ($address->details)
                    <p class="text-sm font-bold text-gray-600">
                        {{ $address->details }}
                    </p>
                @endif
            </div>

            @if ($loop->last)
                <hr class="col-span-2">
                @if (!$changeAddress)
                    <div class="col-span-2">
                        <div class="flex justify-center items-center gap-2">
                            <button wire:click="addAddress"
                                class="btn btn-sm bg-secondary hover:bg-secondaryDark font-bold">
                                {{ __('front/homePage.Add New Address') }}
                                <span class="material-icons text-sm">
                                    add
                                </span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="col-span-2">
                        {{-- Address --}}
                        <div
                            class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
                            {{-- User Address Select Boxes --}}
                            <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12">
                                <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2 ">

                                    {{-- Country --}}
                                    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                        <label
                                            class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="country">{{ __('front/homePage.Country') }}</label>
                                        <select
                                            class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                            wire:model='address.country_id' id="country">
                                            @forelse ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}
                                                </option>
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
                                            for="governorate">{{ __('front/homePage.Governorate') }}</label>
                                        <select
                                            class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                            wire:model='address.governorate_id' id="governorate">
                                            @forelse ($governorates as $governorate)
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
                                            for="city">{{ __('front/homePage.City') }}</label>

                                        <select
                                            class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                            wire:model='address.city_id' id="city">
                                            @forelse ($cities as $city)
                                                <option value="{{ $city['id'] }}">
                                                    {{ $city['name'][session('locale')] }}
                                                </option>
                                            @empty
                                                @if ($address['governorate_id'] == null)
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
                                            for="details">{{ __('front/homePage.Address Details') }}</label>
                                        <textarea id="details" rows="2" wire:model.lazy="address.details" dir="rtl"
                                            placeholder="{{ __('front/homePage.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                            class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                                    </div>

                                    {{-- Special Marque --}}
                                    <div
                                        class="special_marque col-span-3 grid grid-cols-6 justify-between items-center">
                                        <label
                                            class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="special_marque">{{ __('front/homePage.Special Marque') }}</label>
                                        <textarea id="special_marque" rows="2" wire:model.lazy="address.special_marque" dir="rtl"
                                            placeholder="{{ __('front/homePage.Please mention any special marque such as mosque, grocery, ... etc.') }}"
                                            class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"></textarea>
                                    </div>
                                </div>

                                @error('address.*')
                                    <div
                                        class="inline-block mt-2 col-span-3 md:col-span-1 md:col-start-2 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror

                                <div class="col-span-3 flex flex-wrap justify-around items-center">
                                    <button
                                        class="btn btn-sm bg-success hover:bg-successDark text-white font-bold rounded focus:outline-none focus:shadow-outline"
                                        wire:click="save(0)">
                                        {{ __('front/homePage.Save') }}
                                    </button>
                                    <button wire:click="cancel"
                                        class="btn btn-sm bg-primary hover:bg-primaryDark text-white font-bold rounded focus:outline-none focus:shadow-outline">
                                        {{ __('front/homePage.Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        @empty
            <div class="col-span-2">
                {{-- Address --}}
                <div
                    class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-red-100 p-2 rounded text-center my-2">
                    {{-- User Address Select Boxes --}}
                    <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12">
                        <div class="bg-red-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2 ">

                            {{-- Country --}}
                            <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center">
                                <label
                                    class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                    for="country">{{ __('front/homePage.Country') }}</label>
                                <select
                                    class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                    wire:model='address.country_id' id="country">
                                    @forelse ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}
                                        </option>
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
                                    for="governorate">{{ __('front/homePage.Governorate') }}</label>
                                <select
                                    class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                    wire:model='address.governorate_id' id="governorate">
                                    @forelse ($governorates as $governorate)
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
                                    for="city">{{ __('front/homePage.City') }}</label>

                                <select
                                    class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                                    wire:model='address.city_id' id="city">
                                    @forelse ($cities as $city)
                                        <option value="{{ $city['id'] }}">
                                            {{ $city['name'][session('locale')] }}
                                        </option>
                                    @empty
                                        @if ($address['governorate_id'] == null)
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
                                    for="details">{{ __('front/homePage.Address Details') }}</label>
                                <textarea id="details" rows="2" wire:model.lazy="address.details" dir="rtl"
                                    placeholder="{{ __('front/homePage.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                    class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300 overflow-hidden"></textarea>
                            </div>

                            {{-- Special Marque --}}
                            <div class="special_marque col-span-3 grid grid-cols-6 justify-between items-center">
                                <label
                                    class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                    for="special_marque">{{ __('front/homePage.Special Marque') }}</label>
                                <textarea id="special_marque" rows="2" wire:model.lazy="address.special_marque" dir="rtl"
                                    placeholder="{{ __('front/homePage.Please mention any special marque such as mosque, grocery, ... etc.') }}"
                                    class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"></textarea>
                            </div>
                        </div>

                        @error('address.*')
                            <div
                                class="inline-block mt-2 col-span-3 md:col-span-1 md:col-start-2 bg-red-700 rounded text-white shadow px-3 py-1">
                                {{ $message }}</div>
                        @enderror

                        <div class="col-span-3 flex flex-wrap justify-around items-center">
                            <button
                                class="btn btn-sm bg-success hover:bg-successDark text-white font-bold rounded focus:outline-none focus:shadow-outline"
                                wire:click="save(1)">
                                {{ __('front/homePage.Save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        @endforelse
    </div>
</div>

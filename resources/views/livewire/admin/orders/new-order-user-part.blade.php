<div class="bg-gray-50 p-2 rounded-xl shadow">
    <x-admin.waiting />

    <div class="text-center mb-2 font-bold text-gray-900">
        {{ __('admin/ordersPages.Customer Choosing') }}
    </div>
    <div class="flex flex-wrap-reverse justify-around items-center gap-3">
        <div class="relative w-full md:w-auto md:w-[50%]">

            {{-- Search Customer Input :: Start --}}
            <div class="flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-900 bg-gray-700 text-center text-white text-sm">
                    <span class="material-icons">
                        search
                    </span>
                </span>
                <input type="text" wire:model.live.debounce.500ms='search' wire:keydown.Escape="$set('search','')"
                    data-name="new-order-user-part"
                    class="searchInput focus:ring-0 flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-700"
                    placeholder="{{ __('admin/ordersPages.Search ...') }}">
            </div>
            {{-- Search Customer Input :: End --}}

            @if ($search != null)
                <div
                    class="absolute button-0 left-0 w-full z-10 bg-white border border-t-0 border-gray-700 max-h-36 overflow-x-hidden rounded-b-xl p-2 scrollbar scrollbar-thin scrollbar-thumb-gray-700">
                    {{-- Loading :: Start --}}
                    <div wire:loading.delay wire:target="search" class="w-full">
                        <div class="flex gap-2 justify-center items-center p-4">
                            <span class="text-primary text-xs font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                    class="animate-spin text-9xl" height="1em" preserveAspectRatio="xMidYMid meet"
                                    viewBox="0 0 50 50">
                                    <path fill="currentColor"
                                        d="M41.9 23.9c-.3-6.1-4-11.8-9.5-14.4c-6-2.7-13.3-1.6-18.3 2.6c-4.8 4-7 10.5-5.6 16.6c1.3 6 6 10.9 11.9 12.5c7.1 2 13.6-1.4 17.6-7.2c-3.6 4.8-9.1 8-15.2 6.9c-6.1-1.1-11.1-5.7-12.5-11.7c-1.5-6.4 1.5-13.1 7.2-16.4c5.9-3.4 14.2-2.1 18.1 3.7c1 1.4 1.7 3.1 2 4.8c.3 1.4.2 2.9.4 4.3c.2 1.3 1.3 3 2.8 2.1c1.3-.8 1.2-2.5 1.1-3.8c0-.4.1.7 0 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    {{-- Customers List :: Start --}}
                    @forelse ($customers as $customer)
                        <div class="group flex flex-col justify-center items-center gap-1 cursor-pointer rounded transition-all ease-in-out hover:bg-gray-700 p-1"
                            wire:click="$set('customer_id',{{ $customer->id }})"
                            wire:key="customer-{{ $customer->id }}-{{ rand() }}">
                            <span class="text-center font-bold text-gray-900 group-hover:text-white">
                                {{ $customer->f_name . ' ' . $customer->l_name }}
                            </span>
                            <span class="text-sm font-bold text-gray-500 group-hover:text-gray-50">
                                {{ $customer->phones->where('default', 1)->count() ? $customer->phones->where('default', 1)->first()->phone : '' }}
                            </span>
                        </div>

                        @if (!$loop->last)
                            <hr class="my-1">
                        @endif
                    @empty
                        <div class="text-center font-bold">
                            {{ __('admin/ordersPages.No Customers Found') }}
                        </div>
                    @endforelse
                    {{-- Customers List :: End --}}
                </div>
            @endif
        </div>

        @if ($customer_id)
            <div>
                {{-- Change Customer --}}
                <button wire:click="clearCustomer"
                    class="btn btn-sm bg-red-500 hover:bg-red-700 focus:bg-red-700 active:bg-red-700 font-bold">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        close
                    </span>
                    {{ __('admin/ordersPages.Choose another customer') }}
                </button>
            </div>
            <div>
                {{-- Edit Customer --}}
                <a href="{{ route('admin.customers.edit', $selectedCustomer->id) }}" target="_blank"
                    wire:click="clearCustomer"
                    class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-500 font-bold">
                    <span class="material-icons rtl:ml-1 ltr:mr-1">
                        edit
                    </span>
                    {{ __('admin/ordersPages.Edit Customer') }}
                </a>
            </div>
        @endif

        {{-- Create New Customer :: Start --}}
        <div>
            <a href="{{ route('admin.customers.create') }}" target="_blank" wire:click="clearCustomer"
                class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                <span class="material-icons rtl:ml-1 ltr:mr-1">
                    add
                </span>
                {{ __('admin/ordersPages.Add Customer') }}
            </a>
        </div>
        {{-- Create New Customer :: End --}}
    </div>

    {{-- Customer Selected --}}
    @if ($customer_id)

        <hr class="my-2">

        {{-- Customer Info :: Start --}}
        <div class="flex flex-wrap justify-around items-center gap-2">
            {{-- Customer's Name & Mail --}}
            <div
                class="flex flex-col items-center justify-center gap-2 bg-white shadow-inner p-2 rounded-xl border-2 border-gray-800">
                <span class="text-lg text-gray-900 font-bold">
                    {{ $selectedCustomer->f_name . ' ' . $selectedCustomer->l_name }}
                </span>

                <span class="text-gray-500 text-sm font-bold">
                    {{ $selectedCustomer->email }}
                </span>
            </div>

            {{-- Customer's Balance --}}
            <div class="p-1 flex flex-col gap-2 justify-center items-center bg-green-500 rounded">
                <span class="pt-1 px-1 text-xs font-bold text-white">
                    {{ __('admin/ordersPages.Balance') }}
                </span>

                <span class="p-1 w-full text-center font-bold bg-white rounded" dir="ltr">
                    {{ number_format($selectedCustomer->balance, 2, '.', '\'') }}
                </span>
            </div>

            {{-- Customer's Points --}}
            <div class="p-1 flex flex-col gap-2 justify-center items-center bg-green-500 rounded">
                <span class="pt-1 px-1 text-xs font-bold text-white">
                    {{ __('admin/ordersPages.Points') }}
                </span>

                <span class="p-1 w-full text-center font-bold bg-white rounded" dir="ltr">
                    {{ number_format($selectedCustomer->validPoints, 2, '.', '\'') }}
                </span>
            </div>


        </div>
        {{-- Customer Info :: End --}}

        <hr class="my-2">

        <div class="grid grid-cols-1 md:grid-cols-3 justify-between items-center gap-3">
            {{-- Addresses --}}
            <div
                class="
                col-span-1 md:col-span-2 grid grid-cols-2 md:grid-cols-4 justify-center items-center gap-3">
                @forelse ($selectedCustomer->addresses as $address)
                    <div @if (!$address->default) wire:click="selectAddress({{ $address->id }})" @endif
                        wire:key="address-{{ $address->id }}-{{ rand() }}"
                        class="relative select-none col-span-2  @if ($address->default) shadow-inner bg-green-100 @else cursor-pointer hover:shadow-inner shadow bg-gray-100 @endif rounded-xl flex flex-col items-center justify-center gap-2 w-full p-2">
                        @if ($address->default)
                            <span class="text-xs font-bold text-success">
                                {{ __('admin/ordersPages.Default Shipping Address') }}
                            </span>
                        @else
                            <span wire:click.stop="removeAddress({{ $address->id }})"
                                class="absolute top-3 left-3 material-icons text-sm font-bold text-danger"
                                title="{{ __('admin/ordersPages.Remove Address') }}">
                                cancel
                            </span>
                        @endif

                        <div class="flex items-center justify-center gap-2">
                            <p class="text-lg font-bold text-center text-gray-700">
                                {{ $address->country ? $address->country->name : '' }}
                            </p>
                            <span>
                                -
                            </span>
                            <p class="text-lg font-bold text-center text-gray-700">
                                {{ $address->governorate ? $address->governorate->name : '' }}
                            </p>
                            <span>
                                -
                            </span>
                            <p class="text-lg font-bold text-center text-gray-700">
                                {{ $address->city ? $address->city->name : '' }}
                            </p>
                        </div>
                        @if ($address->details)
                            <p class="text-sm font-bold text-gray-600">
                                {{ $address->details }}
                            </p>
                        @endif
                        @if ($address->landmarks)
                            <p class="text-sm font-bold text-gray-400">
                                {{ $address->landmarks }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="text-center font-bold col-span-2 md:col-span-4">
                        {{ __('admin/ordersPages.No Addresses for this customer') }}
                    </div>
                @endforelse

                @if (!$addAddress)
                    <div class="col-span-4">
                        <div class="flex justify-center items-center gap-2">
                            <button wire:click="$set('addAddress',{{ true }})"
                                class="btn btn-sm bg-secondary hover:bg-secondaryDark font-bold">
                                <span class="material-icons text-sm">
                                    add
                                </span>
                                {{ __('admin/ordersPages.Add New Address') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="col-span-4">
                        {{-- New Address --}}
                        <div
                            class="col-span-12 grid grid-cols-12 gap-x-4 gap-y-2 items-center bg-gray-100 p-2 rounded text-center my-2">
                            {{-- User Address Select Boxes --}}
                            <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12">
                                <div class="bg-gray-200 rounded col-span-3 grid grid-cols-3 gap-x-4 gap-y-2 p-2 ">
                                    {{-- Country --}}
                                    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center gap-1">
                                        <label
                                            class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="country">
                                            {{ __('admin/ordersPages.Country') }}
                                        </label>

                                        <select
                                            class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                            wire:model.live='newAddress.country_id' id="country">
                                            @forelse ($countries as $country)
                                                <option value="{{ $country['id'] }}">
                                                    {{ $country['name'][session('locale')] }}
                                                </option>
                                            @empty
                                                <option value="">
                                                    {{ __('admin/ordersPages.No Countries in Database') }}
                                                </option>
                                            @endforelse
                                        </select>
                                    </div>

                                    {{-- Governorate --}}
                                    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center gap-1">
                                        <label
                                            class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="governorate">{{ __('admin/ordersPages.Governorate') }}</label>
                                        <select
                                            class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                            wire:model.live='newAddress.governorate_id' id="governorate">
                                            @forelse ($newAddress['governorates'] as $governorate)
                                                <option value="{{ $governorate['id'] }}">
                                                    {{ $governorate['name'][session('locale')] }}</option>
                                            @empty
                                                @if ($country == null)
                                                    <option value="">
                                                        {{ __('admin/ordersPages.Please Choose Country First') }}
                                                    </option>
                                                @else
                                                    <option value="">
                                                        {{ __('admin/ordersPages.No Governorates in Database') }}
                                                    </option>
                                                @endif
                                            @endforelse
                                        </select>
                                    </div>

                                    {{-- City --}}
                                    <div class="col-span-3 lg:col-span-1 grid grid-cols-3 items-center gap-1">
                                        <label
                                            class="col-span-1 lg:col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="city">{{ __('admin/ordersPages.City') }}</label>

                                        <select
                                            class="col-span-2 lg:col-span-3 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                                            wire:model.live='newAddress.city_id' id="city">
                                            @forelse ($newAddress['cities'] as $city)
                                                <option value="{{ $city['id'] }}">
                                                    {{ $city['name'][session('locale')] }}
                                                </option>
                                            @empty
                                                @if ($newAddress['governorate_id'] == null)
                                                    <option value="">
                                                        {{ __('admin/ordersPages.Please Choose Governorate First') }}
                                                    </option>
                                                @else
                                                    <option value="">
                                                        {{ __('admin/ordersPages.No Cities in Database') }}
                                                    </option>
                                                @endif
                                            @endforelse
                                        </select>
                                    </div>

                                    {{-- Details --}}
                                    <div class="details col-span-3 grid grid-cols-6 justify-between items-center m-0">
                                        <label
                                            class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="details">{{ __('admin/ordersPages.Address Details') }}</label>
                                        <textarea id="details" rows="2" wire:model.live.blur="newAddress.details" dir="rtl"
                                            placeholder="{{ __('admin/ordersPages.Please mention the details of the address such as street name, building number, ... etc.') }}"
                                            class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300 overflow-hidden"></textarea>
                                    </div>

                                    {{-- Landmarks --}}
                                    <div class="landmarks col-span-3 grid grid-cols-6 justify-between items-center">
                                        <label
                                            class="col-span-2 lg:col-span-1 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                            for="landmarks">{{ __('admin/ordersPages.Landmarks') }}</label>
                                        <textarea id="landmarks" rows="2" wire:model.live.blur="newAddress.landmarks" dir="rtl"
                                            placeholder="{{ __('admin/ordersPages.Please mention any landmarks such as mosque, grocery, ... etc.') }}"
                                            class="col-span-4 lg:col-span-5 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"></textarea>
                                    </div>
                                </div>

                                @error('newAddress.*')
                                    <div
                                        class="inline-block mt-2 col-span-3 md:col-span-1 md:col-start-2 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror

                                <div class="col-span-3 flex flex-wrap justify-around items-center">
                                    <button
                                        class="btn btn-sm bg-success hover:bg-successDark text-white font-bold rounded focus:outline-none focus:shadow-outline"
                                        wire:click="saveAddress">
                                        {{ __('admin/ordersPages.Save') }}
                                    </button>
                                    <button wire:click="$set('addAddress',{{ false }})"
                                        class="btn btn-sm bg-primary hover:bg-primaryDark text-white font-bold rounded focus:outline-none focus:shadow-outline">
                                        {{ __('admin/ordersPages.Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Phones --}}
            <div class="col-span-1 flex flex-col justify-center items-center gap-3">
                @forelse ($selectedCustomer->phones as $phone)
                    <div wire:click="selectPhone({{ $phone->id }})" wire:key="phone-{{ $phone->id }}"
                        class="relative select-none col-span-2 lg:col-span-1 cursor-pointer @if ($phone->default) shadow-inner bg-green-100 hover:shadow @else hover:shadow-inner shadow bg-gray-100 @endif rounded-xl flex flex-col items-center justify-center gap-2 w-full p-2">
                        @if ($phone->default)
                            <span class="text-xs font-bold text-success">
                                {{ __('admin/ordersPages.Default Phone') }}
                            </span>
                        @else
                            <span wire:click.stop="removePhone({{ $phone->id }})"
                                class="absolute top-3 left-3 material-icons text-sm font-bold text-danger"
                                title="{{ __('admin/ordersPages.Remove Phone') }}">
                                cancel
                            </span>
                        @endif

                        <div class="flex items-center justify-center gap-2">
                            <p class="text-lg font-bold text-center text-gray-700">
                                {{ $phone->phone }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center font-bold">
                        {{ __('admin/ordersPages.No phones for this customer') }}
                    </div>
                @endforelse

                @if (!$addPhone)
                    <div class="col-span-2">
                        <div class="flex justify-center items-center gap-2">
                            <button wire:click="$set('addPhone',{{ true }})"
                                class="btn btn-sm bg-secondary hover:bg-secondaryDark font-bold">
                                <span class="material-icons text-sm">
                                    add
                                </span>
                                {{ __('admin/ordersPages.Add New Phone') }}
                            </button>
                        </div>
                    </div>
                @else
                    <div class="col-span-2 w-full">
                        {{-- New Phone --}}
                        <div class="grid grid-cols-12 items-center bg-gray-100 p-2 rounded text-center">
                            <div class="grid grid-cols-3 gap-x-4 gap-y-2 col-span-12">
                                {{-- Phone --}}
                                <div class="phone col-span-3 grid grid-cols-3 justify-between items-center gap-2">
                                    <label
                                        class="col-span-3 select-none cursor-pointer text-black font-medium m-0 mx-3"
                                        for="phone">{{ __('admin/ordersPages.Phone') }}</label>
                                    <input type="text" id="phone" wire:model.live.blur="newPhone"
                                        wire:keydown.enter="savePhone" dir="ltr"
                                        placeholder="{{ __('admin/ordersPages.Enter the phone number') }}"
                                        class="col-span-3 w-full py-1 rounded text-center border-gray-300 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300">
                                </div>

                                @error('newPhone')
                                    <div
                                        class="inline-block mt-2 col-span-3 bg-red-700 rounded text-white shadow px-3 py-1">
                                        {{ $message }}</div>
                                @enderror

                                <div class="col-span-3 flex flex-wrap justify-around items-center">
                                    <button
                                        class="btn btn-sm bg-success hover:bg-successDark text-white font-bold rounded focus:outline-none focus:shadow-outline"
                                        wire:click="savePhone">
                                        {{ __('admin/ordersPages.Save') }}
                                    </button>
                                    <button wire:click="$set('addPhone',{{ false }})"
                                        class="btn btn-sm bg-primary hover:bg-primaryDark text-white font-bold rounded focus:outline-none focus:shadow-outline">
                                        {{ __('admin/ordersPages.Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>

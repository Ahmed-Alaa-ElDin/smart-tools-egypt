<div class="bg-gray-50 p-2 rounded-xl shadow">
    <div class="flex flex-wrap-reverse justify-around items-center gap-3">
        <div class="relative">
            <div class="flex rounded-md shadow-sm">
                <span
                    class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-900 bg-gray-700 text-center text-white text-sm">
                    <span class="material-icons">
                        search
                    </span>
                </span>
                <input type="text" wire:model.debounce.500ms='search'
                    class="searchInput focus:ring-0 flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-700"
                    placeholder="{{ __('admin/ordersPages.Search ...') }}">
            </div>
            @if ($search != null)
                <div
                    class="absolute button-0 left-0 w-full z-10 bg-white border border-t-0 border-gray-700 max-h-36 overflow-x-hidden rounded-b-xl p-2 scrollbar scrollbar-thin scrollbar-thumb-gray-700">
                    @forelse ($customers as $customer)
                        <div class="group flex flex-col justify-center items-center gap-1 cursor-pointer rounded transition-all ease-in-out hover:bg-gray-700 p-1"
                            wire:click="$set('customer_id',{{ $customer->id }})" wire:key="customer-{{ $customer->id }}-{{ rand() }}">
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
                </div>
            @endif
        </div>
        <div>
            <a href="{{ route('admin.customers.create') }}" target="_blank"
                class="btn btn-sm bg-success hover:bg-successDark focus:bg-success active:bg-success font-bold">
                <span class="material-icons rtl:ml-1 ltr:mr-1">
                    add
                </span>
                {{ __('admin/ordersPages.Add Customer') }}
            </a>
        </div>
    </div>

    @if ($customer_id)
        <hr class="my-2">

        <div class="flex justify-between items-start gap-3">
            {{-- Addresses --}}
            @forelse ($selectedCustomer->addresses as $address)
                <div wire:click="selectAddress({{ $address->id }})" wire:key="address-{{ $address->id }}-{{ rand() }}"
                    class="relative select-none col-span-2 lg:col-span-1 cursor-pointer @if ($address->default) shadow-inner bg-green-100 hover:shadow @else hover:shadow-inner shadow bg-gray-100 @endif rounded-xl flex flex-col items-center justify-center gap-2 w-full p-2">
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
                    <p class="text-sm font-bold text-gray-600">
                        {{ $address->details ?? $address->details }}
                    </p>
                </div>
            @empty
            @endforelse

            <div>
                <div wire:click="selectPhone(
                {{-- {{ $phone->id }} --}}
                )"
                    wire:key="phone-
                {{-- {{ $phone->id }} --}}
                "
                    class="relative select-none col-span-2 lg:col-span-1 cursor-pointer 
                {{-- @if ($phone->default) shadow-inner bg-green-100 hover:shadow @else hover:shadow-inner shadow bg-gray-100 @endif --}}
                rounded-xl flex flex-col items-center justify-center gap-2 w-full p-2">
                    {{-- @if ($phone->default)
                    <span class="text-xs font-bold text-success">
                        {{ __('admin/ordersPages.Default Phone') }}
                    </span>
                @else
                    <span wire:click.stop="removePhone({{ $phone->id }})"
                        class="absolute top-3 left-3 material-icons text-sm font-bold text-danger"
                        title="{{ __('admin/ordersPages.Remove Phone') }}">
                        cancel
                    </span>
                @endif --}}

                    <div class="flex items-center justify-center gap-2">
                        <p class="text-lg font-bold text-center text-gray-700">
                            {{-- {{ $phone->phone }} --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @dump($customer_id)
</div>

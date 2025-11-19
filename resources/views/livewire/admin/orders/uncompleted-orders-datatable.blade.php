<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">

        {{-- Search and Pagination Control --}}
        <div class="py-3 bg-white space-y-3">

            <div class="flex justify-between gap-6 items-center">
                {{-- Search Box --}}
                <div class="col-span-1">
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <div
                            class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                            <span class="material-icons">
                                search
                            </span>
                        </div>
                        <input type="text" wire:model.live='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/ordersPages.Search ...') }}">
                    </div>
                </div>

                {{-- Pagination Number --}}
                <div class="form-inline col-span-1 justify-end my-2">
                    {{ __('pagination.Show') }} &nbsp;
                    <select wire:model.live='perPage' class="form-control w-auto px-3 cursor-pointer">
                        <option>5</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    &nbsp; {{ __('pagination.results') }}
                </div>
            </div>
        </div>
        {{-- Search and Pagination Control --}}

        <div class="scrollbar scrollbar-hidden -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 ">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Customer Info. Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Customer Info.') }}
                                    </div>
                                </th>

                                {{-- Address Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Address') }}
                                    </div>
                                </th>

                                {{-- Number of Items Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Number of Items') }}
                                    </div>
                                </th>

                                {{-- Total Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Total') }}
                                    </div>
                                </th>

                                {{-- Created At Header --}}
                                <th wire:click="setSortBy('created_at')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Last Update') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'created_at',
                                        ])
                                    </div>
                                </th>

                                {{-- Manage Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Manage') }}
                                        <span class="sr-only">{{ __('admin/ordersPages.Manage') }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($carts as $cart)
                                <tr>
                                    {{-- Customer Info. Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <a href="{{ route('admin.customers.edit', $cart->user->id) }}" target="_blank"
                                            class="flex flex-col items-center content-center justify-center">
                                            <span class="font-bold">
                                                {{ $cart->user ? $cart->user->f_name . ' ' . $cart->user->l_name : __('N/A') }}
                                            </span>
                                            <span class="text-gray-500">
                                                @if ($cart->user && $cart->user->phones->count())
                                                    @foreach ($cart->user->phones as $phone)
                                                        <span
                                                            class="@if ($phone->default) font-bold @endif">{{ $phone->phone }}</span>
                                                        @if (!$loop->last)
                                                            -
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ __('N/A') }}
                                                @endif
                                            </span>
                                        </a>
                                    </td>

                                    {{-- Address Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex flex-col items-center content-center justify-center">
                                            @if ($cart->user->defaultAddress->first())
                                                <span
                                                    class="font-bold">{{ $cart->user->defaultAddress->first()->city->name }}</span>
                                                <span
                                                    class="text-xs ">{{ $cart->user->defaultAddress->first()->governorate->name }}</span>
                                            @else
                                                {{ __('N/A') }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Number of Items Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden text-center">
                                        <button data-modal-target="cart-content-modal"
                                            data-modal-toggle="cart-content-modal"
                                            wire:click="showCartItems({{ $cart->identifier }})"
                                            title="{{ __('admin/ordersPages.View') }}"
                                            class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                            <span class="bg-white rounded py-1 px-2">
                                                {{ $cart->content->count() }}
                                            </span>

                                            <span class="material-icons text-lg text-white p-1 ltr:ml-1 rtl:mr-1">
                                                visibility
                                            </span>
                                        </button>
                                    </td>

                                    {{-- Total Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center" dir="ltr">
                                            {{ formatTotal($cart->content->sum(fn($cartItem) => $cartItem->price * $cartItem->qty)) }}
                                        </div>
                                    </td>

                                    {{-- Created At Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        {{ $cart->created_at }}
                                    </td>

                                    {{-- Manage Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        {{-- Complete Order Button --}}
                                        <button wire:click="completeOrder({{ $cart->identifier }})"
                                            title="{{ __('admin/ordersPages.Complete Order') }}" type="button"
                                            class="m-0 focus:outline-none">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-success hover:bg-successHover rounded">
                                                done
                                            </span>
                                        </button>

                                        {{-- Delete Cart Button --}}
                                        <button wire:click="deleteCart({{ $cart->identifier }})"
                                            title="{{ __('admin/ordersPages.Delete') }}" type="button"
                                            class="m-0 focus:outline-none">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                delete
                                            </span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="9">
                                        {{ $search == '' ? __('admin/ordersPages.No data in this table') : __('admin/ordersPages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $carts->links() }}
        </div>
    </div>
</div>

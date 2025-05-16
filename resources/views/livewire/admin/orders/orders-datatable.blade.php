<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">

        {{-- Multiple Selection Section --}}
        @if (count($selectedOrders))
            <div class="flex justify-around items-center">
                <div
                    class="bg-primary rounded-full text-white font-bold px-3 py-2 flex justify-between items-center shadow gap-x-2 text-xs">
                    {{ trans_choice('admin/ordersPages.Order Selected', count($selectedOrders), ['order' => count($selectedOrders)]) }}
                    <span
                        class="material-icons w-4 h-4 bg-white text-black p-2 rounded-full flex justify-center items-center text-xs font-bold text-red-800 cursor-pointer"
                        wire:click="unselectAll" title="{{ __('admin/ordersPages.Unselect All') }}">close</span>
                </div>
                <div>
                    <div class="flex justify-center">
                        <button class="btn btn-warning dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                settings
                            </span> &nbsp; {{ __('admin/ordersPages.Control selected orders') }}
                            &nbsp;</button>

                        <div class="dropdown-menu text-black ">
                            {{-- Edit Status --}}
                            <a wire:click.prevent="statusesUpdateSelect"
                                class="dropdown-item dropdown-item-excel justify-start font-bold hover:bg-yellow-400 focus:bg-yellow-400 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    linear_scale
                                </span> &nbsp;&nbsp;
                                {{ __('admin/ordersPages.Edit Status') }}
                            </a>

                            {{-- Download AWB --}}
                            <a wire:click.prevent="downloadBostaAWBs"
                                class="dropdown-item dropdown-item-excel justify-start font-bold hover:bg-green-500 focus:bg-green-500 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons p-1 text-lg w-7 h-7">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill="currentColor"
                                            d="M7.5 9a1.5 1.5 0 0 0-1.42 1.014c.345.04.665.16.942.34A.5.5 0 0 1 7.5 10H8v1.477a2 2 0 0 1 1.043.962l.281.561H13v1.5a.5.5 0 0 1-.5.5H10v1h2.5a1.5 1.5 0 0 0 1.5-1.5v-4A1.5 1.5 0 0 0 12.5 9zm5.5 3H9v-2h3.5a.5.5 0 0 1 .5.5zm-9-2h1V4a1 1 0 0 1 1-1h4v3.5A1.5 1.5 0 0 0 11.5 8H15v8a1 1 0 0 1-1 1h-4c0 .364-.097.706-.268 1H14a2 2 0 0 0 2-2V7.414a1.5 1.5 0 0 0-.44-1.06l-3.914-3.915A1.5 1.5 0 0 0 10.586 2H6a2 2 0 0 0-2 2zm10.793-3H11.5a.5.5 0 0 1-.5-.5V3.207zM2.167 11C1.522 11 1 11.522 1 12.167v4.666c0 .474.282.88.686 1.064A1.334 1.334 0 0 0 4.291 18h.751a1.334 1.334 0 0 0 2.583 0H8a1 1 0 0 0 1-1v-2.176a1 1 0 0 0-.106-.447l-.745-1.49a1 1 0 0 0-.894-.554H7v-.166C7 11.522 6.478 11 5.833 11zM7 14.333V13h.255c.126 0 .241.071.298.184l.574 1.15zm-4.667 3.334a.667.667 0 1 1 1.334 0a.667.667 0 0 1-1.334 0m4 .666a.667.667 0 1 1 0-1.333a.667.667 0 0 1 0 1.333" />
                                    </svg>
                                </span> &nbsp;&nbsp;
                                {{ __('admin/ordersPages.Download All Bosta AWB') }}
                            </a>

                            {{-- Download PO --}}
                            <a wire:click.prevent="downloadPurchaseOrders"
                                class="dropdown-item dropdown-item-excel justify-start font-bold hover:bg-green-500 focus:bg-green-500 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    sim_card_download
                                </span> &nbsp;&nbsp;
                                {{ __('admin/ordersPages.Download All PO') }}
                            </a>

                            {{-- Delete --}}
                            <a wire:click.prevent="archiveAllConfirm"
                                class="dropdown-item dropdown-item-excel justify-start font-bold hover:bg-red-600 focus:bg-red-600 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    delete
                                </span> &nbsp;&nbsp;
                                {{ __('admin/ordersPages.Archive All') }}
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Multiple Selection Section --}}

        {{-- Search and Pagination Control --}}
        <div class="py-3 bg-white space-y-3">

            <div class="flex justify-between gap-6 items-center">
                {{-- Search Box --}}
                <div class="col-span-1">
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span
                            class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                            <span class="material-icons">
                                search
                            </span> </span>
                        <input type="text" wire:model.live='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/ordersPages.Search ...') }}">
                    </div>
                </div>

                {{-- Download --}}
                <div class="form-inline col-span-1 justify-center">
                    {{-- <div class="flex justify-center">
                        <button class="btn btn-success dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                file_download
                            </span> &nbsp; {{ __('admin/ordersPages.Export Orders') }}
                            &nbsp;</button>
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.orders.exportExcel') }}"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-success focus:bg-success">
                                <span class="material-icons">
                                    file_present
                                </span> &nbsp;&nbsp;
                                {{ __('admin/ordersPages.download all excel') }}</a>
                            <a href="{{ route('admin.orders.exportPDF') }}"
                                class="dropdown-item dropdown-item-pdf justify-center font-bold hover:bg-red-600 focus:bg-red-600">
                                <span class="material-icons">
                                    picture_as_pdf
                                </span>
                                &nbsp;&nbsp;
                                {{ __('admin/ordersPages.download all pdf') }}</a>
                        </div>
                    </div> --}}
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
                                {{-- Multiple Select Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        <input type="checkbox" wire:model.live="selectAll" value="true"
                                            class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                    </div>
                                </th>

                                {{-- Order Id Header --}}
                                <th wire:click="setSortBy('orders.id')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.ID') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'orders.id',
                                        ])
                                    </div>
                                </th>

                                {{-- Status Header --}}
                                <th wire:click="setSortBy('status_name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Status') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'status_name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Customer Info. Header --}}
                                <th wire:click="setSortBy('f_name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Customer Info.') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'f_name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Address Header --}}
                                <th wire:click="setSortBy('governorate_name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Address') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'governorate_name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Total Header --}}
                                <th {{-- wire:click="setSortBy('orders.total')"  --}} scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Total') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'orders.total',
                                        ])
                                    </div>
                                </th>

                                {{-- Remaining Header --}}
                                <th {{-- wire:click="setSortBy('orders.should_pay')" --}} scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Should Pay / Get') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'orders.should_pay',
                                        ])
                                    </div>
                                </th>

                                {{-- Last Update Header --}}
                                <th wire:click="setSortBy('orders.updated_at')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/ordersPages.Last Update') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'orders.updated_at',
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
                            @forelse ($orders as $order)
                                <tr>
                                    {{-- select order Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center">
                                            <input type="checkbox" wire:model.live="selectedOrders"
                                                value="{{ $order->id }}"
                                                class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                        </div>
                                    </td>

                                    {{-- Order ID Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center">
                                            {{ $order->id }}
                                        </div>
                                    </td>

                                    {{-- Status Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div
                                            class="flex items-center content-center justify-center p-1 text-xs font-bold rounded shadow-inner
                                            {{ in_array($order->status_id, [1, 2, 14, 15, 16])
                                                ? 'bg-yellow-100 text-yellow-900'
                                                : (in_array($order->status_id, [3, 45, 12])
                                                    ? 'bg-green-100 text-green-900'
                                                    : (in_array($order->status_id, [4, 5, 6])
                                                        ? 'bg-blue-100 text-blue-900'
                                                        : (in_array($order->status_id, [8, 9, 13])
                                                            ? 'bg-red-100 text-red-900'
                                                            : 'bg-blue-100 text-blue-900'))) }}">
                                            {{ $order->status_id ? $order->status->name : __('N/A') }}
                                        </div>
                                    </td>

                                    {{-- Customer Info. Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <a href="{{ route('admin.customers.edit', $order->user_id) }}" target="_blank"
                                            class="flex flex-col items-center content-center justify-center">
                                            <span class="font-bold">
                                                {{ $order->user ? $order->user->f_name . ' ' . $order->user->l_name : __('N/A') }}
                                            </span>
                                            <span class="text-gray-500">
                                                @if ($order->user && $order->user->phones->count())
                                                    @foreach ($order->user->phones as $phone)
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
                                            @if ($order->address_id && $order->address)
                                                <span class="font-bold">{{ $order->address->city->name }}</span>
                                                <span class="text-xs ">{{ $order->address->governorate->name }}</span>
                                            @else
                                                {{ __('N/A') }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Total Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center" dir="ltr">
                                            {{ formatTotal($order->invoice?->total) }}
                                        </div>
                                    </td>

                                    {{-- Total Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex flex-col items-center content-center justify-center overflow-hidden text-white rounded-lg shadow-inner"
                                            dir="ltr">
                                            <span class="bg-red-500 w-full text-center px-2">
                                                {{ formatTotal($order->should_pay) }}
                                            </span>
                                            <span class="bg-green-500 w-full text-center px-2">
                                                {{ formatTotal($order->should_get) }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Last Update Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex flex-col items-center content-center justify-center">
                                            <span>
                                                {{ __('admin/ordersPages.' . Carbon\Carbon::parse($order->updated_at)->format('D')) }}
                                                ,
                                                {{ Carbon\Carbon::parse($order->updated_at)->format('d') }}
                                                {{ __('admin/ordersPages.' . Carbon\Carbon::parse($order->updated_at)->format('M')) }}

                                                {{ Carbon\Carbon::parse($order->updated_at)->format('Y') }}
                                            </span>
                                            <span class="text-gray-500">
                                                {{ Carbon\Carbon::parse($order->updated_at)->format('h:i') }}
                                                {{ __('admin/ordersPages.' . Carbon\Carbon::parse($order->updated_at)->format('A')) }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Manage Body --}}
                                    <td
                                        class="px-6 py-3 flex gap-1 items-center justify-center text-center text-sm font-medium">

                                        {{-- Order Details --}}
                                        <div class="group relative">
                                            <a href="{{ route('admin.orders.show', [$order->id]) }}"
                                                title="{{ __('admin/ordersPages.View') }}" class="m-0"
                                                target="_blank">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-view hover:bg-viewHover rounded">
                                                    visibility
                                                </span>
                                            </a>

                                            {{-- Order Products --}}
                                            <div
                                                class="group-hover:block hidden hover:block absolute max-w-75 bg-white border-gray-500 border w-[500px] max-h-[150px] -left-[250px] top-[35px] overflow-y-auto rounded-lg drop-shadow-lg z-10 scrollbar scrollbar-thin scrollbar-thumb-red-200 scrollbar-track-gray-100">
                                                <table class="w-100">
                                                    @foreach ($order->collections as $collection)
                                                        <tr>
                                                            <td
                                                                class="px-2 py-1 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                                {{ $collection->name }}
                                                            </td>
                                                            <td
                                                                class="px-2 py-1 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                                {{ $collection->pivot->quantity }}
                                                            </td>
                                                            <td
                                                                class="px-2 py-1 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                                <span
                                                                    class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                                                <span dir="ltr"
                                                                    class="font-bold">{{ number_format($collection->pivot->price * $collection->pivot->quantity, 2, '.', '\'') }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    @foreach ($order->products as $product)
                                                        <tr>
                                                            <td
                                                                class="px-2 py-1 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                                {{ $product->name }}
                                                            </td>
                                                            <td
                                                                class="px-2 py-1 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                                {{ $product->pivot->quantity }}
                                                            </td>
                                                            <td
                                                                class="px-2 py-1 text-xs font-bold text-gray-500 uppercase tracking-wider text-nowrap">
                                                                <span
                                                                    class="text-xs">{{ __('front/homePage.EGP') }}</span>
                                                                <span dir="ltr"
                                                                    class="font-bold">{{ number_format($product->pivot->price * $product->pivot->quantity, 2, '.', '\'') }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>

                                        @if ($order->order_delivery_id)
                                            {{-- Download Bosta AWB --}}
                                            <button title="{{ __('admin/ordersPages.Download Bosta AWB') }}"
                                                wire:click="downloadBostaAWB('{{ $order->order_delivery_id }}')"
                                                class="m-0 focus:outline-none">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-green-500 hover:bg-green-700 rounded flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill="currentColor"
                                                            d="M7.5 9a1.5 1.5 0 0 0-1.42 1.014c.345.04.665.16.942.34A.5.5 0 0 1 7.5 10H8v1.477a2 2 0 0 1 1.043.962l.281.561H13v1.5a.5.5 0 0 1-.5.5H10v1h2.5a1.5 1.5 0 0 0 1.5-1.5v-4A1.5 1.5 0 0 0 12.5 9zm5.5 3H9v-2h3.5a.5.5 0 0 1 .5.5zm-9-2h1V4a1 1 0 0 1 1-1h4v3.5A1.5 1.5 0 0 0 11.5 8H15v8a1 1 0 0 1-1 1h-4c0 .364-.097.706-.268 1H14a2 2 0 0 0 2-2V7.414a1.5 1.5 0 0 0-.44-1.06l-3.914-3.915A1.5 1.5 0 0 0 10.586 2H6a2 2 0 0 0-2 2zm10.793-3H11.5a.5.5 0 0 1-.5-.5V3.207zM2.167 11C1.522 11 1 11.522 1 12.167v4.666c0 .474.282.88.686 1.064A1.334 1.334 0 0 0 4.291 18h.751a1.334 1.334 0 0 0 2.583 0H8a1 1 0 0 0 1-1v-2.176a1 1 0 0 0-.106-.447l-.745-1.49a1 1 0 0 0-.894-.554H7v-.166C7 11.522 6.478 11 5.833 11zM7 14.333V13h.255c.126 0 .241.071.298.184l.574 1.15zm-4.667 3.334a.667.667 0 1 1 1.334 0a.667.667 0 0 1-1.334 0m4 .666a.667.667 0 1 1 0-1.333a.667.667 0 0 1 0 1.333" />
                                                    </svg>
                                                </span>
                                            </button>
                                        @else
                                            {{-- Download Bosta AWB --}}
                                            <button
                                                title="{{ __('admin/ordersPages.Please create Bosta order first') }}"
                                                disabled class="m-0 focus:outline-none">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-gray-300 rounded flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path fill="currentColor"
                                                            d="M7.5 9a1.5 1.5 0 0 0-1.42 1.014c.345.04.665.16.942.34A.5.5 0 0 1 7.5 10H8v1.477a2 2 0 0 1 1.043.962l.281.561H13v1.5a.5.5 0 0 1-.5.5H10v1h2.5a1.5 1.5 0 0 0 1.5-1.5v-4A1.5 1.5 0 0 0 12.5 9zm5.5 3H9v-2h3.5a.5.5 0 0 1 .5.5zm-9-2h1V4a1 1 0 0 1 1-1h4v3.5A1.5 1.5 0 0 0 11.5 8H15v8a1 1 0 0 1-1 1h-4c0 .364-.097.706-.268 1H14a2 2 0 0 0 2-2V7.414a1.5 1.5 0 0 0-.44-1.06l-3.914-3.915A1.5 1.5 0 0 0 10.586 2H6a2 2 0 0 0-2 2zm10.793-3H11.5a.5.5 0 0 1-.5-.5V3.207zM2.167 11C1.522 11 1 11.522 1 12.167v4.666c0 .474.282.88.686 1.064A1.334 1.334 0 0 0 4.291 18h.751a1.334 1.334 0 0 0 2.583 0H8a1 1 0 0 0 1-1v-2.176a1 1 0 0 0-.106-.447l-.745-1.49a1 1 0 0 0-.894-.554H7v-.166C7 11.522 6.478 11 5.833 11zM7 14.333V13h.255c.126 0 .241.071.298.184l.574 1.15zm-4.667 3.334a.667.667 0 1 1 1.334 0a.667.667 0 0 1-1.334 0m4 .666a.667.667 0 1 1 0-1.333a.667.667 0 0 1 0 1.333" />
                                                    </svg>
                                                </span>
                                            </button>
                                            {{-- Create Bosta Order --}}
                                            {{-- <button title="{{ __('admin/ordersPages.Create Bosta Order') }}"
                                                wire:click="createBostaOrder({{ $order->id }})"
                                                class="m-0 focus:outline-none">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-secondary hover:secondaryDark rounded">
                                                    local_shipping
                                                </span>
                                            </button> --}}
                                        @endif

                                        {{-- Download PO --}}
                                        <button title="{{ __('admin/ordersPages.Download PO') }}"
                                            wire:click="downloadPurchaseOrder({{ $order->id }})"
                                            class="m-0 focus:outline-none">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-green-500 hover:bg-green-700 rounded">
                                                sim_card_download
                                            </span>
                                        </button>

                                        {{-- Payment History --}}
                                        <a href="{{ route('admin.orders.payment-history', [$order->id]) }}"
                                            title="{{ __('admin/ordersPages.Payment History') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-green-500 hover:bg-green-700 rounded">
                                                attach_money
                                            </span>
                                        </a>

                                        {{-- Edit Status --}}
                                        <button title="{{ __('admin/ordersPages.Edit Status') }}"
                                            wire:click="statusUpdateSelect({{ $order->id }})"
                                            class="m-0 focus:outline-none">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-yellow-400 hover:bg-yellow-700 rounded">
                                                linear_scale
                                            </span>
                                        </button>

                                        {{-- Edit Button --}}
                                        {{-- <a href="{{ route('admin.orders.edit', ['order' => $order->id]) }}"
                                            title="{{ __('admin/ordersPages.Edit') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                edit
                                            </span>
                                        </a> --}}

                                        {{-- Archive Button --}}
                                        <button title="{{ __('admin/ordersPages.Archive') }}" type="button"
                                            wire:click="archiveConfirm({{ $order->id }})"
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
            {{ $orders->links() }}
        </div>
    </div>
</div>

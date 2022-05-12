<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="py-3 bg-white space-y-6">
                    <div class="flex justify-between gap-6 items-center">


                        {{-- Search Box --}}
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span
                                class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                                <span class="material-icons">
                                    search
                                </span> </span>
                            <input type="text" name="company-website" id="company-website" wire:model='search'
                                class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                                placeholder="{{ __('admin/deliveriesPages.Search ...') }}">
                        </div>

                        {{-- Seft deleted Delivery Companies --}}
                        @can('Force Delete Delivery')
                            <div class="ltr:text-right rtl:text-left">
                                <a href="{{ route('admin.deliveries.softDeletedDeliveries') }}"
                                    class="btn btn-sm bg-red-600 hover:bg-red-700 focus:bg-red-600 active:bg-red-600 font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em"
                                        height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"
                                        class="inline-block text-xl rtl:ml-2 ltr:mr-2">
                                        <path fill="currentColor"
                                            d="M20 8h-3V4H3c-1.11 0-2 .89-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4M6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5m6.54-6.38l-1.42 1.42L9 11.41l-2.12 2.13l-1.41-1.42L7.59 10L5.46 7.88l1.42-1.41L9 8.59l2.12-2.12l1.42 1.41L10.41 10l2.13 2.12M18 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5M17 12V9.5h2.5l1.96 2.5H17Z" />
                                    </svg>
                                    {{ __('admin/deliveriesPages.Deleted Companies') }}</a>
                            </div>
                        @endcan

                        {{-- Pagination Number --}}
                        <div class="form-inline justify-end my-2">
                            {{ __('pagination.Show') }} &nbsp;
                            <select wire:model='perPage' class="form-control w-auto px-3 cursor-pointer">
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

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>

                                {{-- Name --}}
                                <th wire:click="sortBy('name')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Name') }} &nbsp;
                                    @include('partials._sort_icon', [
                                        'field' => 'name->' . session('locale'),
                                    ])
                                </th>

                                {{-- Email --}}
                                <th wire:click="sortBy('email')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Contacts') }}&nbsp;
                                    @include('partials._sort_icon', ['field' => 'email'])
                                </th>

                                {{-- Active --}}
                                @can('Activate Delivery')
                                    <th wire:click="sortBy('is_active')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        {{ __('admin/deliveriesPages.Active') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'is_active'])
                                    </th>
                                @endcan

                                {{-- Manage --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    {{ __('admin/deliveriesPages.Manage') }}
                                    <span class="sr-only">{{ __('admin/deliveriesPages.Manage') }}</span>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($deliveries as $delivery)
                                {{-- photo & name --}}
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($delivery->logo_path)
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ asset('storage/images/deliveryCompanies/cropped100/' . $delivery->logo_path) }}"
                                                        alt="{{ $delivery->name . ' profile image' }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                        <span class="material-icons">
                                                            business
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ltr:ml-4 rtl:mr-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $delivery->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Contacts --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $delivery->email }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $delivery->phones->where('default', 1)->first()? $delivery->phones->where('default', 1)->first()->phone: '' }}
                                        </div>
                                    </td>

                                    {{-- Active --}}
                                    @can('Activate Delivery')
                                        <td class="px-6 py-2 text-center whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {!! $delivery->is_active ? '<span class="text-green-600">' . __('admin/deliveriesPages.Active') . '</span>' : '<span class="text-red-600">' . __('admin/deliveriesPages.Inactive') . '</span>' !!}
                                                {!! $delivery->is_active ? '<span class="block cursor-pointer material-icons text-green-600" wire:click="activate(' . $delivery->id . ')">toggle_on</span>' : '<span class="block cursor-pointer material-icons text-red-600" wire:click="activate(' . $delivery->id . ')">toggle_off</span>' !!}
                                            </div>
                                        </td>
                                    @endcan

                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Delivery Company Details --}}
                                        @can("See Delivery's Details")
                                            <a href="{{ route('admin.deliveries.show', ['delivery' => $delivery->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}" class="m-0">

                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-view hover:bg-viewHover rounded">
                                                    visibility
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Edit Button --}}
                                        @can('Edit Delivery')
                                            <a href="{{ route('admin.deliveries.edit', ['delivery' => $delivery->id]) }}"
                                                title="{{ __('admin/deliveriesPages.Edit') }}" class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                    edit
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Edit Company Zone --}}
                                        @can('Edit Zone')
                                            <a href="{{ route('admin.zones.deliveryZones.edit', ['delivery_id' => $delivery->id]) }}"
                                                title="{{ __('admin/deliveriesPages.Edit Zones') }}"
                                                class="m-0">

                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-role hover:bg-roleHover rounded">
                                                    edit_location_alt
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Delete Button --}}
                                        @can('Deleted Delivery')
                                            <a href="#" title="{{ __('admin/deliveriesPages.Delete') }}"
                                                wire:click.prevent="deleteConfirm({{ $delivery->id }})"
                                                class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                    delete
                                                </span>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="6">
                                        {{ $search == ''? __('admin/deliveriesPages.No data in this table'): __('admin/deliveriesPages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $deliveries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

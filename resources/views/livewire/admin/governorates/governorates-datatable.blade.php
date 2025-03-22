<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">
        <div class="py-3 bg-white space-y-6">
            <div class="flex flex-wrap justify-around md:justify-between gap-6 items-center my-2">

                {{-- Search Box --}}
                <div class="flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                        <span class="material-icons">
                            search
                        </span> </span>
                    <input type="text" wire:model.live='search'
                        class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                        placeholder="{{ __('admin/deliveriesPages.Search ...') }}">
                </div>

                {{-- Deleted Countries --}}
                <div class="ltr:text-right rtl:text-left">
                    <a href="{{ route('admin.governorates.softDeletedGovernorates') }}"
                        class="btn btn-sm bg-red-600 hover:bg-red-700 focus:bg-red-600 active:bg-red-600 font-bold">
                        <span class="material-icons rtl:ml-2 ltr:mr-2">
                            delete_forever
                        </span>
                        {{ __('admin/deliveriesPages.Deleted Governorates') }}</a>
                </div>

                {{-- Pagination Number --}}
                <div class="form-inline justify-end">
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
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>

                                {{-- Name --}}
                                <th wire:click="setSortBy('governorates.name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Name') }} &nbsp;
                                    @include('partials._sort_icon', [
                                        'field' => 'governorates.name->' . session('locale'),
                                    ])
                                </th>

                                {{-- Country Name --}}
                                <th wire:click="setSortBy('countries.name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Country Name') }}
                                    @include('partials._sort_icon', [
                                        'field' => 'countries.name->' . session('locale'),
                                    ])
                                </th>

                                {{-- Cities No. --}}
                                <th wire:click="setSortBy('cities_count')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Cities No.') }}
                                    @include('partials._sort_icon', [
                                        'field' => 'cities_count',
                                    ])
                                </th>

                                {{-- Users No. --}}
                                <th wire:click="setSortBy('customers_count')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Users No.') }}
                                    @include('partials._sort_icon', [
                                        'field' => 'customers_count',
                                    ])
                                </th>

                                {{-- Deliverry Comp. No. --}}
                                <th wire:click="setSortBy('deliveries_count')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    {{ __('admin/deliveriesPages.Delivery Comp. No.') }}
                                    @include('partials._sort_icon', [
                                        'field' => 'deliveries_count',
                                    ])
                                </th>

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
                            @forelse ($governorates as $governorate)
                                {{-- name --}}
                                <tr>
                                    <td class="px-6 py-2 whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $governorate->name }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Country Name --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="flex items-center content-center justify-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $governorate->country->name ?? __('admin/deliveriesPages.N/A') }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Cities No. --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        @if ($governorate->cities_count)
                                            <a href="{{ route('admin.governorates.citiesGovernorate', [$governorate->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $governorate->cities_count }}
                                                </span>

                                                <span class="material-icons text-lg text-white p-1 ltr:ml-1 rtl:mr-1">
                                                    visibility
                                                </span>
                                            </a>
                                        @else
                                            <div
                                                class="m-auto text-sm bg-red-400 rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">0</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Users. No. --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        @if ($governorate->customers_count)
                                            <a href="{{ route('admin.governorates.usersGovernorate', [$governorate->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $governorate->customers->groupBy('id')->count('id') }}
                                                </span>

                                                <span class="material-icons text-lg text-white p-1 ltr:ml-1 rtl:mr-1">
                                                    visibility
                                                </span>
                                            </a>
                                        @else
                                            <div
                                                class="m-auto text-sm bg-red-400 rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">0</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Deliverry Comp. No. --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        @if ($governorate->deliveries_count)
                                            <a href="{{ route('admin.governorates.deliveriesGovernorate', [$governorate->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $governorate->deliveries->groupBy('id')->count('id') }}
                                                </span>

                                                <span class="material-icons text-lg text-white p-1 ltr:ml-1 rtl:mr-1">
                                                    visibility
                                                </span>
                                            </a>
                                        @else
                                            <div
                                                class="m-auto text-sm bg-red-400 rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">0</span>
                                            </div>
                                        @endif
                                    </td>


                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.governorates.edit', [$governorate->id]) }}"
                                            title="{{ __('admin/deliveriesPages.Edit') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                edit
                                            </span>
                                        </a>

                                        {{-- Delete Button --}}
                                        <a href="#" title="{{ __('admin/deliveriesPages.Delete') }}"
                                            wire:click.prevent="deleteConfirm({{ $governorate->id }})" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                delete
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="6">
                                        {{ $search == '' ? __('admin/deliveriesPages.No data in this table') : __('admin/deliveriesPages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $governorates->links() }}
        </div>
    </div>
</div>

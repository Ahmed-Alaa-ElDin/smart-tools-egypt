<div>
    {{-- @dump($selectedProducts) --}}
    <div class="flex flex-col">

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
                        <input type="text" name="company-website" id="company-website" wire:model='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/productsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Deleted Brands --}}
                <div class="ltr:text-right rtl:text-left">
                    <a href="{{ route('admin.brands.softDeletedBrands') }}"
                        class="btn btn-sm bg-red-600 hover:bg-red-700 focus:bg-red-600 active:bg-red-600 font-bold">
                        <span class="material-icons rtl:ml-2 ltr:mr-2">
                            delete_forever
                        </span>
                        {{ __('admin/productsPages.Deleted Brands') }}</a>
                </div>

                {{-- Pagination Number --}}
                <div class="form-inline col-span-1 justify-end my-2">
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
        {{-- Search and Pagination Control --}}


        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Name Header --}}
                                <th wire:click="sortBy('name')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'name',
                                        ])
                                    </div>
                                </th>

                                {{-- Country Header --}}
                                <th wire:click="sortBy('country_id')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Country') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'country_id',
                                        ])
                                    </div>
                                </th>

                                {{-- Products Count Header --}}
                                <th wire:click="sortBy('products_count')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.No. of Products') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'products_count',
                                        ])
                                    </div>
                                </th>

                                {{-- Manage Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Manage') }}
                                        <span class="sr-only">{{ __('admin/productsPages.Manage') }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        {{-- Datatable Body --}}
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($brands as $brand)
                                <tr>

                                    {{-- Photo & Name Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center w-64">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($brand->logo_path != null)
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ asset('storage/images/logos/cropped200/' . $brand->logo_path) }}"
                                                        alt="{{ $brand->name . '-logo' }}">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                        <span class="material-icons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                                                role="img" width="1em" height="1em"
                                                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 64 64">
                                                                <path fill="currentColor"
                                                                    d="M36.604 23.043c-.623-.342-1.559-.512-2.805-.512h-6.693v7.795h6.525c1.295 0 2.268-.156 2.916-.473c1.146-.551 1.721-1.639 1.721-3.268c0-1.757-.555-2.939-1.664-3.542" />
                                                                <path fill="currentColor"
                                                                    d="M32.002 2C15.434 2 2 15.432 2 32s13.434 30 30.002 30s30-13.432 30-30s-13.432-30-30-30m12.82 44.508h-6.693a20.582 20.582 0 0 1-.393-1.555a14.126 14.126 0 0 1-.256-2.5l-.041-2.697c-.023-1.85-.344-3.084-.959-3.701c-.613-.615-1.766-.924-3.453-.924h-5.922v11.377H21.18V17.492h13.879c1.984.039 3.51.289 4.578.748s1.975 1.135 2.717 2.027a9.07 9.07 0 0 1 1.459 2.441c.357.893.537 1.908.537 3.051c0 1.379-.348 2.732-1.043 4.064s-1.844 2.273-3.445 2.826c1.338.537 2.287 1.303 2.844 2.293c.559.99.838 2.504.838 4.537v1.949c0 1.324.053 2.225.16 2.697c.16.748.533 1.299 1.119 1.652v.731z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div
                                                class="ltr:ml-4 rtl:mr-4 text-sm w-64 truncate font-medium text-gray-900">
                                                {{ $brand->name }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Country Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center">
                                            {{ $brand->country ? $brand->country->name : __('N/A') }}
                                        </div>
                                    </td>

                                    {{-- Products Count Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        @if ($brand->products_count)
                                            <a href="{{ route('admin.brands.productsBrand', [$brand->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $brand->products_count }}
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

                                    {{-- Manage Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Edit Button --}}
                                        @can('Edit User')
                                            <a href="{{ route('admin.brands.edit', ['brand' => $brand->id]) }}"
                                                title="{{ __('admin/productsPages.Edit') }}" class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                    edit
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Deleted Button --}}
                                        @can('Deleted User')
                                            <a href="#" title="{{ __('admin/productsPages.Delete') }}"
                                                wire:click.prevent="deleteConfirm({{ $brand->id }})"
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
                                        {{ $search == ''? __('admin/productsPages.No data in this table'): __('admin/productsPages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $brands->links() }}
        </div>
    </div>
</div>

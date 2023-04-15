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
                        <span
                            class="inline-flex items-center px-3 ltr:rounded-l-md rtl:rounded-r-md border border-r-0 border-gray-300 bg-gray-50 text-center text-gray-500 text-sm">
                            <span class="material-icons">
                                search
                            </span> </span>
                        <input type="text" wire:model='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/productsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Manage All --}}
                <div class="form-inline col-span-1 justify-center">
                    <div class="flex justify-center">
                        <button class="btn btn-success dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                manage_history
                            </span> &nbsp; {{ __('admin/productsPages.Manage All') }}
                            &nbsp;</button>
                        <div class="dropdown-menu">

                            <a href="#" wire:click.prevent="restoreAllConfirm"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-success focus:bg-success">
                                <span class="material-icons">
                                    restore
                                </span>
                                &nbsp;&nbsp;
                                {{ __('admin/productsPages.Restore All') }}</a>

                            <a href="#" wire:click.prevent="forceDeleteAllConfirm"
                                class="dropdown-item dropdown-item-pdf justify-center font-bold hover:bg-red-600 focus:bg-red-600">
                                <span class="material-icons">
                                    delete
                                </span>
                                &nbsp;&nbsp;
                                {{ __('admin/productsPages.Delete All Permanently') }}</a>
                        </div>
                    </div>
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
                                <th wire:click="sortBy('categories.name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'categories.name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Super Category Header --}}
                                <th wire:click="sortBy('supercategory_name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Supercategory') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'supercategory_name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Subcategory Header --}}
                                <th wire:click="sortBy('subcategories_count')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.No. of Subcategories') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'subcategories_count',
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
                            @forelse ($categories as $category)
                                <tr>

                                    {{-- Icon & Name Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="text-center text-sm  truncate font-medium text-gray-900">
                                            {{ $category->name }}
                                        </div>
                                    </td>

                                    {{-- Supercategory Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="text-center text-sm  truncate font-medium text-gray-900">
                                            {{ $category->supercategory ? $category->supercategory->name : __('N/A') }}
                                        </div>
                                    </td>

                                    {{-- SubCategory Count Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        @if ($category->subcategories_count)
                                            <a href="{{ route('admin.categories.subcategoriesCategory', [$category->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $category->subcategories_count }}
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

                                        {{-- Restore Button --}}
                                        <a href="#" title="{{ __('admin/productsPages.Restore') }}"
                                            wire:click.prevent="restoreConfirm({{ $category->id }})" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-success hover:bg-successDark rounded">
                                                restore
                                            </span>
                                        </a>

                                        {{-- Deleted Button --}}
                                        <a href="#" title="{{ __('admin/productsPages.Delete') }}"
                                            wire:click.prevent="deleteConfirm({{ $category->id }})" class="m-0">
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
                                        {{ $search == '' ? __('admin/productsPages.No data in this table') : __('admin/productsPages.No data available according to your search') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>

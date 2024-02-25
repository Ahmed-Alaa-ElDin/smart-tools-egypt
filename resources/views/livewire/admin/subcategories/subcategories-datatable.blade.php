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
                        <input type="text"   wire:model.live='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/productsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Deleted Countries --}}
                <div class="ltr:text-right rtl:text-left">
                    <a href="{{ route('admin.subcategories.softDeletedSubcategories') }}"
                        class="btn btn-sm bg-red-600 hover:bg-red-700 focus:bg-red-600 active:bg-red-600 font-bold">
                        <span class="material-icons rtl:ml-2 ltr:mr-2">
                            delete_forever
                        </span>
                        {{ __('admin/productsPages.Deleted Subcategories') }}</a>
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


        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Datatable Header --}}
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Name Header --}}
                                <th wire:click="sortBy('subcategories.name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'subcategories.name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Category Header --}}
                                <th wire:click="sortBy('category_name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Category') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'category_name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Subcategory Header --}}
                                <th wire:click="sortBy('supercategory_name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Supercategory') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'supercategory_name->' . session('locale'),
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

                                {{-- Publish Header --}}
                                <th wire:click="sortBy('publish')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Published') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'publish'])
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
                            @forelse ($subcategories as $subcategory)
                                <tr>

                                    {{-- Name Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="text-center text-sm  truncate font-medium text-gray-900">
                                            {{ $subcategory->name }}
                                        </div>
                                    </td>

                                    {{-- Category Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="text-center text-sm  truncate font-medium text-gray-900">
                                            {{ $subcategory->category ? $subcategory->category->name : __('N/A') }}
                                        </div>
                                    </td>

                                    {{-- Supercategory Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="text-center text-sm  truncate font-medium text-gray-900">
                                            {{ $subcategory->supercategory ? $subcategory->supercategory->name : __('N/A') }}
                                        </div>
                                    </td>

                                    {{-- Products Count Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        @if ($subcategory->products_count)
                                            <a href="{{ route('admin.subcategories.productsSubcategory', [$subcategory->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $subcategory->products_count }}
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

                                    {{-- Publish Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {!! $subcategory->publish
                                                ? '<span class="block cursor-pointer material-icons text-success" wire:click="publish(' .
                                                    $subcategory->id .
                                                    ')">toggle_on</span>'
                                                : '<span class="block cursor-pointer material-icons text-red-600" wire:click="publish(' .
                                                    $subcategory->id .
                                                    ')">toggle_off</span>' !!}
                                        </div>
                                    </td>

                                    {{-- Manage Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.subcategories.edit', ['subcategory' => $subcategory->id]) }}"
                                            title="{{ __('admin/productsPages.Edit') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                edit
                                            </span>
                                        </a>

                                        {{-- Deleted Button --}}
                                        <a href="#" title="{{ __('admin/productsPages.Delete') }}"
                                            wire:click.prevent="deleteConfirm({{ $subcategory->id }})" class="m-0">
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
            {{ $subcategories->links() }}
        </div>
    </div>
</div>

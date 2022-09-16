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
                        <input type="text" name="company-website" id="company-website" wire:model='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/productsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Deleted Countries --}}
                <div class="ltr:text-right rtl:text-left">
                    <a href="{{ route('admin.supercategories.softDeletedSupercategories') }}"
                        class="btn btn-sm bg-red-600 hover:bg-red-700 focus:bg-red-600 active:bg-red-600 font-bold">
                        <span class="material-icons rtl:ml-2 ltr:mr-2">
                            delete_forever
                        </span>
                        {{ __('admin/productsPages.Deleted Supercategories') }}</a>
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
                                <th wire:click="sortBy('name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Category Header --}}
                                <th wire:click="sortBy('categories_count')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.No. of Categories') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'categories_count',
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
                            @forelse ($supercategories as $supercategory)
                                <tr>

                                    {{-- Icon & Name Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center ">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($supercategory->icon != null)
                                                    <div
                                                        class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                        <span class="material-icons">
                                                            {!! $supercategory->icon !!}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                        <span class="material-icons">
                                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                                                role="img" width="1em" height="1em"
                                                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"
                                                                class="inline-block">
                                                                <path fill="currentColor"
                                                                    d="M30 30h-8V4h8zm-10 0h-8V12h8zm-10 0H2V18h8z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ltr:ml-4 rtl:mr-4 text-sm  truncate font-medium text-gray-900">
                                                {{ $supercategory->name }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Category Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        @if ($supercategory->categories_count)
                                            <a href="{{ route('admin.supercategories.categoriesSupercategory', [$supercategory->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $supercategory->categories_count }}
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

                                    {{-- Products Count Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        @if ($supercategory->subcategories_count)
                                            <a href="{{ route('admin.supercategories.subcategoriesSupercategory', [$supercategory->id]) }}"
                                                title="{{ __('admin/deliveriesPages.View') }}"
                                                class="m-auto text-sm bg-view hover:bg-viewHover rounded p-1 max-w-max h-9 flex flex-row justify-center items-center content-center">
                                                <span class="bg-white rounded py-1 px-2">
                                                    {{ $supercategory->subcategories_count }}
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
                                            {!! $supercategory->publish
                                                ? '<span class="block cursor-pointer material-icons text-success" wire:click="publish(' .
                                                    $supercategory->id .
                                                    ')">toggle_on</span>'
                                                : '<span class="block cursor-pointer material-icons text-red-600" wire:click="publish(' .
                                                    $supercategory->id .
                                                    ')">toggle_off</span>' !!}
                                        </div>
                                    </td>

                                    {{-- Manage Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- Edit Button --}}
                                        @can('Edit User')
                                            <a href="{{ route('admin.supercategories.edit', ['supercategory' => $supercategory->id]) }}"
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
                                                wire:click.prevent="deleteConfirm({{ $supercategory->id }})"
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
            {{ $supercategories->links() }}
        </div>
    </div>
</div>

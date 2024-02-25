<div>
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
                            <input type="text" wire:model.live.debounce.500ms="search"
                                class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                                placeholder="{{ __('admin/productsPages.Search ...') }}">
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


            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- Datatable Header --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- Multiple Select Header --}}
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                        <div class="min-w-max">
                                            <input type="checkbox" wire:model.live="selectAllCollections"
                                            class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                            </div>
                                    </th>

                                    {{-- Name Header --}}
                                    <th wire:click="sortBy('collections.name->{{ session('locale') }}')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        <div class="min-w-max">
                                            {{ __('admin/productsPages.Name') }} &nbsp;
                                            @include('partials._sort_icon', [
                                                'field' => 'collections.name->' . session('locale'),
                                            ])
                                        </div>
                                    </th>

                                    {{-- No of Items Header --}}
                                    <th wire:click="sortBy('products_count')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        <div class="min-w-max">
                                            {{ __('admin/productsPages.No. of Products') }} &nbsp;
                                            @include('partials._sort_icon', [
                                                'field' => 'products_count',
                                            ])
                                        </div>
                                    </th>

                                    {{-- Price Header --}}
                                    <th wire:click="sortBy('final_price')" scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                        <div class="min-w-max">
                                            {{ __('admin/productsPages.Price') }}&nbsp;
                                            @include('partials._sort_icon', [
                                                'field' => 'final_price',
                                            ])
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            {{-- Datatable Body --}}
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($collections as $collection)
                                    <tr>
                                        {{-- select collection Body --}}
                                        <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                            <div class="flex items-center content-center">
                                                <input type="checkbox" wire:model.live="selectedCollections"
                                                    value="{{ $collection->id }}"
                                                    class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                            </div>
                                        </td>

                                        {{-- Photo & Name Body --}}
                                        <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                            <div class="flex items-center content-center w-64">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if ($collection->thumbnail)
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ asset('storage/images/collections/cropped100/' . $collection->thumbnail->file_name) }}"
                                                            alt="{{ $collection->name . 'image' }}">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-full text-white bg-secondary flex justify-center items-center">
                                                            <span class="material-icons">
                                                                construction
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div
                                                    class="ltr:ml-4 rtl:mr-4 text-sm w-64 truncate font-medium text-gray-900">
                                                    {{ $collection->name }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- No of Items Body --}}
                                        <td class="px-6 py-2 text-center whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $collection->products->count() }}
                                            </div>
                                        </td>

                                        {{-- Price Body --}}
                                        <td class="px-6 py-2 text-center whitespace-nowrap">
                                            <div class="text-sm">
                                                @if ($collection->under_reviewing)
                                                    <span class="bg-yellow-600 px-2 py-1 rounded text-white text-xs">
                                                        {{ __('admin/productsPages.Under Reviewing') }}
                                                    </span>
                                                @else
                                                    <div class="flex flex-wrap gap-2 items-center justify-center">
                                                        <span class="bg-secondary px-2 py-1 rounded text-white">
                                                            <span dir='ltr'>
                                                                {{ number_format($collection->original_price, 2, '.', '\'') }}
                                                            </span>
                                                            <span class="text-xs p-1">
                                                                {{ __('admin/productsPages. EGP') }}
                                                            </span>
                                                        </span>
                                                        @if ($collection->final_price == $collection->base_price)
                                                            <span class="bg-success px-2 py-1 rounded text-white">
                                                                <span dir='ltr'>
                                                                    {{ number_format($collection->final_price, 2, '.', '\'') }}
                                                                </span>
                                                                <span class="text-xs p-1">
                                                                    {{ __('admin/productsPages. EGP') }}
                                                                </span>
                                                            </span>
                                                        @else
                                                            <span
                                                                class="line-through bg-red-600 px-2 py-1 rounded text-white">
                                                                <span dir='ltr'>
                                                                    {{ number_format($collection->base_price, 2, '.', '\'') }}
                                                                </span>
                                                                <span class="text-xs p-1">
                                                                    {{ __('admin/productsPages. EGP') }}
                                                                </span>
                                                            </span>
                                                            <span class="bg-success px-2 py-1 rounded text-white">
                                                                <span dir='ltr'>
                                                                    {{ number_format($collection->final_price, 2, '.', '\'') }}
                                                                </span>
                                                                <span class="text-xs p-1">
                                                                    {{ __('admin/productsPages. EGP') }}
                                                                </span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
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
                {{ $collections->links() }}
            </div>
        </div>
    </div>
</div>

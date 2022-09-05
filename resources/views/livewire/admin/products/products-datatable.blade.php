<div>
    {{-- Loader : Start --}}
    <x-admin.waiting />
    {{-- Loader : End --}}

    <div class="flex flex-col">

        {{-- Multiple Selection Section --}}
        @if (count($selectedProducts))
            <div class="flex justify-around  items-center">
                <div
                    class="bg-primary rounded-full text-white font-bold px-3 py-2 flex justify-between items-center shadow gap-x-2 text-xs">
                    {{ trans_choice('admin/productsPages.Product Selected', count($selectedProducts), ['product' => count($selectedProducts)]) }}
                    <span
                        class="material-icons w-4 h-4 bg-white text-black p-2 rounded-full flex justify-center items-center text-xs font-bold text-red-800 cursor-pointer"
                        wire:click="unselectAll" title="{{ __('admin/productsPages.Unselect All') }}">close</span>
                </div>
                <div>
                    <div class="flex justify-center">
                        <button class="btn btn-warning dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                settings
                            </span> &nbsp; {{ __('admin/productsPages.Control selected products') }}
                            &nbsp;</button>
                        <div class="dropdown-menu text-black ">
                            <a wire:click.prevent="deleteAllConfirm"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-red-600 focus:bg-red-600 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    delete
                                </span> &nbsp;&nbsp;
                                {{ __('admin/productsPages.Delete All') }}
                            </a>
                            <a wire:click.prevent="publishAllConfirm"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-success focus:bg-success hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    publish
                                </span> &nbsp;&nbsp;
                                {{ __('admin/productsPages.Publish All') }}
                            </a>
                            <a wire:click.prevent="hideAllConfirm"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-red-600 focus:bg-red-600 hover:text-white focus:text-white cursor-pointer">
                                <span class="material-icons">
                                    hide_source
                                </span> &nbsp;&nbsp;
                                {{ __('admin/productsPages.Hide All') }}
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
                        <input type="text" name="company-website" id="company-website" wire:model='search'
                            class="focus:ring-primary focus:border-primary flex-1 block w-full rounded-none ltr:rounded-r-md rtl:rounded-l-md sm:text-sm border-gray-300"
                            placeholder="{{ __('admin/productsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Download --}}
                <div class="form-inline col-span-1 justify-center">
                    <div class="flex justify-center">
                        <button class="btn btn-success dropdown-toggle btn-round btn-sm text-white font-bold "
                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="material-icons">
                                file_download
                            </span> &nbsp; {{ __('admin/productsPages.Export Products') }}
                            &nbsp;</button>
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.products.exportExcel') }}"
                                class="dropdown-item dropdown-item-excel justify-center font-bold hover:bg-success focus:bg-success">
                                <span class="material-icons">
                                    file_present
                                </span> &nbsp;&nbsp;
                                {{ __('admin/productsPages.download all excel') }}</a>
                            <a href="{{ route('admin.products.exportPDF') }}"
                                class="dropdown-item dropdown-item-pdf justify-center font-bold hover:bg-red-600 focus:bg-red-600">
                                <span class="material-icons">
                                    picture_as_pdf
                                </span>
                                &nbsp;&nbsp;
                                {{ __('admin/productsPages.download all pdf') }}</a>
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
                                {{-- Multiple Select Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        #
                                    </div>
                                </th>

                                {{-- Name Header --}}
                                <th wire:click="sortBy('products.name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'products.name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Brand Header --}}
                                <th wire:click="sortBy('brand_name')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Brand') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'brand_name',
                                        ])
                                    </div>
                                </th>

                                {{-- Sub Category Header --}}
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Subcategory') }}&nbsp;
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

                                {{-- Quantity Header --}}
                                <th wire:click="sortBy('quantity')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Quantity') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'quantity'])
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
                            @forelse ($products as $product)
                                <tr>
                                    {{-- select product Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center">
                                            <input type="checkbox" wire:model="selectedProducts"
                                                value="{{ $product->id }}"
                                                class="appearance-none border-gray-600 rounded-full checked:bg-secondary outline-none ring-0 cursor-pointer">
                                        </div>
                                    </td>

                                    {{-- Photo & Name Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center w-64">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if ($product->thumbnail)
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="{{ asset('storage/images/products/cropped100/' . $product->thumbnail->file_name) }}"
                                                        alt="{{ $product->name . 'image' }}">
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
                                                {{ $product->name }}
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Brand Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center content-center justify-center">
                                            {{ $product->brand ? $product->brand->name : __('N/A') }}
                                        </div>
                                    </td>

                                    {{-- Sub Category Body --}}
                                    <td class="px-6 py-2 max-w-min whitespace-nowrap overflow-hidden">
                                        <div class="flex flex-wrap items-center content-center justify-center">
                                            @forelse ($product->subcategories as $subcategory)
                                                <span class="inline-block">
                                                    {{ $subcategory->name }}
                                                </span>
                                                @if (!$loop->last)
                                                    &nbsp; {{ ',' }} &nbsp;
                                                @endif
                                            @empty
                                                {{ __('N/A') }}
                                            @endforelse
                                        </div>
                                    </td>

                                    {{-- Price Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm">
                                            @if ($product->under_reviewing)
                                                <span class="bg-yellow-600 px-2 py-1 rounded text-white text-xs">
                                                    {{ __('admin/productsPages.Under Reviewing') }}
                                                </span>
                                            @elseif ($product->final_price == $product->base_price)
                                                <span class="bg-success px-2 py-1 rounded text-white">
                                                    <span dir='ltr'>
                                                        {{ number_format($product->final_price, 2, '.', '\'') }}
                                                    </span>
                                                    <span class="text-xs p-1">
                                                        {{ __('admin/productsPages. EGP') }}
                                                    </span>
                                                </span>
                                            @else
                                                <span
                                                    class="line-through bg-red-600 px-2 py-1 rounded text-white text-xs">
                                                    <span dir='ltr'>
                                                        {{ number_format($product->base_price, 2, '.', '\'') }}
                                                    </span>
                                                    <span class="text-xs p-1">
                                                        {{ __('admin/productsPages. EGP') }}
                                                    </span>
                                                </span>
                                                <span
                                                    class="bg-success px-2 py-1 rounded text-white ltr:ml-1 rtl:mr-1">
                                                    <span dir='ltr'>
                                                        {{ number_format($product->final_price, 2, '.', '\'') }}
                                                    </span>
                                                    <span class="text-xs p-1">
                                                        {{ __('admin/productsPages. EGP') }}
                                                    </span>
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Quantity Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div
                                            class="text-sm  @if ($product->quantity > $product->low_stock + 2) text-success
                                            @elseif ($product->quantity > $product->low_stock)
                                            text-yellow-600
                                        @else
                                            text-red-600 @endif">
                                            {{ $product->quantity }}
                                        </div>
                                    </td>

                                    {{-- Publish Body --}}
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {!! $product->publish
                                                ? '<span class="block cursor-pointer material-icons text-success" wire:click="publish(' .
                                                    $product->id .
                                                    ')">toggle_on</span>'
                                                : '<span class="block cursor-pointer material-icons text-red-600" wire:click="publish(' .
                                                    $product->id .
                                                    ')">toggle_off</span>' !!}
                                        </div>
                                    </td>

                                    {{-- Manage Body --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">

                                        {{-- User Details --}}
                                        @can("See User's Details")
                                            <a href="{{ route('front.product.show', ['id' => $product->id, 'slug' => $product->slug]) }}"
                                                title="{{ __('admin/productsPages.View') }}" class="m-0">
                                                <span
                                                    class="material-icons p-1 text-lg w-9 h-9 text-white bg-view hover:bg-viewHover rounded">
                                                    visibility
                                                </span>
                                            </a>
                                        @endcan

                                        {{-- Edit Button --}}
                                        @can('Edit User')
                                            <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}"
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
                                                wire:click.prevent="deleteConfirm({{ $product->id }})" class="m-0">
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
            {{ $products->links() }}
        </div>
    </div>
</div>

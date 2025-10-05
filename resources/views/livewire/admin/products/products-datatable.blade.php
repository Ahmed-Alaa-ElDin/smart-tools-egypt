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

            <div class="flex flex-wrap justify-between gap-6 items-center">
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
                            placeholder="{{ __('admin/productsPages.Search ...') }}">
                    </div>
                </div>

                {{-- Download --}}
                <div class="form-inline col-span-1 justify-center">
                    <div class="flex justify-center">
                        <button class="btn btn-success dropdown-toggle btn-round btn-sm text-white font-bold"
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

                {{-- Upload --}}
                <div class="form-inline col-span-1 justify-center">
                    <div class="flex justify-center">
                        <button class="btn btn-success btn-round btn-sm text-white font-bold" type="button" onclick="modal.show()">
                            <span class="material-icons">
                                file_upload
                            </span> &nbsp; {{ __('admin/productsPages.Bulk Products Update') }}
                        </button>
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

        {{-- Datatable --}}
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
                                <th wire:click="setSortBy('products.name->{{ session('locale') }}')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Name') }} &nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'products.name->' . session('locale'),
                                        ])
                                    </div>
                                </th>

                                {{-- Brand Header --}}
                                <th wire:click="setSortBy('brand_name')" scope="col"
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
                                <th wire:click="setSortBy('final_price')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Price') }}&nbsp;
                                        @include('partials._sort_icon', [
                                            'field' => 'final_price',
                                        ])
                                    </div>
                                </th>

                                {{-- Quantity Header --}}
                                <th wire:click="setSortBy('quantity')" scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none">
                                    <div class="min-w-max">
                                        {{ __('admin/productsPages.Quantity') }}&nbsp;
                                        @include('partials._sort_icon', ['field' => 'quantity'])
                                    </div>
                                </th>

                                {{-- Publish Header --}}
                                <th wire:click="setSortBy('publish')" scope="col"
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
                                            <input type="checkbox" wire:model.live="selectedProducts"
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
                                            @else
                                                <div class="flex flex-wrap gap-2 items-center justify-center">
                                                    <span class="bg-secondary px-2 py-1 rounded text-white">
                                                        <span dir='ltr'>
                                                            {{ number_format($product->original_price, 2, '.', '\'') }}
                                                        </span>
                                                        <span class="text-xs p-1">
                                                            {{ __('admin/productsPages. EGP') }}
                                                        </span>
                                                    </span>
                                                    @if ($product->final_price == $product->base_price)
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
                                                            class="line-through bg-red-600 px-2 py-1 rounded text-white">
                                                            <span dir='ltr'>
                                                                {{ number_format($product->base_price, 2, '.', '\'') }}
                                                            </span>
                                                            <span class="text-xs p-1">
                                                                {{ __('admin/productsPages. EGP') }}
                                                            </span>
                                                        </span>
                                                        <span class="bg-success px-2 py-1 rounded text-white">
                                                            <span dir='ltr'>
                                                                {{ number_format($product->final_price, 2, '.', '\'') }}
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

                                        {{-- Product Details --}}
                                        <a href="{{ route('front.products.show', ['id' => $product->id, 'slug' => $product->slug]) }}" target="_blank"
                                            title="{{ __('admin/productsPages.View') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-view hover:bg-viewHover rounded">
                                                visibility
                                            </span>
                                        </a>


                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}"
                                            title="{{ __('admin/productsPages.Edit') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-edit hover:bg-editHover rounded">
                                                edit
                                            </span>
                                        </a>

                                        {{-- Deleted Button --}}
                                        <a href="#" title="{{ __('admin/productsPages.Delete') }}"
                                            wire:click.prevent="deleteConfirm({{ $product->id }})" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-delete hover:bg-deleteHover rounded">
                                                delete
                                            </span>
                                        </a>

                                        {{-- Copy Product --}}
                                        <a href="{{ route('admin.products.copy', ['product_id' => $product->id]) }}"
                                            title="{{ __('admin/productsPages.Copy Product') }}" class="m-0">
                                            <span
                                                class="material-icons p-1 text-lg w-9 h-9 text-white bg-yellow-400 hover:bg-yellow-500 rounded">
                                                content_copy
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-2 font-bold" colspan="8">
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

    {{-- Bulk Update Modal Start --}}
    <div id="bulk-update-modal" tabindex="-1" wire:ignore.self onclick="modal.hide()"
        class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center backdrop-blur cursor-pointer hidden"
        aria-modal="true" role="dialog">
        <div class="relative p-4 w-full max-w-2xl" wire:click.stop>
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-4 rounded-t border-b">
                    <h3 class="grow text-xl font-semibold text-gray-900 dark:text-white">
                        {{ __('admin/productsPages.Bulk Products Update') }}
                    </h3>
                    <button type="button" onclick="modal.hide()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap gap-3 justify-around items-start w-full">
                        {{-- Upload input --}}
                        <div class="flex flex-col w-full">
                            <label for="bulkUpdateFile"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ __('admin/productsPages.Upload Excel File') }}
                            </label>
                            <input type="file" wire:model.live="bulkUpdateFile" id="bulkUpdateFile"
                                class="col-span-12 md:col-span-6 md:col-start-4 block w-full pl-3 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300">
                            @error('bulkUpdateFile')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-around p-2 space-x-2 rounded-b border-t border-gray-200">
                    <button type="button" wire:click="bulkUpdate"
                        class="btn font-bold text-white bg-success hover:bg-successDark hover:text-white focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 ">
                        {{ __('admin/productsPages.Update') }}
                    </button>

                    <button type="button" onclick="modal.hide()"
                        class="btn font-bold bg-primary focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        {{ __('admin/productsPages.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Bulk Update Modal : End --}}
</div>

@push('livewire-js')
    <script>
        const bulkUpdateModal = document.getElementById('bulk-update-modal');
        const modal = new Modal (bulkUpdateModal);

        // Hide the modal the livewire dispatches the event "bulkUpdateCloseModal"
        window.addEventListener('bulkUpdateCloseModal', event => {
            modal.hide();
        });
    </script>
@endpush

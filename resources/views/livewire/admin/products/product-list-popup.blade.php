<div wire:click.self="$set('show', false)"
    class="@if (!$show) hidden @else flex @endif backdrop-blur overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full max-h-full scrollbar scrollbar-thin scrollbar-thumb-red-100 scrollbar-track-gray-100">

    <div class="relative my-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('admin/productsPages.Product List') }}
                </h3>
                <button type="button" wire:click="$set('show', false)"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 overflow-hidden">

                {{-- Loader : Start --}}
                <x-admin.waiting />
                {{-- Loader : End --}}

                <div class="flex flex-col">

                    {{-- Multiple Selection Section --}}
                    @if ($totalSelected)
                        <div class="flex flex-wrap gap-2 justify-around items-center">
                            {{-- Unselect All --}}
                            <div
                                class="bg-primary rounded-full text-white font-bold px-3 py-2 flex justify-between items-center shadow gap-x-2 text-xs">
                                {{ trans_choice('admin/productsPages.Item Selected', $totalSelected, ['Item' => $totalSelected]) }}
                                <span
                                    class="material-icons w-4 h-4 bg-white text-black p-2 rounded-full flex justify-center items-center text-xs font-bold text-red-800 cursor-pointer"
                                    wire:click="$emit('unselectAll')"
                                    title="{{ __('admin/productsPages.Unselect All') }}">close</span>
                            </div>

                            {{-- Add Selected --}}
                            <div>
                                <div class="flex justify-center">
                                    <button class="btn btn-success btn-round btn-sm text-white font-bold" wire:click="addSelected"
                                        type="button">
                                        <span class="material-icons">
                                            add
                                        </span>
                                        {{ __('admin/productsPages.Add Selected') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Multiple Selection Section --}}

                    {{-- Tabs --}}
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap items-center justify-around -mb-px text-sm font-medium text-center list-unstyled"
                            id="default-tab">
                            <li class="me-2" role="presentation">
                                <button wire:click="$set('model','product')"
                                    class="inline-block p-4 border-b-2 rounded-t-lg @if ($model != 'product') hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 @else text-primary border-primary @endif"
                                    id="productsTab-tab" type="button">Products</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button wire:click="$set('model','collection')"
                                    class="inline-block p-4 border-b-2 rounded-t-lg @if ($model != 'collection') hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 @else text-primary border-primary @endif"
                                    id="collectionsTab-tab" type="button">Collections</button>
                            </li>
                        </ul>
                    </div>

                    <div id="default-tab-content">
                        {{-- Products :: Start --}}
                        <div class="@if ($model != 'product') hidden @endif" id="productsTab">
                            @livewire('admin.products.product-list-datatable', ['excludedProducts' => $excludedProducts])
                        </div>
                        {{-- Products :: End --}}

                        {{-- Collections :: Start --}}
                        <div class="@if ($model != 'collection') hidden @endif" id="collectionsTab">
                            @livewire('admin.collections.collection-list-datatable', ['excludedCollections' => $excludedCollections])
                        </div>
                        {{-- Collections :: End --}}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

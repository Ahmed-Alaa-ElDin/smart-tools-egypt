<div class="w-full rounded-2xl shadow-sm border border-gray-200"
    style="background: linear-gradient(180deg, #fff5f5 0%, #fff 100%);">
    <x-admin.waiting />

    <div class="px-4 py-3 border-b border-red-100 flex items-center gap-2">
        <span class="material-icons text-lg" style="color: #dc2626;">inventory_2</span>
        <span class="text-sm font-bold text-gray-800 m-0">
            {{ __('admin/ordersPages.Products Choosing') }}
        </span>
    </div>
    <div class="p-4">
        <div class="flex flex-wrap-reverse justify-around items-center gap-3 ">
            <div class="relative w-full md:w-auto md:min-w-[50%]">

                {{-- Search Product Input :: Start --}}
                <div class="flex rounded-xl shadow-sm overflow-hidden">
                    <span class="inline-flex items-center px-3 border border-r-0 text-center text-white text-sm"
                        style="background: linear-gradient(135deg, #dc2626, #b91c1c); border-color: #dc2626;">
                        <span class="material-icons text-base">
                            search
                        </span>
                    </span>
                    <input type="text" wire:model.live.debounce.500ms='search'
                        wire:blur.debounce.200ms="$set('search','')" data-name="new-order-products-part"
                        class="searchInput focus:ring-2 focus:ring-red-200 focus:border-red-300 flex-1 block rounded-none ltr:rounded-r-xl rtl:rounded-l-xl sm:text-sm border-gray-200 transition-all duration-200"
                        placeholder="{{ __('admin/ordersPages.Search ...') }}">
                </div>
                {{-- Search Product Input :: End --}}

                @if ($search != null)
                    <div
                        class="absolute top-full left-0 w-full z-[100] bg-white border border-gray-200 max-h-80 overflow-y-auto rounded-b-2xl p-2 shadow-2xl scrollbar scrollbar-thin scrollbar-thumb-red-200">
                        {{-- Loading :: Start --}}
                        <div wire:loading.delay wire:target="search" class="w-full">
                            <div class="flex flex-col gap-2 justify-center items-center p-8 text-slate-400">
                                <span class="animate-spin text-red-600">
                                    <span class="material-icons text-4xl">sync</span>
                                </span>
                                <span
                                    class="text-xs font-black uppercase tracking-widest">{{ __('admin/ordersPages.Searching ...') }}</span>
                            </div>
                        </div>

                        {{-- Products List :: Start --}}
                        <div wire:loading.remove wire:target="search" class="space-y-1">
                            @forelse ($products_list as $product)
                                <div class="group flex items-center gap-4 cursor-pointer rounded-xl transition-all duration-300 hover:bg-red-50/50 p-3 border border-transparent hover:border-red-100"
                                    wire:click.stop="addProduct({{ $product['id'] }}, '{{ $product['type'] }}')"
                                    wire:key="product-result-{{ $product['id'] }}-{{ $product['type'] }}">

                                    {{-- Thumbnail --}}
                                    <div
                                        class="w-12 h-12 flex-shrink-0 bg-white rounded-lg border border-slate-100 p-1 shadow-sm overflow-hidden group-hover:scale-110 transition-transform">
                                        @if (isset($product['thumbnail']) && $product['thumbnail'])
                                            <img src="{{ $product['type'] == 'Product' ? asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) : asset('storage/images/collections/cropped100/' . $product['thumbnail']['file_name']) }}"
                                                alt="" class="w-full h-full object-contain">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-200">
                                                <span class="material-icons text-xl">inventory_2</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-grow min-w-0">
                                        @if (isset($product['brand']))
                                            <span
                                                class="text-[9px] font-black text-red-600 uppercase tracking-widest block mb-0.5">{{ $product['brand']['name'] ?? '' }}</span>
                                        @endif
                                        <h5
                                            class="text-sm font-black text-slate-800 truncate group-hover:text-red-600 transition-colors">
                                            {{ $product['name'][session('locale')] }}
                                        </h5>
                                        <div
                                            class="flex items-center gap-2 text-[10px] font-bold text-slate-400 mt-0.5">
                                            <span class="material-icons text-[12px]">barcode_reader</span>
                                            {{ $product['barcode'] ?? 'N/A' }}
                                        </div>
                                    </div>

                                    {{-- Pricing --}}
                                    <div class="flex flex-col items-end flex-shrink-0 gap-1">
                                        @if ($product['under_reviewing'])
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-100 text-amber-600 uppercase tracking-wider">
                                                {{ __('admin/productsPages.Under Reviewing') }}
                                            </span>
                                        @else
                                            <div class="flex items-center gap-2">
                                                @if ($product['final_price'] < $product['base_price'])
                                                    <span class="text-[10px] font-bold text-slate-300 line-through"
                                                        dir="ltr">
                                                        {{ number_format($product['base_price'], 2) }}
                                                    </span>
                                                @endif
                                                <span
                                                    class="px-2 py-0.5 rounded-lg text-xs font-black bg-emerald-500 text-white shadow-sm shadow-emerald-500/20"
                                                    dir="ltr">
                                                    {{ number_format($product['final_price'], 2) }}
                                                    <small class="text-[8px] font-bold opacity-80">EGP</small>
                                                </span>
                                            </div>
                                            @if ($product['points'] > 0)
                                                <div
                                                    class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-amber-50 text-amber-600 text-[9px] font-black border border-amber-100">
                                                    <span class="material-icons text-[10px]">stars</span>
                                                    {{ $product['points'] }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    <span
                                        class="material-icons text-slate-200 text-3xl mb-1">sentiment_dissatisfied</span>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                        {{ __('admin/ordersPages.No Products Found') }}</p>
                                </div>
                            @endforelse
                        </div>
                        {{-- Products List :: End --}}
                    </div>
                @endif
            </div>

            @if (count($products))
                <div>
                    {{-- Clear All Products --}}
                    <button wire:click="clearProducts"
                        class="inline-flex items-center gap-1.5 text-white font-bold text-xs rounded-xl px-3 py-2 shadow-sm transition-all duration-200 hover:shadow-md"
                        style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <span class="material-icons text-sm">
                            close
                        </span>
                        {{ __('admin/ordersPages.Clear Products') }}
                    </button>
                </div>
            @endif
        </div>

        {{-- Product Selected :: Start --}}
        @if (count($products))
            <div class="mt-8 space-y-4">
                @forelse ($products as $product)
                    <div class="group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 p-4"
                        wire:key='selected-product-{{ $product['id'] }}-{{ $product['type'] }}'>

                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            {{-- Thumbnail --}}
                            <div class="relative flex-shrink-0">
                                <div
                                    class="w-24 h-24 bg-white rounded-xl overflow-hidden border border-slate-100 p-2 shadow-sm group-hover:scale-105 transition-transform duration-500">
                                    @if (isset($product['thumbnail']) && $product['thumbnail'])
                                        <img src="{{ $product['type'] == 'Product' ? asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) : asset('storage/images/collections/cropped100/' . $product['thumbnail']['file_name']) }}"
                                            alt="" class="w-full h-full object-contain">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-200">
                                            <span class="material-icons text-4xl">inventory_2</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- Quick Link --}}
                                <a href="{{ $product['type'] == 'Product' ? route('front.products.show', ['id' => $product['id'], 'slug' => $product['slug'][session('locale')]]) : route('front.collections.show', ['id' => $product['id'], 'slug' => $product['slug'][session('locale')]]) }}"
                                    target="_blank"
                                    class="absolute -bottom-1 -left-1 w-6 h-6 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                                    <span class="material-icons text-[14px]">open_in_new</span>
                                </a>
                            </div>

                            {{-- Info --}}
                            <div class="flex-grow min-w-0 text-center sm:text-left rtl:sm:text-right">
                                @if (isset($product['brand']))
                                    <span
                                        class="text-[10px] font-black text-red-600 uppercase tracking-widest block mb-1">
                                        {{ $product['brand']['name'] ?? '' }}
                                    </span>
                                @endif
                                <h4
                                    class="text-lg font-black text-slate-800 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors duration-300">
                                    {{ $product['name'][session('locale')] }}
                                </h4>

                                <div class="flex flex-wrap justify-center sm:justify-start items-center gap-3 mt-3">
                                    <div
                                        class="flex items-center gap-1 px-2 py-1 bg-slate-50 rounded-lg text-[11px] font-bold text-slate-400 border border-slate-100">
                                        <span class="material-icons text-[14px]">barcode_reader</span>
                                        {{ $product['barcode'] ?? 'N/A' }}
                                    </div>

                                    @if ($product['points'] > 0)
                                        <div
                                            class="flex items-center gap-1 px-2 py-1 bg-amber-50 rounded-lg text-[11px] font-bold text-amber-600 border border-amber-100">
                                            <span class="material-icons text-[14px]">stars</span>
                                            {{ $product['points'] }} {{ __('admin/ordersPages.Points') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions & Pricing --}}
                            <div class="flex flex-col items-center sm:items-end gap-3 min-w-[180px]">
                                {{-- Price Display --}}
                                <div class="text-right rtl:text-left">
                                    <div class="flex items-center gap-2 justify-center sm:justify-end">
                                        @if ($product['final_price'] < $product['base_price'])
                                            <span class="text-xs font-bold text-slate-300 line-through" dir="ltr">
                                                {{ number_format($product['base_price'], 2) }}
                                            </span>
                                        @endif
                                        <div class="text-xl font-black text-slate-900" dir="ltr">
                                            {{ number_format($product['final_price'], 2) }}
                                            <small class="text-xs font-bold text-slate-400">EGP</small>
                                        </div>
                                    </div>
                                    @if ($product['final_price'] < $product['base_price'])
                                        <div class="text-[10px] font-black text-emerald-600 uppercase tracking-tighter">
                                            {{ __('admin/ordersPages.Saved :amount EGP', ['amount' => number_format($product['base_price'] - $product['final_price'], 2)]) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Quantity Controls --}}
                                <div class="flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
                                    <button
                                        wire:click="amountUpdated('{{ $product['id'] }}','{{ $product['type'] }}',{{ $product['amount'] - 1 }})"
                                        class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-600 hover:bg-red-600 hover:text-white transition-all duration-200">
                                        <span class="material-icons text-sm">remove</span>
                                    </button>

                                    <input type="text" value="{{ $product['amount'] }}"
                                        wire:change="amountUpdated('{{ $product['id'] }}', '{{ $product['type'] }}', $event.target.value)"
                                        class="w-12 bg-transparent border-none text-center text-sm font-black text-slate-800 focus:ring-0">

                                    <button
                                        wire:click="amountUpdated('{{ $product['id'] }}','{{ $product['type'] }}',{{ $product['amount'] + 1 }})"
                                        class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-slate-600 hover:bg-emerald-600 hover:text-white transition-all duration-200">
                                        <span class="material-icons text-sm">add</span>
                                    </button>

                                    <div class="w-px h-6 bg-slate-200 mx-1"></div>

                                    <button
                                        wire:click="amountUpdated('{{ $product['id'] }}','{{ $product['type'] }}', 0)"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 transition-colors">
                                        <span class="material-icons text-sm">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        @endif
        {{-- Product Selected :: End --}}

    </div>
</div>

@props(['item'])

@php
    $quantity = $item['amount'] ?? ($item['pivot']['quantity'] ?? 0);
    $finalPrice = $item['final_price'] ?? ($item['pivot']['price'] ?? 0);
    $basePrice = $item['base_price'] ?? ($item['pivot']['original_price'] ?? 0);
@endphp

<div
    class="group py-5 flex flex-col sm:flex-row items-center gap-6 transition-all duration-300 hover:bg-slate-50/50 px-4 rounded-2xl">
    {{-- Thumbnail --}}
    <div class="relative flex-shrink-0">
        <div
            class="w-24 h-24 bg-white rounded-2xl overflow-hidden border border-slate-100 p-2 shadow-sm group-hover:shadow-md transition-all duration-500">
            @if ($item['thumbnail'])
                <img src="{{ ($item['type'] ?? 'Product') == 'Product' ? asset('storage/images/products/cropped100/' . $item['thumbnail']['file_name']) : asset('storage/images/collections/cropped100/' . $item['thumbnail']['file_name']) }}"
                    alt=""
                    class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
            @else
                <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-200">
                    <span class="material-icons text-4xl">inventory_2</span>
                </div>
            @endif
        </div>
        <div
            class="absolute -top-2 end-[-0.5rem] w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-xs font-black shadow-lg border-2 border-white">
            {{ $quantity }}
        </div>

        {{-- Redirect Overlay --}}
        <a href="{{ $item['type'] == 'Product' ? route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) : route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}"
            target="_blank"
            class="absolute -bottom-2 start-[-0.5rem] w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white hover:scale-110 hover:bg-slate-800 transition-all duration-200"
            title="{{ __('admin/ordersPages.View') }}">
            <span class="material-icons text-xs">open_in_new</span>
        </a>
    </div>

    {{-- Info --}}
    <div class="flex-grow min-w-0 text-center sm:text-start">
        @if (isset($item['brand']))
            <div
                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest mb-1.5">
                <span class="material-icons text-[10px]">sell</span>
                {{ is_array($item['brand']) ? $item['brand']['name'] : $item['brand'] }}
            </div>
        @endif

        <h4
            class="text-lg font-black text-slate-800 mb-1 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors duration-300">
            {{ $item['name'][session('locale')] ?? $item['name'] }}
        </h4>

        <div class="flex flex-wrap justify-center sm:justify-start items-center gap-4 text-slate-400">
            <div class="flex items-center gap-1 text-[11px] font-bold">
                <span class="material-icons text-[14px]">barcode_reader</span>
                {{ $item['barcode'] ?? 'N/A' }}
            </div>
        </div>
    </div>

    {{-- Pricing --}}
    <div
        class="flex flex-col items-center sm:items-end min-w-[160px] bg-slate-50/50 p-3 rounded-2xl border border-slate-100/50 group-hover:bg-white transition-colors">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
            {{ __('admin/ordersPages.Line Total') }}
        </div>
        <div class="text-xl font-black text-slate-900 mb-0.5" dir="ltr">
            {{ number_format($finalPrice * $quantity, 2) }}
            <small class="text-xs font-bold text-slate-500">EGP</small>
        </div>
        <div class="text-[11px] font-bold text-slate-400 mb-2">
            {{ number_format($finalPrice, 2) }} × {{ $quantity }}
        </div>

        @if ($basePrice > $finalPrice)
            <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black shadow-sm border border-emerald-100/50"
                style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);">
                <span class="material-icons text-[12px] text-emerald-500">verified</span>
                {{ __('admin/ordersPages.Saved :amount EGP', ['amount' => number_format(($basePrice - $finalPrice) * $quantity, 2)]) }}
            </div>
        @endif
    </div>
</div>

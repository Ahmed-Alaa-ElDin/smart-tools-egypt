<div class="p-3 w-full grid grid-cols-4 gap-3 items-start">
    @foreach ($items as $item)
        <div class="col-span-2 lg:col-span-1">
            <x-front.product-box-small :item="$item->toArray()" wire:key="product-{{ rand() }}" />
        </div>

        {{-- Load More :: Start --}}
        @if ($loop->last && $items->hasMorePages())
        <div x-data x-intersect="$wire.loadMore()"></div>
        <div wire:loading wire:target="loadMore" class="col-span-4 text-center">
            <x-front.loaders.load-more />
        </div>
        @endif
        {{-- Load More :: End --}}
    @endforeach
</div>
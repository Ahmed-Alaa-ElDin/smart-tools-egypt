<div class="p-3 w-full grid grid-cols-4 gap-3 items-start">
    @foreach ($items as $item)
        <div class="col-span-2 lg:col-span-1" wire:key="{{ $item['type'] }}-{{ $item['id'] }}" >
            <x-front.product-box-small :item="$item" />
        </div>
    @endforeach

    {{-- Load More :: Start --}}
    @if ($items->hasMorePages())
        <div x-data x-intersect="$wire.loadMore()" wire:key="load-more-{{ rand() }}"></div>
    @endif

    <div wire:loading wire:target="loadMore" class="col-span-4 text-center">
        <x-front.loaders.load-more />
    </div>
    {{-- Load More :: End --}}
</div>

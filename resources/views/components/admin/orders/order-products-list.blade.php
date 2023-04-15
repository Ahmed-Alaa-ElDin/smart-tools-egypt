<div>
    @forelse ($items as $item)
        {{-- Product : Start --}}
        <x-front.product-box-wide :item="$item" type="order-view" />
        {{-- Product : End --}}
    @empty
    @endforelse
</div>

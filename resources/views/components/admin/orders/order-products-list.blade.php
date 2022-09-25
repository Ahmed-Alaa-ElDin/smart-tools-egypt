<div>
    @forelse ($products as $product)
        {{-- Product : Start --}}
        <x-front.product-box-wide :product="$product" type="order-view" />
        {{-- Product : End --}}
    @empty
    @endforelse
</div>

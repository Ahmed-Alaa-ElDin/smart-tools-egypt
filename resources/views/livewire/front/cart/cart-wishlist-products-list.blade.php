<div>
    @forelse ($items as $item)
        {{-- Product : Start --}}
        <div wire:key="wishlist-row-container-{{ $item['rowId'] }}"
            class="hover:bg-gray-50/50 transition-colors rounded-2xl">
            @livewire('front.product.product-card-wide', ['item' => $item, 'type' => 'wishlist'], key('wishlist-row-' . $item['rowId']))
        </div>
        {{-- Product : End --}}

        @if (!$loop->last)
            <hr class="border-gray-50 my-2">
        @endif

    @empty
        <div class="text-center p-3">
            <h3 class="text-xl font-bold">
                {{ __('front/homePage.Wishlist is Empty') }}
            </h3>
        </div>

        <hr>

        <div class="text-center p-3">
            <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                {{ __('front/homePage.Continue Shopping') }}
            </a>
        </div>
    @endforelse
</div>

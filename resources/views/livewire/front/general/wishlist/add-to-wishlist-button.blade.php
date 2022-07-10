<button title="{{ __('front/homePage.Add to Wishlist') }}"
    class="h-9 px-2 rounded-full inline-flex items-center gap-2 bg-white border border-gray-200
    @if ($text) border border-secondary transition ease-in-out hover:bg-secondary hover:text-white @else w-9 @endif
    text-secondary shadow"
    wire:click="addToWishlist({{ $product_id }})">
    <span class="material-icons text-lg">
        favorite
    </span>
    @if ($text)
        <span class="text-xs font-bold">
            {{ __('front/homePage.Add to Wishlist') }}
        </span>
    @endif
</button>

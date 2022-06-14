<button wire:click="addToCart({{ $product_id }})" data-title="{{ __('front/homePage.Add to cart') }}"
    title="{{ __('front/homePage.Add to cart') }}" data-placement="left">
    <span
        class="material-icons text-lg p-1 rounded-full border border-light w-9 h-9 animate-pulse text-center shadow-sm bg-primary text-white hover:bg-secondary">
        shopping_cart
    </span>
</button>

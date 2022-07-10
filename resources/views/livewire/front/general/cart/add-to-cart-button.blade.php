<button wire:click.stop="addToCart({{ $product_id }})" title="{{ __('front/homePage.Add to cart') }}"
    class="stop-propagation rounded-full h-9 animate-pulse text-center shadow-sm bg-primary text-white hover:bg-secondary
    px-2 inline-flex items-center gap-2
    @if ($text) transition ease-in-out hover:animate-none hover:bg-secondary hover:text-white @else w-9 @endif
    shadow
    ">
    <span class="material-icons text-lg ">
        shopping_cart
    </span>
    @if ($text)
        <span class="text-xs font-bold">
            {{ __('front/homePage.Add to cart') }}
        </span>
    @endif
</button>

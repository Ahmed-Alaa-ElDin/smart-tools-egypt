<button wire:click.stop="addToCart({{ $product_id }})" title="{{ __('front/homePage.Add to cart') }}"
    class="stop-propagation rounded-full h-9 animate-pulse text-center shadow-sm bg-primary text-white hover:bg-secondary
    inline-flex justify-center items-center gap-2 min-w-max
    @if ($text) px-3 transition ease-in-out hover:animate-none hover:bg-secondary hover:text-white @else w-9 @endif
    shadow
    ">
    <span class="material-icons text-lg ">
        shopping_cart
    </span>
    @if ($text)
        <span class="text-xs font-bold">
            @if ($add_buy == 'add')
                {{ __('front/homePage.Add to cart') }}
            @elseif ($add_buy == 'buy')
                {{ __('front/homePage.Buy Again') }}
            @endif
        </span>
    @endif
</button>

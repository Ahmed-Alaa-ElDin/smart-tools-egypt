<button wire:click.stop="addToCart({{ $product_id }})"
    title="@if ($add_buy == 'add'){{ __('front/homePage.Add to cart') }}@elseif($add_buy == 'pay') {{ __('front/homePage.Go to payment') }}@elseif ($add_buy == 'buy'){{ __('front/homePage.Buy Again') }}@endif"
    class="stop-propagation rounded-full h-9 animate-pulse text-center shadow text-white
    inline-flex justify-center items-center gap-2 min-w-max
    @if ($add_buy == 'pay') bg-secondary hover:bg-secondaryDark @else bg-primary hover:bg-secondary @endif
    @if ($large) p-6 w-full @endif
    @if ($text) transition ease-in-out hover:animate-none hover:bg-secondary hover:text-white
    @else w-9 @endif">
    <span class="material-icons  @if ($large) text-xl @else text-lg @endif">
        @if ($add_buy == 'pay')
            local_shipping
        @else
            shopping_cart
        @endif
    </span>
    @if ($text)
        <span class="font-bold @if (!$large) text-xs @endif">
            @if ($add_buy == 'add')
                {{ __('front/homePage.Add to cart') }}
            @elseif ($add_buy == 'pay')
                {{ __('front/homePage.Go to payment') }}
            @elseif ($add_buy == 'buy')
                {{ __('front/homePage.Buy Again') }}
            @endif
        </span>
    @endif
</button>

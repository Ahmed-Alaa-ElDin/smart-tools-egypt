<button wire:click="addToCompare({{ $product_id }})" title="{{ __('front/homePage.Add to the comparison') }}"
    class="stop-propagation rounded-full h-9 text-center bg-white text-gray-900 hover:bg-secondary
    inline-flex justify-center items-center gap-2 min-w-max shadow border border-secondary
    @if ($large) p-4 w-min @endif
    @if ($text) hover:bg-secondary hover:text-white @else w-9 @endif">
    <span class="material-icons text-lg">
        compare_arrows
    </span>
    @if ($text)
        <span class=" font-bold text-xs">
            {{ __('front/homePage.Add to the comparison') }}
        </span>
    @endif
</button>

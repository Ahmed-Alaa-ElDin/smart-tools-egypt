<button title="{{ __('front/homePage.Remove From Comparison') }}" wire:click="removeFromComparison({{ $item_id }},'{{ $type }}')"
class="stop-propagation rounded-full h-9 text-center bg-white text-gray-900 hover:bg-secondary
inline-flex justify-center items-center gap-2 min-w-max shadow border border-secondary hover:text-white
@if ($large) p-4 w-min @endif
@if ($text) hover:bg-secondary px-3  @else w-9 @endif">
<span class="material-icons text-lg">
    delete
    </span>
    @if ($text)
        <span class=" font-bold text-xs">
            {{ __('front/homePage.Remove From Comparison') }}
        </span>
    @endif
</button>

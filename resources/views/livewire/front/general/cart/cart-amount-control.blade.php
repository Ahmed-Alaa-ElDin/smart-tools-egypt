<div>
    @if ($cartAmount)
        <div class="@if (!$small) md:p-3 @endif p-2">
            {{-- Title :: Start --}}
            @if ($title)
                <h3 class="text-sm font-bold mb-3 text-center text-gray-800">
                    {{ __('front/homePage.Amount of Product in Cart') }}
                </h3>
            @endif
            {{-- Title :: End --}}

            <div
                class="flex @if ($small) gap-2 px-2 py-1 @else gap-4 px-3 py-2 @endif items-center bg-gray-50 rounded-2xl w-fit mx-auto">
                {{-- Decrease :: Start --}}
                <button
                    class="@if ($small) w-6 h-6 @else w-8 h-8 @endif flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400 hover:text-red-600 transition-colors disabled:opacity-50"
                    title="{{ __('front/homePage.Decrease') }}"
                    wire:click="removeOneFromCart('{{ $cartItem->rowId }}',{{ $cartAmount - 1 }})"
                    @if ($cartAmount <= 1) disabled @endif>
                    <span
                        class="material-icons @if ($small) text-sm @else text-lg @endif">remove</span>
                </button>
                {{-- Decrease :: End --}}

                {{-- Amount :: Start --}}
                <div class="relative @if ($small) w-6 @else w-8 @endif">
                    <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        class="bg-transparent border-none p-0 text-center font-bold text-gray-800 focus:ring-0 @if ($small) text-xs @else text-sm @endif w-full"
                        value="{{ $cartAmount }}"
                        wire:change="cartUpdated('{{ $cartItem->rowId }}',$event.target.value)">
                </div>
                {{-- Amount :: End --}}

                {{-- Increase :: Start --}}
                <button
                    class="@if ($small) w-6 h-6 @else w-8 h-8 @endif flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400 hover:text-successDark transition-colors"
                    title="{{ __('front/homePage.Increase') }}"
                    wire:click="addOneToCart('{{ $cartItem->rowId }}',{{ $cartAmount + 1 }})">
                    <span
                        class="material-icons @if ($small) text-sm @else text-lg @endif">add</span>
                </button>
                {{-- Increase :: End --}}
            </div>
        </div>
    @else
        {{-- Title :: Start --}}
        @if ($title)
            <h3 class="text-sm font-bold mb-3 text-center text-gray-800">
                {{ __('front/homePage.Amount of Product in Cart') }}
            </h3>
        @endif
        {{-- Title :: End --}}

        <div
            class="flex @if ($small) gap-2 px-2 py-1 @else gap-4 px-3 py-2 @endif items-center bg-gray-50 rounded-2xl w-fit mx-auto">
            {{-- Placeholder/Disabled Decrease --}}
            <button
                class="@if ($small) w-6 h-6 @else w-8 h-8 @endif flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-200 cursor-not-allowed"
                disabled>
                <span
                    class="material-icons @if ($small) text-sm @else text-lg @endif">remove</span>
            </button>

            {{-- Initial Add --}}
            <button
                class="flex-grow @if ($small) px-2 py-1 text-xs @else px-4 py-2 text-sm @endif font-bold text-primary hover:text-primary-dark transition-colors"
                wire:click="addToCart('{{ $item_id }}')">
                {{ __('front/homePage.Add to cart') }}
            </button>

            {{-- Placeholder/Disabled Increase --}}
            <button
                class="@if ($small) w-6 h-6 @else w-8 h-8 @endif flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-200 cursor-not-allowed"
                disabled>
                <span class="material-icons @if ($small) text-sm @else text-lg @endif">add</span>
            </button>
        </div>
    @endif
</div>

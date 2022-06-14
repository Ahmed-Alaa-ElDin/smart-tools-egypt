<div>
    @if ($cartAmount)
        <div class="flex justify-center items-center gap-3 w-full">
            {{-- Add :: Start --}}
            <button class="w-8 h-8 rounded-circle bg-secondary text-white" title="{{ __('front/homePage.Increase') }}"
                wire:click="addOneToCart('{{ $cartProduct->rowId }}',{{ $cartAmount + 1 }})">
                <span class="material-icons text-lg">
                    add
                </span>
            </button>
            {{-- Add :: End --}}

            {{-- Amount :: Start --}}
            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                class="focus:ring-primary focus:border-primary flex-1 block w-full rounded text-sm border-gray-300 text-center text-gray-700 px-1 p-2"
                value="{{ $cartAmount }}"
                wire:change="cartUpdated('{{ $cartProduct->rowId }}',$event.target.value)">
            {{-- Amount :: End --}}

            {{-- Remove :: Start --}}
            <button class="w-8 h-8 rounded-circle bg-secondary text-white" title="{{ __('front/homePage.Decrease') }}"
                wire:click="removeOneFromCart('{{ $cartProduct->rowId }}',{{ $cartAmount - 1 }})">
                <span class="material-icons text-lg">
                    remove
                </span>
            </button>
            {{-- Remove :: End --}}

            {{-- Delete :: Start --}}
            <button title="{{ __('front/homePage.Remove from Cart') }}"
                class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white"
                wire:click="removeFromCart('{{ $cartProduct->rowId }}','{{ $product_id }}')">
                <span class="material-icons text-lg">
                    delete
                </span>
            </button>
            {{-- Delete :: End --}}
        </div>
    @endif
</div>

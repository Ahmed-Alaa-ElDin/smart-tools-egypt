<div>
    @if ($cartAmount)
        <div class="md:p-3 p-2">
            {{-- Title :: Start --}}
            @if ($title)
                <h3 class="text-md font-bold mb-2 text-center">
                    {{ __('front/homePage.Amount of Product in Cart') }}
                </h3>
            @endif
            {{-- Title :: End --}}

            @if ($small)
                <div class="flex justify-center items-center gap-1 w-full">
                    {{-- Add :: Start --}}
                    <button class="w-6 h-6 rounded-circle bg-secondary text-white"
                        title="{{ __('front/homePage.Increase') }}"
                        wire:click="addOneToCart('{{ $cartItem->rowId }}',{{ $cartAmount + 1 }})">
                        <span class="material-icons text-xs">
                            add
                        </span>
                    </button>
                    {{-- Add :: End --}}

                    {{-- Amount :: Start --}}
                    <input type="text"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                        class="focus:ring-primary focus:border-primary flex-1 block w-full rounded text-xs border-gray-300 text-center text-gray-700 px-1 p-2"
                        value="{{ $cartAmount }}"
                        wire:change="cartUpdated('{{ $cartItem->rowId }}',$event.target.value)">
                    {{-- Amount :: End --}}

                    {{-- Remove :: Start --}}
                    <button class="w-6 h-6 rounded-circle bg-secondary text-white"
                        wire:key="DecreaseByOne-{{ rand() }}" title="{{ __('front/homePage.Decrease') }}"
                        wire:click="removeOneFromCart('{{ $cartItem->rowId }}',{{ $cartAmount - 1 }})">
                        <span class="material-icons text-xs">
                            remove
                        </span>
                    </button>
                    {{-- Remove :: End --}}

                    {{-- Delete :: Start --}}
                    {{-- @if ($remove)
                        <button title="{{ __('front/homePage.Remove from Cart') }}"
                            class="w-6 h-6 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white"
                            wire:click="removeFromCart('{{ $cartItem->rowId }}','{{ $item_id }}')">
                            <span class="material-icons text-xs">
                                delete
                            </span>
                        </button>
                    @endif --}}
                    {{-- Delete :: End --}}
                </div>
            @else
                <div class="flex justify-center items-center gap-3 w-full">
                    {{-- Add :: Start --}}
                    <button class="w-8 h-8 rounded-circle bg-secondary text-white"
                        title="{{ __('front/homePage.Increase') }}"
                        wire:click="addOneToCart('{{ $cartItem->rowId }}',{{ $cartAmount + 1 }})">
                        <span class="material-icons text-lg">
                            add
                        </span>
                    </button>
                    {{-- Add :: End --}}

                    {{-- Amount :: Start --}}
                    <input type="text"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                        class="focus:ring-primary focus:border-primary flex-1 block w-full rounded text-sm border-gray-300 text-center text-gray-700 px-1 p-2"
                        value="{{ $cartAmount }}"
                        wire:change="cartUpdated('{{ $cartItem->rowId }}',$event.target.value)">
                    {{-- Amount :: End --}}

                    {{-- Remove :: Start --}}
                    <button class="w-8 h-8 rounded-circle bg-secondary text-white"
                        wire:key="DecreaseByOne-{{ rand() }}" title="{{ __('front/homePage.Decrease') }}"
                        wire:click="removeOneFromCart('{{ $cartItem->rowId }}',{{ $cartAmount - 1 }})">
                        <span class="material-icons text-lg">
                            remove
                        </span>
                    </button>
                    {{-- Remove :: End --}}

                    {{-- Delete :: Start --}}
                    {{-- @if ($remove)
                        <button title="{{ __('front/homePage.Remove from Cart') }}"
                            class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white"
                            wire:click="removeFromCart('{{ $cartItem->rowId }}','{{ $item_id }}')">
                            <span class="material-icons text-lg">
                                delete
                            </span>
                        </button>
                    @endif --}}
                    {{-- Delete :: End --}}
                </div>
            @endif
        </div>
    @else
        {{-- Title :: Start --}}
        @if ($title)
            <h3 class="text-md font-bold mb-2 text-center">
                {{ __('front/homePage.Amount of Product in Cart') }}
            </h3>
        @endif
        {{-- Title :: End --}}

        @if ($small)
            <div class="hidden">
                {{-- Add :: Start --}}
                <button class="w-6 h-6 rounded-circle bg-secondary text-white"
                    title="{{ __('front/homePage.Increase') }}" wire:click="addToCart('{{ $item_id }}')">
                    <span class="material-icons text-xs">
                        add
                    </span>
                </button>
                {{-- Add :: End --}}

                {{-- Amount :: Start --}}
                <input type="text"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                    class="focus:ring-primary focus:border-primary flex-1 block w-full rounded text-xs border-gray-300 text-center text-gray-700 px-1 p-2"
                    value="0" wire:change="addToCart('{{ $item_id }}',$event.target.value)">
                {{-- Amount :: End --}}

                {{-- Remove :: Start --}}
                <button class="w-6 h-6 rounded-circle bg-secondary text-white"
                    title="{{ __('front/homePage.Decrease') }}" wire:key="disabled-{{ rand() }}" disabled>
                    <span class="material-icons text-xs">
                        remove
                    </span>
                </button>
                {{-- Remove :: End --}}
            </div>
        @else
            <div class="hidden">
                {{-- Add :: Start --}}
                <button class="w-8 h-8 rounded-circle bg-secondary text-white"
                    title="{{ __('front/homePage.Increase') }}" wire:click="addToCart('{{ $item_id }}')">
                    <span class="material-icons text-lg">
                        add
                    </span>
                </button>
                {{-- Add :: End --}}

                {{-- Amount :: Start --}}
                <input type="text"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"
                    class="focus:ring-primary focus:border-primary flex-1 block w-full rounded text-sm border-gray-300 text-center text-gray-700 px-1 p-2"
                    value="{{ $cartAmount }}" wire:change="addToCart('{{ $item_id }}',$event.target.value)">
                {{-- Amount :: End --}}

                {{-- Remove :: Start --}}
                <button class="w-8 h-8 rounded-circle bg-secondary text-white" wire:key="disabled-{{ rand() }}"
                    title="{{ __('front/homePage.Decrease') }}" disabled>
                    <span class="material-icons text-lg">
                        remove
                    </span>
                </button>
                {{-- Remove :: End --}}
            </div>
        @endif
    @endif
</div>

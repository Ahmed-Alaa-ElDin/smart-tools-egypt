{{-- Product : Start --}}
<div class="p-4">
    <div class="flex gap-5 justify-between items-center">
        {{-- Thumnail :: Start --}}
        <div>
            @if ($product['thumbnail'])
                <img class="w-full h-full flex justify-center items-center bg-gray-200"
                    src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
                    alt="{{ $product['name'][session('locale')] . 'image' }}">
            @else
                <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                    <span class="block material-icons text-8xl">
                        construction
                    </span>
                </div>
            @endif
        </div>
        {{-- Thumnail :: End --}}

        {{-- Product Info : Start --}}
        <div class="grow flex flex-col justify-start gap-2">
            {{-- Product's Brand :: Start --}}
            <div class="flex items-center">
                <span class="text-sm font-bold text-gray-400">
                    {{ $product['brand']['name'] }}
                </span>
            </div>
            {{-- Product's Brand :: End --}}

            {{-- Product Name : Start --}}
            <div class="flex items-center">
                {{-- todo : Small Screen --}}
                <span class="text-lg font-bold truncate">
                    {{ $product['name'][session('locale')] }}
                </span>
            </div>
            {{-- Product Name : End --}}

            {{-- Reviews : Start --}}
            {{-- todo --}}
            <div class="text-center flex justify-start items-center gap-2">
                <div class="rating flex">
                    <span class="material-icons text-yellow-500 text-sm">
                        star
                    </span>

                    <span class="material-icons text-yellow-500 text-sm">
                        star
                    </span>

                    <span class="material-icons text-yellow-500 text-sm">
                        star
                    </span>

                    <span class="material-icons text-yellow-500 text-sm">
                        star_border
                    </span>

                    <span class="material-icons text-yellow-500 text-sm">
                        star_border
                    </span>
                </div>

                <span class="text-xs text-gray-600">(100)</span>
            </div>
            {{-- Reviews : End --}}

            @if ($type == 'cart')
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    {{-- Add to the wishlist :: Start --}}
                    @livewire('front.general.wishlist.add-to-wishlist-button', ['product_id' => $product['id'], 'text' => true, 'remove' => true], key('add-wishlist-button-' . Str::random(10)))
                    {{-- Add to the wishlist :: End --}}

                    {{-- Remove from the cart :: Start --}}
                    <button title="{{ __('front/homePage.Remove from Cart') }}"
                        class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white shadow-sm"
                        wire:click="removeFromCart({{ $product['id'] }})">
                        <span class="material-icons text-lg">
                            delete
                        </span>
                    </button>
                    {{-- Remove from the cart :: End --}}
                </div>
            @elseif ($type == 'wishlist')
                <div class="mt-2 flex flex-wrap items-center gap-3">
                    {{-- Add To Cart :: Start --}}
                    <button title="{{ __('front/homePage.Add to cart') }}"
                        class="h-8 p-2 flex justify-between items-center gap-2 rounded-full bg-secondary border border-secondary text-white transition ease-in-out hover:bg-primary hover:text-white hover:animate-none	hover:border-primary animate-pulse text-center shadow-sm"
                        wire:click="moveToCart({{ $product['id'] }})"
                        >
                        <span class="material-icons text-lg rounded-circle">
                            shopping_cart
                        </span>
                        <span class="text-xs font-bold">{{ __('front/homePage.Add to cart') }}</span>
                    </button>
                    {{-- Add To Cart :: End --}}

                    {{-- Remove from the wishlist :: Start --}}
                    <button title="{{ __('front/homePage.Remove from Wishlist') }}"
                        class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white shadow-sm"
                        wire:click="removeFromWishlist({{ $product['id'] }})">
                        <span class="material-icons text-lg">
                            delete
                        </span>
                    </button>
                    {{-- Remove from the wishlist :: End --}}
                </div>
            @endif

        </div>
        {{-- Product Info : End --}}

        {{-- Product Price : Start --}}
        <div class="flex flex-col justify-center items-center gap-2">
            @if ($product['under_reviewing'])
                <span class="text-yellow-600 font-bold text-sm">
                    {{ __('front/homePage.Under Reviewing') }}
                </span>
            @else
                <div class="flex flex-col md:flex-row-reverse items-center gap-3">
                    {{-- Base Price : Start --}}
                    <del class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400">
                        <span class="text-xs">
                            {{ __('front/homePage.EGP') }}
                        </span>
                        <span class="font-bold text-2xl">{{ explode('.', $product['base_price'])[0] }}</span>
                    </del>
                    {{-- Base Price : End --}}

                    {{-- Final Price : Start --}}
                    <div class="flex rtl:flex-row-reverse gap-1">
                        <span class="font-bold text-primary text-xs">{{ __('front/homePage.EGP') }}</span>
                        <span
                            class="font-bold text-primary text-lg">{{ explode('.', $product['final_price'])[0] }}</span>
                        <span
                            class="text-primary text-xs">{{ explode('.', $product['final_price'])[1] ?? '00' }}</span>
                    </div>
                    {{-- Final Price : End --}}
                </div>
            @endif

            {{-- Free Shipping :: Start --}}
            @if ($product['free_shipping'])
                <span class="text-xs font-bold text-green-600 text-right rtl:text-left w-full">
                    {{ __('front/homePage.Free Shipping') }}
                </span>
            @endif
            {{-- Free Shipping :: End --}}

            @if ($type == 'cart')
                {{-- Product Amount :: Start --}}
                <div class="max-w-[150px] my-2">
                    @livewire('front.general.cart.cart-amount', ['product_id' => $product['id'], 'unique' => 'product-' . $product['id'], 'remove' => false], key($product['name'][session('locale')] . '-' . rand()))
                </div>
                {{-- Product Amount :: End --}}
            @endif

        </div>
        {{-- Product Price : End --}}
    </div>
</div>
{{-- Product : End --}}

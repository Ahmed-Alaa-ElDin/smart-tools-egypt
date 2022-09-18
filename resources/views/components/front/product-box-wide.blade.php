{{-- Product : Start --}}
<div class="p-4 scrollbar scrollbar-thin">
    <div class="flex gap-6 justify-start items-center">
        {{-- Thumnail :: Start --}}
        <a href="{{ route('front.product.show', ['id' => $product['id'], 'slug' => $product->slug]) }}"
            class="min-w-max block hover:text-current">
            @if ($product['thumbnail'])
                <img class="w-full h-full flex justify-center items-center bg-gray-200"
                    src="{{ asset('storage/images/products/cropped100/' . $product['thumbnail']['file_name']) }}"
                    alt="{{ $product['name'] . 'image' }}">
            @else
                <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                    <span class="block material-icons text-8xl">
                        construction
                    </span>
                </div>
            @endif
        </a>
        {{-- Thumnail :: End --}}

        <div class="flex gap-6 justify-between items-center w-full max-w-100">
            {{-- Product Info : Start --}}
            <div class="grow flex flex-col justify-start gap-2">
                {{-- Product's Brand :: Start --}}
                {{-- todo :: brand link --}}
                <div class="flex items-center">
                    <a href="#" class="text-sm font-bold text-gray-400 hover:text-current">
                        {{ $product['brand'] ? $product['brand']['name'] : '' }}
                    </a>
                </div>
                {{-- Product's Brand :: End --}}

                {{-- Product Name : Start --}}
                <div class="flex items-center">
                    <a href="{{ route('front.product.show', ['id' => $product['id'], 'slug' => $product->slug]) }}"
                        class="text-lg font-bold hover:text-current">
                        {{ $product->name }}
                    </a>
                </div>
                {{-- Product Name : End --}}

                {{-- Reviews : Start --}}
                <div class="my-1 flex justify-start items-center gap-2 select-none">
                    <div class="rating flex">
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                class="material-icons inline-block @if ($i <= ceil($product['avg_rating'])) text-yellow-300 @else text-gray-400 @endif">
                                star
                            </span>
                        @endfor
                    </div>

                    <span class="text-sm text-gray-600">({{ $product['reviews_count'] ?? 0 }})</span>
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
                            class="h-8 py-2 px-3 flex justify-between items-center gap-2 rounded-full bg-secondary border border-secondary text-white transition ease-in-out hover:bg-primary hover:text-white hover:animate-none	hover:border-primary animate-pulse text-center shadow-sm"
                            wire:click="moveToCart({{ $product['id'] }})">
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
            <div class="flex flex-col items-end justify-center gap-2">
                @if ($product['under_reviewing'])
                    <span class="text-yellow-600 font-bold text-sm">
                        {{ __('front/homePage.Under Reviewing') }}
                    </span>
                @else
                    <div class="flex flex-col md:flex-row-reverse gap-3">
                        {{-- Base Price : Start --}}
                        <del class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400">
                            <span class="text-xs">
                                {{ __('front/homePage.EGP') }}
                            </span>
                            <span class="font-bold text-2xl"
                                dir="ltr">{{ number_format(explode('.', $product['base_price'])[0], 0, '.', '\'') }}</span>
                        </del>
                        {{-- Base Price : End --}}

                        {{-- Final Price : Start --}}
                        <div class="flex rtl:flex-row-reverse gap-1">
                            <span class="font-bold text-primary text-xs">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-primary text-lg"
                                dir="ltr">{{ number_format(explode('.', $product['final_price'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="text-primary text-xs">{{ explode('.', $product['final_price'])[1] ?? '00' }}</span>
                        </div>
                        {{-- Final Price : End --}}
                    </div>
                @endif

                {{-- Free Shipping :: Start --}}
                @if ($product['free_shipping'])
                    <span class="text-xs font-bold text-success text-center w-full">
                        {{ __('front/homePage.Free Shipping') }}
                    </span>
                @endif
                {{-- Free Shipping :: End --}}

                @if ($type == 'cart')
                    {{-- Product Amount :: Start --}}
                    <div class="min-w-[120px] max-w-[150px] md:min-w-0 my-2">
                        @livewire('front.general.cart.cart-amount', ['product_id' => $product['id'], 'unique' => 'product-' . $product['id'], 'remove' => false], key($product['name'] . '-' . rand()))
                    </div>
                    {{-- Product Amount :: End --}}
                @elseif ($type === 'order-view')
                    <div class="flex justify-center items-center bg-primary rounded-lg shadow overflow-hidden px-2 py-1">
                        <div class="text-white p-2 text-xs   font-bold">
                            {{ __('front/homePage.Quantity') }}
                        </div>
                        <div class="bg-white text-gray-900 p-1 rounded-circle w-7 h-7 font-bold text-center">
                            {{ $product->pivot->quantity }}
                        </div>
                    </div>
                @endif

            </div>
            {{-- Product Price : End --}}
        </div>
    </div>
</div>
{{-- Product : End --}}

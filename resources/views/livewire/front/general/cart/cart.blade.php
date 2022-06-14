<div class="hidden lg:block align-self-stretch ml-3 mr-0" data-hover="dropdown">
    <div class="nav-cart-box dropdown h-100" id="cart_items">
        <a href="javascript:void(0)" class="flex items-center gap-2 text-reset h-100" data-toggle="dropdown"
            data-display="static">
            <span class="material-icons">
                shopping_cart
            </span>
            <span class="grow ml-1 text-center">
                <span
                    class="badge bg-red-600 mb-1 text-white badge-inline badge-pill cart-count">{{ Cart::instance('cart')->count() }}</span>
                <span class="nav-box-text text-xs hidden xl:block opacity-70">
                    {{ __('front/homePage.Cart') }}
                </span>
            </span>
        </a>

        <div class="dropdown-menu p-0 stop-propagation  z-50 min-w-max overflow-hidden">

            <div
                class="text-center p-2 overflow-y-auto overflow-x-hidden scrollbar scrollbar-thin scrollbar-thumb-red-200 max-h-[50vh]">
                {{-- Cart Items :: Start --}}
                <ul>
                    @forelse ($cart as $cart_item)
                        <li>
                            <div class="flex flex-nowrap gap-4 justify-between items-center px-3">

                                {{-- Thumbnail :: Start --}}
                                @if ($cart_item->options->thumbnail)
                                    <img src="{{ asset('storage/images/products/cropped100/' . $cart_item->options->thumbnail->file_name) }}"
                                        class="w-14 h-14 rounded" alt="{{ $cart_item->name[session('locale')] }}">
                                @else
                                    <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                                        class="w-14 h-14 rounded" alt="{{ $cart_item->name[session('locale')] }}">
                                @endif
                                {{-- Thumbnail :: End --}}

                                <div class="flex flex-col">

                                    {{-- Product Name :: Start --}}
                                    <h3 class="h5 m-0 font-bold truncate">{{ $cart_item->name[session('locale')] }}
                                    </h3>
                                    {{-- Product Name :: End --}}

                                    {{-- Product Amount & Price :: Start --}}
                                    <div class="flex flex-nowrap" dir="ltr">
                                        <span class="font-bold">
                                            {{ $cart_item->qty }}
                                        </span>
                                        &nbsp; x &nbsp;
                                        <div class="flex gap-1" dir="ltr">
                                            <span
                                                class="font-bold text-green-700">{{ explode('.', $cart_item->price)[0] }}</span>
                                            <span
                                                class="font-bold text-green-700 text-xs">{{ explode('.', $cart_item->price)[1] ?? 00 }}</span>
                                        </div>
                                    </div>
                                    {{-- Product Amount & Price :: End --}}
                                </div>

                                {{-- Buttons :: Start --}}
                                <div class="flex gap-2">
                                    {{-- Add To Wishlist :: Start --}}
                                    <button title="{{ __('front/homePage.Add to Wishlist') }}"
                                        class="w-8 h-8 rounded-circle bg-white border border-secondary text-secondary transition ease-in-out hover:bg-secondary hover:text-white"
                                        wire:click="moveToWishlist('{{ $cart_item->rowId }}')">
                                        <span class="material-icons text-lg">
                                            favorite_border
                                        </span>
                                    </button>
                                    {{-- Add To Wishlist :: End --}}

                                    {{-- Delete :: Start --}}
                                    <button title="{{ __('front/homePage.Remove from Cart') }}"
                                        class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white"
                                        wire:click="removeFromCart('{{ $cart_item->rowId }}','{{ $cart_item->id }}')">
                                        <span class="material-icons text-lg">
                                            delete
                                        </span>
                                    </button>
                                    {{-- Delete :: End --}}
                                </div>
                                {{-- Buttons :: End --}}
                            </div>
                        </li>
                        @if (!$loop->last)
                            <hr class="my-2">
                        @endif
                    @empty
                        <li>
                            <h3 class="h5 m-0 font-bold px-3">{{ __('front/homePage.Shopping Cart is Empty') }}</h3>
                        </li>
                    @endforelse
                </ul>
                {{-- Cart Items :: End --}}
            </div>

            @if ($cart->count() > 0)
                {{-- Cart Subtotal :: Start --}}
                <div class="flex justify-center items-center gap-3 p-3 bg-gray-100">
                    <div class="font-bold">
                        {{ __('front/homePage.Subtotal :') }}
                    </div>
                    <div class="flex gap-1" dir="ltr">
                        <span
                            class="font-bold text-green-700">{{ __('front/homePage.EGP') }}</span>
                        <span
                            class="font-bold text-green-700">{{ explode('.', Cart::instance('cart')->subtotal())[0] }}</span>
                        <span
                            class="font-bold text-green-700 text-xs">{{ explode('.', Cart::instance('cart')->subtotal())[1] }}</span>
                    </div>
                </div>
                {{-- Cart Subtotal :: End --}}

                {{-- Cart Buttons :: Start --}}
                <div class="flex flex-col justify-center items-center gap-1 m-1 px-2">
                    {{-- Checkout :: Start --}}
                    <a href="#" class="block w-full btn bg-secondary text-white font-bold">
                        <span class="material-icons">
                            local_shipping
                        </span>
                        &nbsp;
                        {{ __('front/homePage.Go To Checkout') }}
                    </a>
                    {{-- Checkout :: End --}}

                    <div class="flex justify-center items-center gap-3 w-full">
                        {{-- View & Edit Cart :: Start --}}
                        <a href="#" class="grow btn bg-primary btn-sm text-white font-bold">
                            <span class="material-icons">
                                edit
                            </span>
                            &nbsp;
                            {{ __('front/homePage.View & Edit Cart') }}
                        </a>
                        {{-- View & Edit Cart :: End --}}

                        {{-- Clear Cart :: Start --}}
                        <button wire:click="clearCart"
                            class="btn bg-white border border-primary btn-sm text-primary font-bold rounded-full"
                            title="__('front/homePage.Clear Cart') }}">
                            <span class="material-icons">
                                delete
                            </span>
                        </button>
                        {{-- Clear Cart :: End --}}
                    </div>
                </div>
                {{-- Cart Buttons :: End --}}
            @endif

        </div>
    </div>
</div>

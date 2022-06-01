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

            <div class="text-center p-2 overflow-auto scrollbar scrollbar-thin scrollbar-thumb-red-200 max-h-[50vh]">
                {{-- Cart Items :: Start --}}
                <ul>
                    @forelse ($cart as $cart_item)
                        <li>
                            <div class="flex flex-nowrap gap-2 items-center px-3">

                                @if ($cart_item->options->thumbnail)
                                    <img src="{{ asset('storage/images/products/cropped100/' . $cart_item->options->thumbnail->file_name) }}"
                                        class="w-14 h-14 rounded" alt="{{ $cart_item->name[session('locale')] }}">
                                @else
                                    <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                                        class="w-14 h-14 rounded" alt="{{ $cart_item->name[session('locale')] }}">
                                @endif

                                <div class="flex flex-col">
                                    <h3 class="h5 m-0 font-bold">{{ $cart_item->name[session('locale')] }}</h3>

                                    <div class="flex flex-nowrap" dir="ltr">
                                        <span class="font-bold">
                                            {{ $cart_item->qty }}
                                        </span>
                                        &nbsp; x &nbsp;
                                        <div class="flex gap-1" dir="ltr">
                                            <span
                                                class="font-bold text-green-700">{{ explode('.', $cart_item->price)[0] }}</span>
                                            <span
                                                class="font-bold text-green-700 text-xs">{{ explode('.', $cart_item->price)[1] }}</span>
                                        </div>
                                    </div>
                                </div>
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
                <div class="flex justify-around items-center p-3 bg-gray-100">
                    <div class="font-bold">
                        {{ __('front/homePage.Subtotal :') }}
                    </div>
                    <div class="flex gap-1" dir="ltr">
                        <span
                            class="font-bold text-green-700">{{ explode('.', Cart::instance('cart')->subtotal())[0] }}</span>
                        <span
                            class="font-bold text-green-700 text-xs">{{ explode('.', Cart::instance('cart')->subtotal())[1] }}</span>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

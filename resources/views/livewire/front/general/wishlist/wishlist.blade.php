<div class="hidden lg:block align-self-stretch ml-3 mr-0" data-hover="dropdown">
    <div class="nav-cart-box dropdown h-100" id="wishlist">
        <a href="javascript:void(0)" class="flex items-center gap-2 text-reset h-100" data-toggle="dropdown"
            data-display="static">
            <span class="material-icons">
                favorite
            </span>
            <span class="grow ml-1 text-center">
                <span
                    class="badge bg-red-600 mb-1 text-white badge-inline badge-pill">{{ Cart::instance('wishlist')->count() }}</span>
                <span class="nav-box-text text-xs hidden xl:block opacity-70">
                    {{ __('front/homePage.Wishlist') }}
                </span>
            </span>
        </a>

        <div class="dropdown-menu p-0 stop-propagation  z-50 min-w-max overflow-hidden">

            <div
                class="text-center p-2 overflow-y-auto overflow-x-hidden scrollbar scrollbar-thin scrollbar-thumb-red-200 max-h-[50vh]">
                {{-- WishlistItems :: Start --}}
                <ul>
                    @forelse ($wishlist as $wishlist_item)
                        <li>
                            <div class="flex flex-nowrap gap-4 justify-between items-center px-3">

                                {{-- Thumbnail :: Start --}}
                                @if ($wishlist_item->options->thumbnail)
                                    <img src="{{ asset('storage/images/products/cropped100/' . $wishlist_item->options->thumbnail->file_name) }}"
                                        class="w-14 h-14 rounded" alt="{{ $wishlist_item->name[session('locale')] }}">
                                @else
                                    <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}"
                                        class="w-14 h-14 rounded" alt="{{ $wishlist_item->name[session('locale')] }}">
                                @endif
                                {{-- Thumbnail :: End --}}

                                <div class="flex flex-col">

                                    {{-- Product Name :: Start --}}
                                    <h3 class="h5 m-0 font-bold truncate">
                                        {{ $wishlist_item->name[session('locale')] }}
                                    </h3>
                                    {{-- Product Name :: End --}}

                                    {{-- Product Amount & Price :: Start --}}
                                    <div class="flex flex-nowrap" dir="ltr">
                                        <div class="flex gap-1" dir="ltr">
                                            <span
                                                class="font-bold text-green-700">{{ number_format(explode('.', $wishlist_item->price)[0],0,'.','\'') }}</span>
                                            <span
                                                class="font-bold text-green-700 text-xs">{{ explode('.', $wishlist_item->price)[1] ?? "00" }}</span>
                                        </div>
                                    </div>
                                    {{-- Product Amount & Price :: End --}}
                                </div>

                                {{-- Buttons :: Start --}}
                                <div class="flex gap-2">
                                    {{-- Add To Cart :: Start --}}
                                    <button title="{{ __('front/homePage.Add to cart') }}"
                                        class="w-8 h-8 rounded-circle bg-secondary border border-secondary text-white transition ease-in-out hover:bg-primary hover:text-white  animate-pulse text-center shadow-sm"
                                        wire:click="moveToCart('{{ $wishlist_item->rowId }}')">
                                        <span class="material-icons text-lg rounded-circle">
                                            shopping_cart
                                        </span>
                                    </button>
                                    {{-- Add To Cart :: End --}}

                                    {{-- Delete :: Start --}}
                                    <button title="{{ __('front/homePage.Remove from Wishlist') }}"
                                        class="w-8 h-8 rounded-circle bg-white border border-primary text-primary transition ease-in-out hover:bg-primary hover:text-white"
                                        wire:click="removeFromWishlist('{{ $wishlist_item->rowId }}')">
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
                            <h3 class="h5 m-0 font-bold px-3">{{ __('front/homePage.Wishlist is Empty') }}</h3>
                        </li>
                    @endforelse
                </ul>
                {{-- WishlistItems :: End --}}
            </div>

            @if ($wishlist_count > 0)
                {{-- WishlistButtons :: Start --}}
                <div class="flex flex-col justify-center items-center gap-1 m-1 px-2">
                    <div class="flex justify-center items-center gap-3 w-full">
                        {{-- View & Edit Wishlist:: Start --}}
                        <a href="#" class="grow btn bg-primary btn-sm text-white font-bold">
                            <span class="material-icons">
                                visibility
                            </span>
                            &nbsp;
                            {{ __('front/homePage.View Wishlist') }}
                        </a>
                        {{-- View & Edit Wishlist:: End --}}

                        {{-- Clear Wishlist:: Start --}}
                        <button wire:click="clearWishlist"
                            class="btn bg-white border border-primary btn-sm text-primary font-bold rounded-full"
                            title="{{ __('front/homePage.Clear Wishlist') }}">
                            <span class="material-icons">
                                delete
                            </span>
                        </button>
                        {{-- Clear Wishlist:: End --}}
                    </div>
                </div>
                {{-- WishlistButtons :: End --}}
            @endif

        </div>
    </div>
</div>

{{-- Product : Start --}}
<li class="splide__slide">
    <div class="carousel-box w-full inline-block">
        <div
            class="group shadow border border-light rounded-lg hover:shadow-md hover:scale-105 mt-1 mb-2 transition overflow-hidden relative">

            {{-- Add Product Large Screen : Start --}}
            <div
                class="hidden lg:flex absolute top-2 ltr:-right-10 z-10 rtl:-left-10 transition-all ease-in-out duration-500 ltr:group-hover:right-2 rtl:group-hover:left-2 flex-col gap-1">
                {{-- Add to compare : Start --}}
                @livewire('front.general.compare.add-to-compare-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-compare-button-' . Str::random(10)))
                {{-- Add to compare : End --}}


                @if ($wishlist)
                    {{-- Remove from wishlist : Start --}}
                    @livewire('front.general.wishlist.remove-from-wishlist-button', ['item_id' => $item['id'], 'type' => $item['type']], key('remove-wishlist-button-' . Str::random(10)))
                    {{-- Remove from wishlist : End --}}
                @else
                    {{-- Add to wishlist : Start --}}
                    @livewire('front.general.wishlist.add-to-wishlist-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-wishlist-button-' . Str::random(10)))
                    {{-- Add to wishlist : End --}}
                @endif

                @if (isset($item['quantity']) && $item['quantity'] > 0)
                    {{-- Add to cart : Start --}}
                    @livewire('front.general.cart.add-to-cart-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-cart-button-' . Str::random(10)))
                    {{-- Add to cart : End --}}
                @endif
            </div>
            {{-- Add Product Large Screen : End --}}

            <a class="relative block hover:text-current"
                @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}"
            @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @endif>

                {{-- Base Discount : Start --}}
                <span
                    class="absolute bg-white flex gap-1 top-2 ltr:left-0 rtl:right-0 flex justify-center items-center shadow p-1 ltr:rounded-r-full rtl:rounded-l-full text-primary text-sm font-bold">
                    <span>
                        {{ __('front/homePage.OFF') }}
                    </span>
                    <span class="flex items-center bg-primary text-white rounded-full p-1">
                        {{ $item['base_price'] > 0 ? 100 - round(($item['final_price'] * 100) / $item['base_price'], 0) : 0 }}%
                    </span>
                </span>
                {{-- Base Discount : End --}}

                {{-- Product Image : Start --}}
                <div class="block max-h-52 overflow-hidden" data-img-name = "{{ $item['thumbnail'] ? $item['thumbnail']['file_name'] : '' }}">
                    @if ($item['thumbnail'])
                        <img class="mx-auto max-h-52 h-full md:h-52 construction-placeholder" data-placeholder-size="text-[200px]"
                            @if ($item['type'] == 'Product') src="{{ asset('storage/images/products/cropped250/' . $item['thumbnail']['file_name']) }}"
                            @elseif ($item['type'] == 'Collection') src="{{ asset('storage/images/collections/cropped250/' . $item['thumbnail']['file_name']) }}" @endif>
                    @else
                        <div class="flex justify-center items-center bg-gray-100">
                            <span class="block material-icons text-[200px]">
                                construction
                            </span>
                        </div>
                    @endif
                </div>
                {{-- Product Image : End --}}

                {{-- Extra Discount : Start --}}
                @if ($item['final_price'] > $item['best_price'])
                    <span
                        class="absolute bottom-2 rtl:right-0 ltr:left-0 text-xs font-bold text-white px-2 py-1 bg-primary">
                        {{ __('front/homePage.Extra Discount') }}
                        {{ $item['final_price'] > 0 ? round(($item['offer_discount'] * 100) / $item['final_price']) : 0 }}%
                    </span>
                @endif
                {{-- Extra Discount : End --}}
            </a>

            <a class="md:p-3 p-2 text-left block hover:text-current"
                @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}"
            @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @endif>
                {{-- Price : Start --}}
                <div class="flex flex-wrap-reverse justify-center items-center gap-3">
                    @if ($item['under_reviewing'])
                        <span class="text-yellow-600 font-bold mb-2">
                            {{ __('front/homePage.Under Reviewing') }}
                        </span>
                    @else
                        {{-- Final Price : Start --}}
                        <div class="flex rtl:flex-row-reverse gap-1">
                            <span class="font-bold text-successDark text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span class="font-bold text-successDark text-2xl"
                                dir="ltr">{{ number_format(explode('.', $item['final_price'])[0], 0, '.', '\'') }}</span>
                            <span
                                class="font-bold text-successDark text-xs">{{ explode('.', $item['final_price'])[1] }}</span>
                        </div>
                        {{-- Final Price : End --}}

                        {{-- Base Price : Start --}}
                        <del class="flex rtl:flex-row-reverse gap-1 font-bold text-red-400 text-sm">
                            <span>
                                {{ __('front/homePage.EGP') }}
                            </span>
                            <span class="font-bold text-3xl"
                                dir="ltr">{{ number_format(explode('.', $item['base_price'])[0], 0, '.', '\'') }}</span>
                        </del>
                        {{-- Base Price : End --}}
                    @endif

                </div>
                {{-- Price : End --}}

                {{-- Free Shipping: Start --}}
                @if ($item['free_shipping'])
                    <div class="text-center text-success font-bold text-sm">
                        {{ __('front/homePage.Free Shipping') }}
                    </div>
                @endif
                {{-- Free Shipping: End --}}

                {{-- Reviews : Start --}}
                <div class="my-1 text-center flex justify-center items-center gap-2">
                    <div class="rating flex">
                        @for ($i = 1; $i <= 5; $i++)
                            <span
                                class="material-icons inline-block @if ($i <= ceil($item['avg_rating'])) text-yellow-300 @else text-gray-400 @endif">
                                star
                            </span>
                        @endfor
                    </div>

                    <span class="text-sm text-gray-600">({{ $item['reviews_count'] }})</span>
                </div>
                {{-- Reviews : End --}}

                <div class="flex flex-col gap-2">

                    {{-- Product Name : Start --}}
                    <h4 class="text-md m-0 text-center font-bold truncate">
                        {{ $item['name'][session('locale')] }}
                    </h4>
                    {{-- Product Name : End --}}

                    {{-- Product Model : Start --}}
                    <h4 dir="ltr" class="text-sm m-0 text-center text-gray-500 truncate">
                        {{ $item['model'] }}
                    </h4>
                    {{-- Product Model : End --}}

                    {{-- Availablility :: Start --}}
                    @if (isset($item['quantity']))
                        @if ($item['quantity'] <= 0)
                            <h5 class="font-bold text-sm text-red-600 text-center">
                                {{ __('front/homePage.Currently Not Available') }}
                            </h5>
                        @elseif ($item['quantity'] == 1)
                            <h5 class="font-bold text-sm text-yellow-600 text-center">
                                {{ __('front/homePage.Last Piece') }}
                            </h5>
                        @elseif ($item['quantity'] == 2)
                            <h5 class="font-bold text-sm text-yellow-600 text-center">
                                {{ __('front/homePage.Only 2 Pieces Remaining') }}
                            </h5>
                        @elseif ($item['quantity'] == 3)
                            <h5 class="font-bold text-sm text-success text-center">
                                {{ __('front/homePage.Only 3 Pieces Remaining') }}
                            </h5>
                        @endif
                    @endif
                    {{-- Availablility :: End --}}
                </div>

                {{-- Points : Start --}}
                @if ($item['points'] || $item['best_points'])
                    <div
                        class="rounded px-2 mt-2 bg-gray-200 border-gray-800 text-black text-sm border flex justify-between items-center">
                        <span>{{ __('front/homePage.Points') }}</span>
                        <span
                            dir="ltr">{{ $item['best_points'] > $item['points'] ? number_format($item['best_points'], 0, '.', '\'') : number_format($item['points'], 0, '.', '\'') }}</span>
                    </div>
                @endif
                {{-- Points : End --}}
            </a>

            {{-- Cart Amount : Start --}}
            @if ($item['quantity'])
                <div class="md:p-3 p-2">
                    @livewire(
                        'front.general.cart.cart-amount',
                        [
                            'item_id' => $item['id'],
                            'type' => $item['type'],
                            'unique' => 'item-' . $item['id'],
                        ],
                        key($item['name'][session('locale')] . '-' . rand())
                    )
                </div>
            @endif
            {{-- Cart Amount : End --}}

            {{-- Add Product Small Screen : Start --}}
            <div class="flex top-2 gap-1 justify-center mb-3 lg:hidden">
                {{-- Add to compare : Start --}}
                @livewire('front.general.compare.add-to-compare-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-compare-button-' . Str::random(10)))
                {{-- Add to compare : End --}}

                @if ($wishlist)
                    {{-- Remove from wishlist : Start --}}
                    @livewire('front.general.wishlist.remove-from-wishlist-button', ['item_id' => $item['id'], 'type' => $item['type']], key('remove-wishlist-button-' . Str::random(10)))
                    {{-- Remove from wishlist : End --}}
                @else
                    {{-- Add to wishlist : Start --}}
                    @livewire('front.general.wishlist.add-to-wishlist-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-wishlist-button-' . Str::random(10)))
                    {{-- Add to wishlist : End --}}
                @endif

                @if (isset($item['quantity']) && $item['quantity'] > 0)
                    {{-- Add to cart : Start --}}
                    @livewire('front.general.cart.add-to-cart-button', ['item_id' => $item['id'], 'type' => $item['type']], key('add-cart-button-' . Str::random(10)))
                    {{-- Add to cart : End --}}
                @endif
            </div>
            {{-- Add Product Small Screen : End --}}
        </div>
    </div>

</li>
{{-- Product : End --}}

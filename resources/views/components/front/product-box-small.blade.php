{{-- Product : Start --}}
<li class="splide__slide">
    <div class="carousel-box w-full inline-block">
        <div class="group border border-light rounded hover:shadow-md hover:scale-105 mt-1 mb-2 transition overflow-hidden relative">

            {{-- Add Product : Start --}}
            <div
                class="absolute top-2 ltr:-right-10 z-10 rtl:-left-10 transition-all ease-in-out duration-500 ltr:group-hover:right-2 rtl:group-hover:left-2 flex flex-col gap-1">
                {{-- Add to compare : Start --}}
                @livewire('front.general.compare.add-to-compare-button', ['product_id' => $product['id']], key('add-compare-button-' . Str::random(10)))
                {{-- Add to compare : End --}}

                {{-- Add to wishlist : Start --}}
                @livewire('front.general.wishlist.add-to-wishlist-button', ['product_id' => $product['id']], key('add-wishlist-button-' . Str::random(10)))
                {{-- Add to wishlist : End --}}

                @if ($product['quantity'] > 0)
                {{-- Add to cart : Start --}}
                @livewire('front.general.cart.add-to-cart-button', ['product_id' => $product['id']], key('add-cart-button-' . Str::random(10)))
                {{-- Add to cart : End --}}
                @endif
            </div>
            {{-- Add Product : End --}}

            <a class="relative block hover:text-current"
                href="{{ route('front.product.show', ['id' => $product['id'], 'slug' => $product['slug']]) }}">

                {{-- Base Discount : Start --}}
                <span
                    class="absolute bg-white flex gap-1 top-2 ltr:left-0 rtl:right-0 flex justify-center items-center shadow p-1 ltr:rounded-r-full rtl:rounded-l-full text-primary text-sm font-bold">
                    <span>
                        {{ __('front/homePage.OFF') }}
                    </span>
                    <span class="flex items-center bg-primary text-white rounded-full p-1">
                        {{ 100 - round(($product['final_price'] * 100) / $product['base_price'], 0) }}%
                    </span>
                </span>
                {{-- Base Discount : End --}}

                {{-- Product Image : Start --}}
                <div class="block max-h-52 overflow-hidden">
                    @if ($product['thumbnail'])
                        <img class="mx-auto max-h-52 h-full md:h-52 lazyloaded"
                            src="{{ asset('storage/images/products/original/' . $product['thumbnail']['file_name']) }}">
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
                @if ($product['final_price'] > $product['best_price'])
                    <span
                        class="absolute bottom-2 rtl:right-0 ltr:left-0 text-xs font-bold text-white px-2 py-1 bg-primary">
                        {{ __('front/homePage.Extra Discount') }}
                        {{ round((($product['final_price'] - $product['best_price']) * 100) / $product['final_price']) }}%
                    </span>
                @endif
                {{-- Extra Discount : End --}}
            </a>

            <a class="md:p-3 p-2 text-left block hover:text-current" href="{{ route('front.product.show', ['id' => $product['id'], 'slug' => $product['slug']]) }}">
                {{-- Price : Start --}}
                <div class="flex flex-wrap-reverse justify-center items-center gap-3">
                    @if ($product['under_reviewing'])
                        <span class="text-yellow-600 font-bold mb-2">
                            {{ __('front/homePage.Under Reviewing') }}
                        </span>
                    @else
                        {{-- Final Price : Start --}}
                        <div class="flex rtl:flex-row-reverse gap-1">
                            <span class="font-bold text-primary text-sm">{{ __('front/homePage.EGP') }}</span>
                            <span
                                class="font-bold text-primary text-2xl" dir="ltr">{{ number_format(explode('.', $product['final_price'])[0],0,'.','\'') }}</span>
                            <span
                                class="font-bold text-primary text-xs">{{ explode('.', $product['final_price'])[1] }}</span>
                        </div>
                        {{-- Final Price : End --}}

                        {{-- Base Price : Start --}}
                        <del class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400 text-sm">
                            <span>
                                {{ __('front/homePage.EGP') }}
                            </span>
                            <span class="font-bold text-3xl" dir="ltr">{{ number_format(explode('.', $product['base_price'])[0],0,'.','\'') }}</span>
                        </del>
                        {{-- Base Price : End --}}
                    @endif

                </div>
                {{-- Price : End --}}

                {{-- Free Shipping : Start --}}
                @if ($product['free_shipping'])
                    <div class="text-center text-success font-bold text-sm">
                        {{ __('front/homePage.Free Shipping') }}
                    </div>
                @endif
                {{-- Free Shipping : End --}}

                {{-- Reviews : Start --}}
                <div class="my-1 text-center flex justify-center items-center gap-2">
                    <div class="rating flex">
                        @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="material-icons inline-block @if ($i <= ceil($product['avg_rating'])) text-yellow-300 @else text-gray-400 @endif">
                                    star
                                </span>
                            @endfor
                    </div>

                    <span class="text-sm text-gray-600">({{ $product['reviews_count'] }})</span>
                </div>
                {{-- Reviews : End --}}

                <div class="flex flex-col gap-2">

                    {{-- Product Name : Start --}}
                    <h4 class="text-md m-0 text-center font-bold truncate">
                        {{ $product['name'] }}
                    </h4>
                    {{-- Product Name : End --}}

                    {{-- Product Model : Start --}}
                    <h4 dir="ltr" class="text-sm m-0 text-center text-gray-500 truncate">
                        {{ $product['model'] }}
                    </h4>
                    {{-- Product Model : End --}}

                    {{-- Availablility :: Start --}}
                    @if ($product['quantity'] <= 0)
                        <h5 class="font-bold text-sm text-red-600 text-center">
                            {{ __('front/homePage.Currently Not Available') }}
                        </h5>
                    @elseif ($product['quantity'] == 1)
                        <h5 class="font-bold text-sm text-yellow-600 text-center">
                            {{ __('front/homePage.Last Piece') }}
                        </h5>
                    @elseif ($product['quantity'] == 2)
                        <h5 class="font-bold text-sm text-yellow-600 text-center">
                            {{ __('front/homePage.Only 2 Pieces Remaining') }}
                        </h5>
                    @elseif ($product['quantity'] == 3)
                        <h5 class="font-bold text-sm text-success text-center">
                            {{ __('front/homePage.Only 3 Pieces Remaining') }}
                        </h5>
                    @endif
                    {{-- Availablility :: End --}}
                </div>

                {{-- Points : Start --}}
                @if ($product['points'] || $product['best_points'])
                    <div
                        class="rounded px-2 mt-2 bg-gray-200 border-gray-800 text-black text-sm border flex justify-between items-center">
                        <span>{{ __('front/homePage.Points') }}</span>
                        <span>{{ $product['best_points'] > $product['points'] ? round($product['best_points']) : $product['points'] }}</span>
                    </div>
                @endif
                {{-- Points : End --}}
            </a>

            {{-- Cart Amount : Start --}}
            <div class="md:p-3 p-2">
                @livewire('front.general.cart.cart-amount', ['product_id' => $product['id'], 'unique' => 'product-' . $product['id']], key($product['name'] . '-' . rand()))
            </div>
            {{-- Cart Amount : End --}}
        </div>
    </div>

</li>
{{-- Product : End --}}

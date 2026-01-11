{{-- Product Item : Start --}}
<div class="p-6 flex flex-col sm:flex-row items-center gap-6 group">
    {{-- Product Thumbnail --}}
    <div class="relative flex-shrink-0">
        <a @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @endif
            class="block">
            <div
                class="w-24 h-24 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 p-2 group-hover:shadow-md transition-shadow">
                @if ($item['thumbnail'])
                    <img @if ($item['type'] == 'Product') src="{{ asset('storage/images/products/cropped100/' . $item['thumbnail']['file_name']) }}" @elseif ($item['type'] == 'Collection') src="{{ asset('storage/images/collections/cropped100/' . $item['thumbnail']['file_name']) }}" @endif
                        alt="{{ $item['name'][session('locale')] }}"
                        class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                        <span class="material-icons text-4xl">inventory_2</span>
                    </div>
                @endif
            </div>
        </a>
    </div>

    {{-- Product Info --}}
    <div class="flex-grow text-center sm:text-left rtl:sm:text-right">
        {{-- Brand --}}
        @if (isset($item['brand']))
            <div class="mb-1">
                <a href="{{ route('front.brands.show', ['brand' => $item['brand']['id']]) }}"
                    class="text-xs text-gray-400 hover:text-primary transition-colors font-medium uppercase tracking-wider">
                    {{ $item['brand']['name'] }}
                </a>
            </div>
        @endif

        {{-- Product Name --}}
        <h4 class="font-bold text-gray-800 hover:text-primary transition-colors">
            <a
                @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}"
                @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug'][session('locale')]]) }}" @endif>
                {{ $item['name'][session('locale')] }}
            </a>
        </h4>

        {{-- Reviews : Start --}}
        <div class="my-1 flex justify-center lg:justify-start items-center gap-2 select-none">
            <div class="rating flex">
                @for ($i = 1; $i <= 5; $i++)
                    <span
                        class="material-icons text-sm inline-block @if ($i <= ceil($item['avg_rating'])) text-yellow-300 @else text-gray-400 @endif">
                        star
                    </span>
                @endfor
            </div>

            <span class="text-sm text-gray-600">({{ $item['reviews_count'] ?? 0 }})</span>
        </div>
        {{-- Reviews : End --}}

        {{-- Unit Price --}}
        @if (!$item['under_reviewing'])
            <div class="flex justify-center items-center lg:justify-start gap-3">
                {{-- Base Price : Start --}}
                <del class="flex rtl:flex-row-reverse gap-1 font-bold text-gray-400">
                    <span class="text-xs">
                        {{ __('front/homePage.EGP') }}
                    </span>
                    <span class="font-bold text-xl"
                        dir="ltr">{{ number_format(explode('.', $item['base_price'])[0], 0, '.', '\'') }}</span>
                </del>
                {{-- Base Price : End --}}

                {{-- Final Price : Start --}}
                <div class="flex rtl:flex-row-reverse gap-1">
                    <span class="font-bold text-successDark text-xs">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-successDark text-lg"
                        dir="ltr">{{ number_format(explode('.', $item['final_price'])[0], 0, '.', '\'') }}</span>
                    <span class="text-successDark text-xs">{{ explode('.', $item['final_price'])[1] ?? '00' }}</span>
                </div>
                {{-- Final Price : End --}}
            </div>
        @else
            <span class="text-yellow-600 font-bold text-sm">
                {{ __('front/homePage.Under Reviewing') }}
            </span>
        @endif

        {{-- Additional Info & Actions --}}
        <div class="mt-3 flex flex-wrap items-center justify-center sm:justify-start gap-3">
            @if ($type == 'cart')
                {{-- Add to Wishlist --}}
                @livewire(
                    'front.general.wishlist.add-to-wishlist-button',
                    [
                        'item_id' => $item['id'],
                        'type' => $item['type'],
                        'text' => true,
                        'remove' => false,
                    ],
                    key('add-wishlist-button-' . $item['id'] . '-' . $item['type'])
                )
            @elseif ($type == 'wishlist')
                {{-- Move to Cart --}}
                @livewire(
                    'front.general.cart.add-to-cart-button',
                    [
                        'item_id' => $item['id'],
                        'type' => $item['type'],
                        'text' => true,
                    ],
                    key('move-to-cart-button-' . $item['id'] . '-' . $item['type'])
                )
            @endif
        </div>
    </div>

    @if ($type == 'cart')
        {{-- Quantity Controls :: Integrated --}}
        <div class="p-2">
            <div class="flex gap-4 px-3 py-2 items-center bg-gray-50 rounded-2xl w-fit mx-auto sm:mx-0">
                {{-- Decrease --}}
                <button
                    class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400 hover:text-red-600 transition-colors disabled:opacity-50"
                    title="{{ __('front/homePage.Decrease') }}" wire:click="decrement"
                    @if (($item['cartQty'] ?? 0) <= 1) disabled @endif>
                    <span class="material-icons text-lg">remove</span>
                </button>

                {{-- Amount --}}
                <div class="relative w-8 text-center uppercase">
                    <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        class="bg-transparent border-none p-0 text-center font-bold text-gray-800 focus:ring-0 text-sm w-full"
                        value="{{ $item['cartQty'] ?? 0 }}" wire:change="updateQuantity($event.target.value)">
                </div>

                {{-- Increase --}}
                <button
                    class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-400 hover:text-successDark transition-colors"
                    title="{{ __('front/homePage.Increase') }}" wire:click="increment">
                    <span class="material-icons text-lg">add</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Actions --}}
    <div class="flex flex-col items-center sm:items-end gap-4 min-w-[150px]">
        {{-- Total --}}
        @if ($type == 'cart')
            <div class="flex items-center gap-1 font-bold text-successDark w-full lg:justify-end">
                <span class="text-xs">{{ __('front/homePage.EGP') }}</span>
                <span class="text-lg">{{ number_format($this->total, 2) }}</span>
            </div>
        @endif

        {{-- Free Shipping:: Start --}}
        @if ($item['free_shipping'])
            <span class="text-xs font-bold text-success text-center lg:text-right rtl:lg:text-left w-full">
                {{ __('front/homePage.Free Shipping') }}
            </span>
        @endif
        {{-- Free Shipping:: End --}}

        @if ($type == 'cart')
            {{-- Remove from Cart --}}
            <button wire:click="removeFromCart"
                class="text-xs text-gray-400 hover:text-primary flex items-center gap-1 transition-colors uppercase font-bold tracking-tighter">
                <span class="material-icons text-sm">delete</span>
                {{ __('front/homePage.Remove from Cart') }}
            </button>
        @elseif ($type == 'wishlist')
            {{-- Remove from Wishlist --}}
            <button wire:click="removeFromWishlist"
                class="text-xs text-gray-400 hover:text-primary flex items-center gap-1 transition-colors uppercase font-bold tracking-tighter">
                <span class="material-icons text-sm">delete</span>
                {{ __('front/homePage.Remove from Wishlist') }}
            </button>
        @endif
    </div>
</div>
{{-- Product Item : End --}}

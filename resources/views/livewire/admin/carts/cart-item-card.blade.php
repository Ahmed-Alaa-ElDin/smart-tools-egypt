<div class="transition-all ease-in-out hover:bg-gray-100 hover:text-black rounded hover:shadow-xl px-2 bg-white p-2">
    <a @if ($cartItem['options']['type'] == 'Product') href="{{ route('front.products.show', ['id' => $cartItem['id'], 'slug' => $cartItem['options']['slug']]) }}"
                            @elseif ($cartItem['options']['type'] == 'Collection')
                            href="{{ route('front.collections.show', ['id' => $cartItem['id'], 'slug' => $cartItem['options']['slug']]) }}" @endif
                            target="_blank"
        class="flex flex-nowrap gap-4 justify-between items-center hover:bg-gray-100 hover:text-current hover:shadow-none w-full py-2">

        <div class="flex flex-nowrap gap-2 items-center">
            {{-- Thumbnail :: Start --}}
            @if ($cartItem['options']['thumbnail'])
                <img @if ($cartItem['options']['type'] == 'Product') src="{{ asset('storage/images/products/cropped100/' . $cartItem['options']['thumbnail']['file_name']) }}"
                                @elseif ($cartItem['options']['type'] == 'Collection')
                                src="{{ asset('storage/images/collections/cropped100/' . $cartItem['options']['thumbnail']['file_name']) }}" @endif
                    class="w-14 h-14 rounded" alt="{{ $cartItem['name'][session('locale')] }}">
            @else
                <img src="{{ asset('assets/img/logos/smart-tools-logo-50.png') }}" class="w-14 h-14 rounded"
                    alt="{{ $cartItem['name'][session('locale')] }}">
            @endif
            {{-- Thumbnail :: End --}}

            {{-- Product Name :: Start --}}
            <h3 class="h5 m-0 font-bold truncate">
                {{ $cartItem['name'][session('locale')] }}
            </h3>
            {{-- Product Name :: End --}}
        </div>

        <div class="flex flex-col gap-2 items-center text-sm">
            {{-- Product Amount & Price :: Start --}}
            <div class="flex flex-nowrap items-center" dir="ltr">
                <span class="font-bold">
                    {{ $cartItem['qty'] }}
                </span>
                &nbsp; x &nbsp;
                <div class="flex gap-1" dir="ltr">
                    <span
                        class="font-bold text-green-700">{{ number_format(explode('.', $cartItem['price'])[0], 0, '.', '\'') }}</span>
                    <span class="font-bold text-green-700 text-3xs">{{ explode('.', $cartItem['price'])[1] ?? '00' }}
                    </span>
                </div>
            </div>
            {{-- Product Amount & Price :: End --}}

            {{-- Total Price :: Start --}}
            <div class="flex flex-nowrap items-center gap-1" dir="ltr">
                <span class="font-bold text-green-700 text-lg">
                    {{ number_format(explode('.', $cartItem['qty'] * $cartItem['price'])[0], 0, '.', '\'') }}
                </span>
                <sup class="font-bold text-green-700 text-xs">{{ explode('.', $cartItem['qty'] * $cartItem['price'])[1] ?? '00' }}
                </sup>
            </div>
            {{-- Total Price :: End --}}
        </div>
    </a>
</div>

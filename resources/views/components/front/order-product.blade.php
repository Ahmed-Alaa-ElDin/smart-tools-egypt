<div class="flex gap-5 items-center p-2 scrollbar scrollbar-hidden">
    {{-- Thumnail :: Start --}}
    <a @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug']]) }}"
    @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug']]) }}" @endif
        class="min-w-max block hover:text-current">
        @if ($item->thumbnail)
            <img class="w-24 h-24 flex justify-center items-center bg-gray-200 rounded"
                @if ($item['type'] == 'Product') src="{{ asset('storage/images/products/original/' . $item['thumbnail']['file_name']) }}"
            @elseif ($item['type'] == 'Collection') src="{{ asset('storage/images/collections/original/' . $item['thumbnail']['file_name']) }}" @endif
                alt="{{ $item->name . 'image' }}">
        @else
            <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded">
                <span class="block material-icons text-8xl">
                    construction
                </span>
            </div>
        @endif
    </a>
    {{-- Thumnail :: End --}}

    {{-- Product Info :: Start --}}
    <div class="grow flex flex-col justify-start gap-2">
        {{-- Product's Brand :: Start --}}
        @if (isset($item['brand']))
            {{-- todo :: brand link --}}
            <div class="flex items-center">
                <a href="#" class="text-sm font-bold text-gray-400 hover:text-current">
                    {{ $item['brand'] ? $item['brand']['name'] : '' }}
                </a>
            </div>
        @endif
        {{-- Product's Brand :: End --}}

        {{-- Product Name :: Start --}}
        <div class="flex items-center">
            <a @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug']]) }}"
    @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug']]) }}" @endif
                class="text-lg font-bold truncate  hover:text-current">
                {{ $item->name }}
            </a>
        </div>
        {{-- Product Name :: End --}}

        {{-- Reviews :: Start --}}
        <div class="my-1 flex justify-start items-center gap-2">
            <div class="rating flex">
                @for ($i = 1; $i <= 5; $i++)
                    <span
                        class="material-icons inline-block @if ($i <= ceil($item->avg_rating)) text-yellow-300 @else text-gray-400 @endif">
                        star
                    </span>
                @endfor
            </div>

            <span class="text-sm text-gray-600">({{ $item->reviews->count() ?? 0 }})</span>
        </div>
        {{-- Reviews :: End --}}

        {{-- Buy Again :: Start --}}
        <div>
            {{-- todo :: Add Item Type --}}
            @livewire('front.general.cart.add-to-cart-button', ['item_id' => $item['id'], 'type' => $item['type'], 'text' => true, 'add_buy' => 'buy'], key('add-cart-button-' . Str::random(10)))
        </div>
        {{-- Buy Again :: End --}}

    </div>
    {{-- Product Info :: End --}}

    {{-- Product Price :: Start --}}
    <div class="flex flex-col justify-center items-end gap-1">
        @if ($item['under_reviewing'])
            <span class="text-yellow-600 font-bold text-sm">
                {{ __('front/homePage.Under Reviewing') }}
            </span>
        @else
            <div class="flex flex-col md:flex-row-reverse items-center gap-3">
                {{-- Base Price :: Start --}}
                <del class="flex rtl:flex-row-reverse gap-1 font-bold text-red-400">
                    <span class="text-xs">
                        {{ __('front/homePage.EGP') }}
                    </span>
                    <span class="font-bold text-2xl"
                        dir="ltr">{{ number_format(explode('.', $item['base_price'])[0], 0, '.', '\'') }}</span>
                </del>
                {{-- Base Price :: End --}}

                {{-- Final Price :: Start --}}
                <div class="flex rtl:flex-row-reverse gap-1 text-successDark">
                    <span class="font-bold text-xs">{{ __('front/homePage.EGP') }}</span>
                    <span class="font-bold text-lg" {{-- dir="ltr">{{ $item->pivot->price }}</span> --}}
                        dir="ltr">{{ number_format(explode('.', $item->pivot->price)[0], 0, '.', '\'') ?? '00' }}</span>
                    <span class="text-xs">{{ explode('.', $item->pivot->price)[1] ?? '00' }}</span>
                </div>
                {{-- Final Price :: End --}}

            </div>
        @endif


        {{-- Product Amount :: Start --}}
        <div>
            <div class="flex items-center justify-center gap-1">
                <span class="text-xs font-bold text-gray-600">
                    {{ __('front/homePage.Quantity') }}
                </span>
                <span class="text-primary font-bold">
                    {{ $item->pivot->quantity }}
                </span>
            </div>
        </div>
        {{-- Product Amount :: End --}}

        {{-- Add Review :: Start --}}
        @if ($item->can_review)
            <div class="">
                <a @if ($item['type'] == 'Product') href="{{ route('front.products.show', ['id' => $item['id'], 'slug' => $item['slug']]) }}" @elseif ($item['type'] == 'Collection') href="{{ route('front.collections.show', ['id' => $item['id'], 'slug' => $item['slug']]) }}" @endif
                    class="btn btn-sm bg-primary font-bold rounded-full">
                    {{ __('front/homePage.Add Review') }}
                </a>
            </div>
        @endif
        {{-- Add Review :: End --}}

    </div>
    {{-- Product Price :: End --}}
</div>

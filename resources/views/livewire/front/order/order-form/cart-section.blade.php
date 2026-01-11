<div>
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="material-icons text-primary">shopping_cart</span>
            {{ __('front/homePage.Shopping Cart') }}
            <span class="bg-red-50 text-primary text-xs px-2 py-1 rounded-full ml-1">
                {{ $this->cart_count }}
            </span>
        </h3>
        <a href="{{ route('front.cart') }}"
            class="text-sm text-primary hover:text-primary-dark font-bold flex items-center gap-1">
            {{ __('front/homePage.View & Edit Cart') }}
            <span class="material-icons text-xs">edit</span>
        </a>
    </div>

    @forelse($this->cart as $item)
        @livewire('front.product.product-card-wide', ['item' => $item], key('cart-row-' . $item['rowId']))
    @empty
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-icons text-4xl text-gray-200">shopping_basket</span>
            </div>
            <p class="text-gray-500 font-medium">{{ __('front/homePage.Shopping Cart is Empty') }}</p>
            <a href="{{ route('front.homepage') }}" class="mt-4 inline-block text-primary font-bold hover:underline">
                {{ __('front/homePage.Back to Shopping') }}
            </a>
        </div>
    @endforelse
</div>

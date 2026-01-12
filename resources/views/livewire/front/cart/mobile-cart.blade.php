<a href="{{ route('front.cart') }}" class="relative flex justify-center text-center pb-2 pt-3 min-w-max text-xs">
    <span
        class="flex justify-center items-center bg-primary h-12 w-12 border border-white border-4 rounded-circle absolute -mt-12">
        <span class="material-icons text-white">
            shopping_cart
        </span>
    </span>
    <span class="flex justify-center items-center mt-1 font-bold opacity-60">
        {{ __('front/homePage.Cart') }}
        (<span class="cart-count">{{ Cart::instance('cart')->count() }}</span>)
    </span>
</a>
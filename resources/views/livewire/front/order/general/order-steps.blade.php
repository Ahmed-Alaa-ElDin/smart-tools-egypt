<div class="flex gap-3 justify-around items-center select-none">
    <a class="block text-center @if ($step == 1) text-primary @elseif($step > 1) text-secondary @else text-gray-400 @endif"
        @if ($step > 1) href="{{ route('front.cart') }}" @else href="javascript:void(0)" @endif>
        <span class="material-icons text-4xl mb-2">
            shopping_cart
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.My Cart') }}
        </h3>
    </a>

    <span class="material-icons rtl:rotate-180 text-gray-400">
        arrow_forward_ios
    </span>

    <a
        class="block text-center @if ($step == 2) text-primary @elseif($step > 2) text-secondary @else text-gray-400 @endif"
        @if ($step > 2) href="{{ route('front.orders.checkout') }}" @else href="javascript:void(0)" @endif>
        <span class="material-icons text-4xl mb-2">
            local_shipping
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.Shipping info') }}
        </h3>
    </a>

    <span class="material-icons rtl:rotate-180 text-gray-400">
        arrow_forward_ios
    </span>

    <a
        class="block text-center @if ($step == 3) text-primary @elseif($step > 3) text-secondary @else text-gray-400 @endif"
        @if ($step > 3) href="{{ route('front.orders.payment') }}" @else href="javascript:void(0)" @endif>
        <span class="material-icons text-4xl mb-2">
            payment
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.Payment') }}
        </h3>
    </a>

    <span class="material-icons rtl:rotate-180 text-gray-400">
        arrow_forward_ios
    </span>

    <div
        class="block text-center @if ($step == 4) text-primary @elseif($step > 4) text-secondary
    @else
        text-gray-400 @endif">
        <span class="material-icons text-4xl mb-2">
            check
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.Confirmation') }}
        </h3>
    </div>
</div>

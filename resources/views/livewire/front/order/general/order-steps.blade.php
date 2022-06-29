<div class="flex gap-3 justify-around items-center select-none">
    <div class="text-center @if ($step == 1) text-primary @elseif($step > 1) text-secondary
    @else
        text-gray-400 @endif">
        <span class="material-icons text-4xl">
            shopping_cart
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.My Cart') }}
        </h3>
    </div>

    <span class="material-icons text-gray-400">
        arrow_back_ios
    </span>

    <div class="text-center @if ($step == 2) text-primary @elseif($step > 2) text-secondary
    @else
        text-gray-400 @endif">
        <span class="material-icons text-4xl">
            local_shipping
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.Shipping info') }}
        </h3>
    </div>

    <span class="material-icons text-gray-400">
        arrow_back_ios
    </span>

    <div class="text-center @if ($step == 3) text-primary @elseif($step > 3) text-secondary
    @else
        text-gray-400 @endif">
        <span class="material-icons text-4xl">
            payment
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.Payment') }}
        </h3>
    </div>

    <span class="material-icons text-gray-400">
        arrow_back_ios
    </span>

    <div class="text-center @if ($step == 4) text-primary @elseif($step > 4) text-secondary
    @else
        text-gray-400 @endif">
        <span class="material-icons text-4xl">
            check
        </span>
        <h3 class="text-md font-bold hidden lg:block">
            {{ __('front/homePage.Confirmation') }}
        </h3>
    </div>
</div>

<div class="relative">
    <div wire:loading class="absolute top-0 left-0 z-10 w-full h-full backdrop-blur-sm select-none animate-pulse">
        <div class="flex flex-col justify-center items-center">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-waiting-400.png') }}"
                class="w-48 drop-shadow-[0px_0px_20px_rgba(255,255,255,0.7)]" alt="Loading" draggable="false">
            <span class="text-primary h3 font-bold"
                style="text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff">{{ __('admin/master.Loading ...') }}</span>
        </div>
    </div>

    {{-- ############## Title :: Start ############## --}}
    <div class="flex justify-between items-center p-4">
        <h3 class="h5 text-center font-bold m-0">
            {{ __('front/homePage.Order Summary') }}
        </h3>

        <h4 class="text-sm font-bold">
            {{ trans_choice('front/homePage.Product', Cart::instance('cart')->count(), ['product' => Cart::instance('cart')->count()]) }}
        </h4>
    </div>
    {{-- ############## Title :: End ############## --}}
    <hr>
    <div class="font-bold p-4 flex flex-col gap-3 justify-center items-center">

        {{-- todo --}}
        {{-- ############## Coupon :: Start ############## --}}
        {{-- <div class="w-full flex gap-3">
            <input
                class="grow-1 rounded text-center border-gray-300 text-gray-500 focus:outline-gray-600 focus:ring-gray-300 focus:border-gray-300"
                type="text" placeholder="{{ __('front/homePage.Coupon Code') }}">
            <button class="btn bg-primary font-bold self-stretch">{{ __('front/homePage.Apply') }}</button>
        </div> --}}
        {{-- ############## Coupon :: End ############## --}}

        {{-- ############## Subtotal :: Start ############## --}}
        <div class="w-100 flex justify-between items-center">
            <div class="h6 font-bold m-0">
                {{ __('front/homePage.Subtotal :') }}
            </div>
            <div class="flex rtl:flex-row-reverse gap-1 text-primary">
                <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                <span class="font-bold text-xl"
                    dir="ltr">{{ number_format(explode('.', $products_final_prices)[0], 0, '.', '\'') }}</span>
                <span class="font-bold text-xs">{{ explode('.', $products_final_prices)[1] ?? '00' }}</span>
            </div>
        </div>
        {{-- ############## Subtotal :: End ############## --}}

        {{-- ############## Extra Discount :: Start ############## --}}
        @if ($discount)
            <div class="w-100 flex justify-between items-center">
                <div class="h6 font-bold m-0">
                    {{ __('front/homePage.Extra Discount :') }}
                </div>
                <div class="flex gap-2">
                    <span class="flex rtl:flex-row-reverse gap-1 text-success">
                        -
                        <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                        <span class="font-bold text-xl"
                            dir="ltr">{{ number_format(explode('.', $discount)[0], 0, '.', '\'') }}</span>
                        <span
                            class="font-bold text-xs">{{ explode('.', number_format($discount), 2)[1] ?? '00' }}</span>
                    </span>
                    <span class="text-success">
                        ({{ $discount_percent }} %) -
                    </span>
                </div>
            </div>
        @endif
        {{-- ############## Extra Discount :: End ############## --}}

        {{-- ############## Shipping :: Start ############## --}}
        {{-- todo : get actual value --}}
        <div class="w-100 flex justify-between items-center">
            <div class="h6 font-bold m-0">
                {{ __('front/homePage.Shipping :') }}
            </div>
            <div class="">
                <span class="text-success">
                    {{ __('front/homePage.Free Shipping') }}
                </span>
            </div>
        </div>
        {{-- ############## Shipping :: End ############## --}}
    </div>

    <hr>

    <div class="p-4 flex flex-col gap-3 justify-center items-center">
        {{-- ############## Total :: Start ############## --}}
        <div class="w-full flex justify-between items-center">
            <div class="h6 font-bold m-0">
                {{ __('front/homePage.Total :') }}
            </div>
            <div class="flex rtl:flex-row-reverse gap-1 text-success">
                <span class="font-bold text-sm">{{ __('front/homePage.EGP') }}</span>
                <span class="font-bold text-2xl"
                    dir="ltr">{{ number_format(explode('.', $products_best_prices)[0], 0, '.', '\'') }}</span>
                <span
                    class="font-bold text-xs">{{ number_format(explode('.', $products_best_prices)[1] ?? '00', 0) ?? '00' }}</span>
            </div>
        </div>
        {{-- ############## Total :: End ############## --}}
    </div>

    <hr>

    {{-- ############## Buttons :: Start ############## --}}
    @if (Gloudemans\Shoppingcart\Facades\Cart::instance('cart')->count() > 0)
        <div class="p-2 flex justify-center items-center">
            <button class="btn bg-primary font-bold self-stretch" wire:click="">
                {{ __('front/homePage.Proceed to Shipping Info.') }}
                &nbsp;
                <span class="material-icons">
                    local_shipping
                </span>
            </button>
        </div>
    @else
        <div class="text-center p-3">
            <a href="{{ route('front.homepage') }}" class="btn bg-primary font-bold">
                {{ __('front/homePage.Continue Shopping') }}
            </a>
        </div>
    @endif

    {{-- ############## Buttons :: End ############## --}}
</div>

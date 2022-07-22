<div>
    @if (!$coupon_applied)
        <div class="w-full flex items-center justify-center gap-3">
            <input
                class="grow-1 rounded text-center border-red-300 focus:outline-red-600 focus:ring-red-300 focus:border-red-300"
                dir="ltr" type="text" placeholder="{{ __('front/homePage.Coupon Code') }}"
                wire:model.lazy="coupon">
            <button class="btn bg-primary font-bold self-stretch"
                wire:click="checkCoupon">{{ __('front/homePage.Apply') }}</button>
        </div>
    @else
        <div class="flex flex-col gap-2 justify-center items-center">
            <span class="text-successDark font-bold">
                {{ $success_message }}
            </span>
            <button class="btn btn-sm bg-primary font-bold" wire:click="removeCoupon">
                <span class="material-icons">
                    delete
                </span>
                &nbsp;
                {{ __('front/homePage.Remove Coupon') }}
            </button>
        </div>
    @endif

    @if ($error_message)
        <div class="flex justify-center items-center mt-2">
            <span class="text-primary font-bold text-sm">
                {{ $error_message }}
            </span>
        </div>
    @endif
</div>

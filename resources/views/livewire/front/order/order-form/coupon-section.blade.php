<div>
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="material-icons text-primary">confirmation_number</span>
            {{ __('front/homePage.Coupon Code') }}
        </h3>
    </div>

    <div class="p-6">
        @if ($applied_coupon)
            <div
                class="flex items-center justify-between p-2 bg-green-50 border-2 border-green-100 rounded-2xl animate-fadeIn">
                <div class="flex items-center gap-4 text-green-700">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="material-icons">check_circle</span>
                    </div>
                    <div>
                        <p class="font-bold text-sm">{{ $applied_coupon->code }}</p>
                    </div>
                </div>
                <button wire:click="removeCoupon" class="p-2 text-green-700 hover:text-primary transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>
        @else
            <div class="flex gap-2">
                <div class="grow relative">
                    <span
                        class="absolute left-4 rtl:left-auto right-4 top-1/2 -translate-y-1/2 material-icons text-gray-300">local_offer</span>
                    <input type="text" wire:model="coupon_code"
                        id="coupon_code"
                        placeholder="{{ __('front/homePage.Enter Coupon Code') }}"
                        class="w-full pl-12 pr-4 rtl:pr-12 rtl:pl-4 py-3 rounded-xl border-gray-100 focus:border-primary focus:ring-primary text-sm transition-all @error('coupon_code') border-red-300 @enderror">
                </div>
                <button wire:click="applyCoupon" wire:loading.attr="disabled"
                    class="px-6 py-3 bg-gray-800 text-white font-bold rounded-xl hover:bg-black transition-colors flex items-center gap-2 group disabled:opacity-50">
                    <span wire:loading.remove>{{ __('front/homePage.Apply') }}</span>
                    <span wire:loading class="material-icons animate-spin text-sm">sync</span>
                    <span wire:loading.remove
                        class="material-icons text-sm group-hover:scale-110 transition-transform">auto_awesome</span>
                </button>
            </div>
            @error('coupon_code')
                <p class="text-xs text-primary mt-2 font-bold px-2">{{ $message }}</p>
            @enderror
        @endif
    </div>
</div>

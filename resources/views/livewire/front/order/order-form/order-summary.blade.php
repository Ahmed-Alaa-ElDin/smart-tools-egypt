<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100/50 overflow-hidden relative">
    {{-- Loading Overlay --}}
    <div wire:loading.flex wire:key="cart-summary-loading"
        class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[2px] items-center justify-center animate-pulse">
        <div class="flex flex-col items-center opacity-20">
            <img src="{{ asset('assets/img/logos/smart-tools-logo-waiting-400.png') }}" class="w-32" alt="Loading">
            <span
                class="text-primary font-bold mt-2 text-xs uppercase tracking-widest">{{ __('front/homePage.Loading ...') }}</span>
        </div>
    </div>

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <span class="material-icons text-primary">receipt_long</span>
            {{ __('front/homePage.Cart Summary') }}
        </h3>
        <span class="bg-primary/10 text-primary text-xs font-black px-2.5 py-1 rounded-full uppercase">
            {{ trans_choice('front/homePage.Item', $this->items_total_quantities, ['item' => $this->items_total_quantities]) }}
        </span>
    </div>

    <div class="p-6 space-y-4">
        {{-- Base Subtotal --}}
        <div class="flex justify-between items-center">
            <span
                class="text-gray-500 font-medium text-sm">{{ __('front/homePage.Subtotal before products discounts') }}</span>
            <span
                class="font-bold text-gray-800 @if ($this->items_total_discounts || $this->offers_total_discounts || $this->order_discount) line-through opacity-30 @endif italic">
                {{ number_format($this->items_total_base_prices, 2) }} {{ __('front/homePage.EGP') }}
            </span>
        </div>

        {{-- Product Discounts --}}
        @if ($this->items_total_discounts > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn">
                <span class="text-successDark font-medium flex items-center gap-1 text-xs">
                    <span class="material-icons text-xs">sell</span>
                    {{ __('front/homePage.Products Discounts:') }}
                </span>
                <span class="font-bold text-successDark">
                    - {{ number_format($this->items_total_discounts, 2) }} {{ __('front/homePage.EGP') }}
                    ({{ $this->items_discounts_percentage }} %)
                </span>
            </div>
        @endif

        {{-- Offers Discounts --}}
        @if ($this->offers_total_discounts > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn text-xs">
                <span class="text-successDark font-medium flex items-center gap-1">
                    <span class="material-icons text-xs">local_offer</span>
                    {{ __('front/homePage.Offers Discounts:') }}
                </span>
                <span class="font-bold text-successDark">
                    - {{ number_format($this->offers_total_discounts, 2) }} {{ __('front/homePage.EGP') }}
                    ({{ $this->offers_discounts_percentage }} %)
                </span>
            </div>
        @endif

        {{-- Order Offer Discount --}}
        @if ($this->order_discount > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn text-xs">
                <span class="text-successDark font-medium flex items-center gap-1">
                    <span class="material-icons text-xs">celebration</span>
                    {{ __('front/homePage.Discount on order:') }}
                </span>
                <span class="font-bold text-successDark">
                    - {{ number_format($this->order_discount, 2) }} {{ __('front/homePage.EGP') }}
                    ({{ $this->order_discount_percentage }} %)
                </span>
            </div>
        @endif

        {{-- Coupon Discount --}}
        @if ($this->coupon_discount > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn text-xs">
                <span class="text-successDark font-medium flex items-center gap-1">
                    <span class="material-icons text-xs">confirmation_number</span>
                    {{ __('front/homePage.Coupon Discount:') }}
                </span>
                <span class="font-bold text-successDark">
                    - {{ number_format($this->coupon_discount, 2) }} {{ __('front/homePage.EGP') }}
                    ({{ $this->coupon_discount_percentage }} %)
                </span>
            </div>
        @endif

        {{-- Shipping --}}
        <div class="flex justify-between items-center text-sm pt-2">
            <span class="text-gray-500 font-medium">{{ __('front/homePage.Shipping:') }}</span>
            @if ($this->total_order_free_shipping)
                <span
                    class="text-successDark font-bold uppercase tracking-tighter text-[10px] bg-green-50 px-2 py-0.5 rounded-full">
                    {{ __('front/homePage.Free Shipping') }}
                </span>
            @elseif($this->shipping_fees > 0)
                <span class="font-bold text-gray-800">
                    {{ number_format($this->shipping_fees, 2) }} {{ __('front/homePage.EGP') }}
                </span>
            @else
                <span
                    class="text-gray-400 font-bold italic text-[10px] uppercase tracking-tighter bg-gray-50 px-2 py-0.5 rounded-full">
                    {{ __('front/homePage.uneligable for shipping') }}
                </span>
            @endif
        </div>

        {{-- Allow Opening Package Fee --}}
        @if (!$this->total_order_free_shipping && $this->allow_opening && $this->allow_opening_fee > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn">
                <span class="text-gray-500 font-medium flex items-center gap-1">
                    <span class="material-icons text-xs">inventory_2</span>
                    {{ __('front/homePage.Allow to open package') }}
                </span>
                <span class="font-bold text-gray-800">
                    + {{ number_format($this->allow_opening_fee, 2) }} {{ __('front/homePage.EGP') }}
                </span>
            </div>
        @endif

        {{-- Points Used --}}
        @if ($this->points_egp > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn text-xs">
                <span class="text-successDark font-medium flex items-center gap-1">
                    <span class="material-icons text-xs">stars</span>
                    {{ __('front/homePage.Points Discount:') }}
                </span>
                <span class="font-bold text-successDark">
                    - {{ number_format($this->points_egp, 2) }} {{ __('front/homePage.EGP') }}
                </span>
            </div>
        @endif

        {{-- Balance Used --}}
        @if ($this->balance_to_use > 0)
            <div class="flex justify-between items-center text-sm animate-fadeIn text-xs">
                <span class="text-successDark font-medium flex items-center gap-1">
                    <span class="material-icons text-xs">account_balance_wallet</span>
                    {{ __('front/homePage.Balance Used:') }}
                </span>
                <span class="font-bold text-successDark">
                    - {{ number_format($this->balance_to_use, 2) }} {{ __('front/homePage.EGP') }}
                </span>
            </div>
        @endif

        <hr class="border-gray-50">

        {{-- Points Section --}}
        @if ($this->total_points_after_order_points > 0)
            <div
                class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between animate-fadeIn border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                        <span class="material-icons text-primary">stars</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                            {{ __('front/homePage.You will get:') }}</p>
                        <p class="text-sm font-black text-gray-800">
                            {{ number_format($this->total_points_after_order_points + $this->coupon_items_points + $this->coupon_order_points) }}
                            {{ trans_choice('front/homePage.Point/Points', $this->total_points_after_order_points + $this->coupon_items_points + $this->coupon_order_points) }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Total --}}
        <div class="flex justify-between items-center py-2">
            <span
                class="text-gray-800 font-black text-lg uppercase tracking-tighter">{{ __('front/homePage.Total:') }}</span>
            <div class="text-right">
                <div class="flex items-center gap-1 font-black text-3xl text-successDark">
                    <span>{{ number_format($this->subtotal_final, 2) }}</span>
                    <span class="text-xs uppercase">{{ __('front/homePage.EGP') }}</span>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">
                    {{ __('front/homePage.Inclusive of VAT') }}
                </p>
            </div>
        </div>

        {{-- Actions --}}
        @if (!request()->routeIs('front.orders.checkout'))
            <div class="pt-4 space-y-3">
                @if ($this->items_total_quantities > 0)
                    <button wire:click="$parent.submit" wire:loading.attr="disabled"
                        class="w-full py-4 bg-primary hover:bg-primaryDark text-white font-bold rounded-2xl shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
                        <span wire:loading.remove>
                            {{ __('front/homePage.Confirm Order') }}
                        </span>
                        <span wire:loading class="material-icons animate-spin">sync</span>
                        <span wire:loading.remove
                            class="material-icons group-hover:translate-x-1 transition-transform rtl:group-hover:-translate-x-1">
                            {{ session('locale') == 'ar' ? 'arrow_back' : 'arrow_forward' }}
                        </span>
                    </button>
                @else
                    <a href="{{ route('front.homepage') }}"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-black py-4 rounded-2xl flex items-center justify-center gap-2 transition-all active:scale-[0.98] group">
                        <span
                            class="material-icons group-hover:-translate-x-1 transition-transform rtl:rotate-180">arrow_back</span>
                        <span class="uppercase tracking-widest">{{ __('front/homePage.Continue Shopping') }}</span>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

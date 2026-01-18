<div class="container py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Form Sections -->
        <div class="lg:col-span-8 space-y-6">
            {{-- Cart Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                @livewire('front.order.order-form.cart-section', [], key('cart-section'))
            </div>

            {{-- Address Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-sm">
                @livewire('front.order.order-form.address-section', [], key('address-section'))
            </div>

            {{-- Phone Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-sm">
                @livewire('front.order.order-form.phone-section', [], key('phone-section'))
            </div>

            {{-- Payment Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                @livewire('front.order.order-form.payment-section', [], key('payment-section'))
            </div>

            {{-- Order Notes Section --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-icons text-primary">notes</span>
                        {{ __('front/homePage.Order Notes') }}
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Notes Textarea --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('front/homePage.Additional Notes') }}
                        </label>
                        <textarea wire:model="notes" id="notes" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none text-sm"
                            placeholder="{{ __('front/homePage.Add any special instructions or notes for your order...') }}"></textarea>
                    </div>

                    {{-- Allow Opening Checkbox --}}
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center h-5">
                            <input wire:model.live="allow_opening" id="allow_opening" type="checkbox"
                                class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <label for="allow_opening" class="text-sm font-medium text-gray-800 cursor-pointer">
                                {{ __('front/homePage.Allow opening package before payment') }}
                            </label>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __('front/homePage.An additional fee will be applied if you choose this option.') }}
                                <span class="font-bold text-primary">
                                    ({{ number_format(config('settings.allow_to_open_package_price', 0), 2) }}
                                    {{ __('front/homePage.EGP') }})
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-4 lg:sticky lg:top-24 self-start space-y-6">
            {{-- Coupon Section --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                @livewire('front.order.order-form.coupon-section', [], key('coupon-section'))
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                @livewire(
                    'front.order.order-form.order-summary',
                    [
                        'items_total_quantities' => $this->items_total_quantities,
                        'items_total_base_prices' => $this->items_total_base_prices,
                        'items_total_discounts' => $this->items_total_discounts,
                        'items_discounts_percentage' => $this->items_discounts_percentage,
                        'offers_total_discounts' => $this->offers_total_discounts,
                        'offers_discounts_percentage' => $this->offers_discounts_percentage,
                        'order_discount' => $this->order_discount,
                        'order_discount_percentage' => $this->order_discount_percentage,
                        'coupon_discount' => $this->coupon_discount,
                        'coupon_discount_percentage' => $this->coupon_discount_percentage,
                        'coupon_items_points' => $this->coupon_items_points,
                        'coupon_order_points' => $this->coupon_order_points,
                        'coupon_free_shipping' => $this->coupon_free_shipping,
                        'total_order_free_shipping' => $this->total_order_free_shipping,
                        'shipping_fees' => $this->shipping_fees,
                        'allow_opening' => $this->allow_opening,
                        'allow_opening_fee' => config('settings.allow_to_open_package_price', 0),
                        'total_after_order_discount' => $this->total_after_order_discount,
                        'total_points_after_order_points' => $this->total_points_after_order_points,
                        'points_egp' => $this->points_egp,
                        'balance_to_use' => $this->balance_to_use,
                        'subtotal_final' => $this->subtotal_final,
                        'is_eligible_for_shipping' => $this->is_eligible_for_shipping,
                        'phone1' => $this->phone1,
                    ],
                    key('order-summary')
                )
            </div>
        </div>
    </div>
</div>

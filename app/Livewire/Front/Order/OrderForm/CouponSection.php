<?php

namespace App\Livewire\Front\Order\OrderForm;

use App\Livewire\Front\Order\OrderForm\Wrapper;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Zone;
use App\Traits\Front\EnrichesCartItems;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CouponSection extends Component
{
    use EnrichesCartItems;

    public $coupon_code;
    public $applied_coupon;

    public function applyCoupon()
    {
        $this->validate([
            'coupon_code' => 'required',
        ]);

        $coupon = Coupon::with('zones')->where('code', $this->coupon_code)->first();

        if (!$coupon) {
            $this->addError('coupon_code', __('front/homePage.Invalid coupon code.'));
            return;
        }

        // Check expiry
        if ($coupon->expire_at && \Carbon\Carbon::parse($coupon->expire_at)->isPast()) {
            $this->addError('coupon_code', __('front/homePage.This coupon has expired.'));
            return;
        }

        // Check usage limit
        if ($coupon->number !== null && $coupon->number <= 0) {
            $this->addError('coupon_code', __('front/homePage.This coupon has reached its usage limit.'));
            return;
        }

        // Check min order price
        if ($coupon->min_order_price) {
            $items = $this->getEnrichedItems('cart');
            $subtotal = collect($items)->sum(function ($item) {
                $qty = $item['cartQty'] ?? 1;
                $price = $item['best_price'] ?? 0;
                return $price * $qty;
            });

            if ($subtotal < $coupon->min_order_price) {
                $this->addError('coupon_code', __('front/homePage.Minimum order price to use this coupon is :min EGP', ['min' => number_format($coupon->min_order_price, 2)]));
                return;
            }
        }

        // Check target zones
        if ($coupon->zones->isNotEmpty()) {
            $userAddress = Auth::check() ? Auth::user()->addresses()->where('default', true)->first() : null;
            $userZoneIds = [];
            if ($userAddress) {
                $userZoneIds = Zone::where('is_active', 1)
                    ->whereHas('destinations', fn($q) => $q->where('city_id', $userAddress->city_id))
                    ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
                    ->pluck('id')
                    ->toArray();
            }

            if (empty($userZoneIds) || $coupon->zones->pluck('id')->intersect($userZoneIds)->isEmpty()) {
                $this->addError('coupon_code', __('front/homePage.This coupon is not valid for your delivery zone'));
                return;
            }
        }

        // Check per-user restriction
        if (Auth::check()) {
            $usedBefore = Order::where('user_id', Auth::id())
                ->where('coupon_id', $coupon->id)
                ->whereNotIn('status_id', [/* IDs for cancelled/failed orders if applicable */])
                ->exists();

            if ($usedBefore) {
                $this->addError('coupon_code', __('front/homePage.You have already used this coupon.'));
                return;
            }
        }

        $this->applied_coupon = $coupon;
        $this->dispatch('couponApplied', $coupon->id)->to(Wrapper::class);
        $this->coupon_code = '';
    }

    public function removeCoupon()
    {
        $this->applied_coupon = null;
        $this->dispatch('couponApplied', null)->to(Wrapper::class);
    }

    public function render()
    {
        return view('livewire.front.order.order-form.coupon-section');
    }
}

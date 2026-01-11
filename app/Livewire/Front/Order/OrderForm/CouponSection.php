<?php

namespace App\Livewire\Front\Order\OrderForm;

use App\Models\Order;

use App\Models\Coupon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Front\Order\OrderForm\Wrapper;

class CouponSection extends Component
{
    public $coupon_code;
    public $applied_coupon;

    public function applyCoupon()
    {
        $this->validate([
            'coupon_code' => 'required',
        ]);

        $coupon = Coupon::where('code', $this->coupon_code)->first();

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

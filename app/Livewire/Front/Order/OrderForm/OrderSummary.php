<?php

namespace App\Livewire\Front\Order\OrderForm;

use Livewire\Component;
use Livewire\Attributes\Reactive;

class OrderSummary extends Component
{
    #[Reactive]
    public $items_total_quantities = 0;
    #[Reactive]
    public $items_total_base_prices = 0;
    #[Reactive]
    public $items_total_discounts = 0;
    #[Reactive]
    public $items_discounts_percentage = 0;
    #[Reactive]
    public $offers_total_discounts = 0;
    #[Reactive]
    public $offers_discounts_percentage = 0;
    #[Reactive]
    public $order_discount = 0;
    #[Reactive]
    public $order_discount_percentage = 0;
    #[Reactive]
    public $total_order_free_shipping = false;
    #[Reactive]
    public $shipping_fees = 0;
    #[Reactive]
    public $total_after_order_discount = 0;
    #[Reactive]
    public $total_points_after_order_points = 0;
    #[Reactive]
    public $points_egp = 0;
    #[Reactive]
    public $balance_to_use = 0;
    #[Reactive]
    public $subtotal_final = 0;
    #[Reactive]
    public $coupon_discount = 0;
    #[Reactive]
    public $coupon_discount_percentage = 0;
    #[Reactive]
    public $coupon_items_points = 0;
    #[Reactive]
    public $coupon_order_points = 0;
    #[Reactive]
    public $allow_opening = false;
    #[Reactive]
    public $allow_opening_fee = 0;

    public function handleCartUpdated()
    {
        // Handled by parent
    }

    public function render()
    {
        return view('livewire.front.order.order-form.order-summary');
    }
}

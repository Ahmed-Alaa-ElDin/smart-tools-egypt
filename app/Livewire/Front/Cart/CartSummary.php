<?php

namespace App\Livewire\Front\Cart;

use App\Models\Offer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CartSummary extends Component
{
    public $items;
    public $items_total_quantities;
    public $items_total_weights;
    public $items_total_base_prices;
    public $items_total_final_prices;
    public $items_total_discounts;
    public $items_discounts_percentage;
    public $total_after_offer_prices;
    public $offers_total_discounts;
    public $offers_discounts_percentage;
    public $order_discount;
    public $order_discount_percent;
    public $order_points;
    public $total_after_order_discount;
    public $offers_free_shipping;
    public $order_offer_free_shipping;
    public $total_order_free_shipping;
    public $items_total_points;
    public $offers_total_points;
    public $after_offers_total_points;
    public $total_points_after_order_points;

    protected $listeners = [
        'cartUpdated' => 'render',
    ];

    public function render()
    {
        $this->items_total_quantities = Cart::instance('cart')->count();

        if ($this->items_total_quantities) {
            // Add Cart Quantity to each item
            $this->items = array_map(function ($item) {
                $cart_item = Cart::instance('cart')->search(function ($cart_item) use ($item) {
                    return $cart_item->id == $item['id'] && $cart_item->options->type == $item['type'];
                })->first();

                $item['after_offer_price'] = $item['final_price'] - $item['offer_discount'];
                $item['qty'] = $cart_item->qty ?? 0;
                $item['total_weight'] = $item['weight'] * $item['qty'];
                $item['total_base_price'] = $item['base_price'] * $item['qty'];
                $item['total_item_discount'] = ($item['base_price'] - $item['final_price']) * $item['qty'];
                $item['total_item_discount_percent'] = $item['base_price'] ? round((($item['base_price'] - $item['final_price']) / $item['base_price']) * 100, 2) : 0;
                $item['total_final_price'] = $item['final_price'] * $item['qty'];
                $item['total_offer_discount'] = $item['offer_discount'] * $item['qty'];
                $item['total_offer_discount_percent'] = $item['final_price'] ? round(($item['offer_discount'] / $item['final_price']) * 100, 2) : 0;
                $item['total_after_offer_price'] = $item['total_final_price'] - $item['total_offer_discount'];
                $item['total_item_points'] =  $item['points'] * $item['qty'];
                $item['total_offer_points'] =  $item['offer_points'] * $item['qty'];
                $item['total_after_offer_points'] =  $item['total_item_points'] + $item['total_offer_points'];

                return $item;
            }, $this->items);

            $this->items_total_weights = array_sum(array_column($this->items, 'total_weight'));

            // Order Offer
            $order_offer = Offer::orderOffers()->first();

            // ------------------------------------------------------------------------------------------------------
            // A - Prices
            // ------------------------------------------------------------------------------------------------------

            // 1 - Base Items Prices
            $this->items_total_base_prices = array_sum(array_column($this->items, 'total_base_price'));
            // 2 - Final Items prices (Base Price - Item Discount)
            $this->items_total_final_prices = array_sum(array_column($this->items, 'total_final_price'));
            $this->items_total_discounts = array_sum(array_column($this->items, 'total_item_discount'));
            $this->items_discounts_percentage = $this->items_total_base_prices ? round(($this->items_total_discounts * 100) / $this->items_total_base_prices, 2) : 0;
            // 3 - After Offers Prices (Final Price - Offers Discount)
            $this->total_after_offer_prices = array_sum(array_column($this->items, 'total_after_offer_price'));
            $this->offers_total_discounts = array_sum(array_column($this->items, 'total_offer_discount'));
            $this->offers_discounts_percentage = $this->items_total_final_prices ? round(($this->offers_total_discounts * 100) / $this->items_total_final_prices, 2) : 0;
            if ($order_offer) {
                // Percent Discount
                if ($order_offer->type == 0 && $order_offer->value <= 100) {
                    $this->order_discount = $this->total_after_offer_prices * ($order_offer->value / 100);
                    $this->order_discount_percent = round($order_offer->value);
                }
                // Fixed Discount
                elseif ($order_offer->type == 1) {
                    $this->order_discount = $this->total_after_offer_prices - $order_offer->value > 0 ? $order_offer->value : $this->total_after_offer_prices;
                    $this->order_discount_percent = $this->total_after_offer_prices ? round(($this->order_discount * 100) / $this->total_after_offer_prices) : 0;
                }
                // Points
                elseif ($order_offer->type == 2) {
                    $this->order_points = $order_offer->value;
                }
            }


            // 5 - Prices After Order Offer
            $this->total_after_order_discount = $this->total_after_offer_prices - $this->order_discount;

            // ------------------------------------------------------------------------------------------------------
            // B - Free Shipping
            // ------------------------------------------------------------------------------------------------------
            // 1 - Items Offers Free Shipping
            $this->offers_free_shipping = !in_array(0, array_column($this->items, 'offer_free_shipping'));

            // 2 - Order Offer Free Shipping
            if ($order_offer) {
                // Order Free Shipping
                $this->order_offer_free_shipping = $order_offer->free_shipping;
            }

            // 3 - Total Order Free Shipping (After Items & Order Offers)
            $this->total_order_free_shipping = $this->offers_free_shipping || $this->order_offer_free_shipping;

            // ------------------------------------------------------------------------------------------------------
            // C - Points
            // ------------------------------------------------------------------------------------------------------

            // 1 - Items Points
            $this->items_total_points = round(array_sum(array_column($this->items, 'total_item_points')), 0);
            // 2 - Offers Points
            $this->offers_total_points = round(array_sum(array_column($this->items, 'total_offer_points')), 0);
            // 3 - Points After Offers (Items Points + Offers Points)
            $this->after_offers_total_points = round(array_sum(array_column($this->items, 'total_after_offer_points')), 0);
            // 4 - Points After Order Points
            $this->total_points_after_order_points = $this->after_offers_total_points + $this->order_points;
        } else {
            $this->items = [];
            $this->items_total_quantities = 0;
            $this->items_total_weights = 0;
            $this->items_total_base_prices = 0;
            $this->items_total_final_prices = 0;
            $this->items_total_discounts = 0;
            $this->items_discounts_percentage = 0;
            $this->total_after_offer_prices = 0;
            $this->offers_total_discounts = 0;
            $this->offers_discounts_percentage = 0;
            $this->order_discount = 0;
            $this->order_discount_percent = 0;
            $this->order_points = 0;
            $this->total_after_order_discount = 0;
            $this->offers_free_shipping = 0;
            $this->order_offer_free_shipping = 0;
            $this->total_order_free_shipping = 0;
            $this->items_total_points = 0;
            $this->offers_total_points = 0;
            $this->after_offers_total_points = 0;
            $this->total_points_after_order_points = 0;
        }

        return view('livewire.front.cart.cart-summary');
    }
}

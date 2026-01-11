<?php

namespace App\Livewire\Front\Cart;

use App\Models\Offer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

use App\Traits\Front\EnrichesCartItems;

class CartSummary extends Component
{
    use EnrichesCartItems;

    public $items = [];

    #[On('cartUpdated')]
    public function handleCartUpdated()
    {
        $this->items = $this->getEnrichedItems('cart');
    }

    #[Computed]
    public function orderOffer()
    {
        return Offer::orderOffers()->first();
    }

    #[Computed]
    public function processedItems()
    {
        if (empty($this->items)) {
            return collect([]);
        }

        $cartContent = Cart::instance('cart')->content();
        $cartLookup = $cartContent->keyBy(fn($cartItem) => $cartItem->id . '-' . ($cartItem->options->type ?? ''));

        return collect($this->items)->map(function ($item) use ($cartLookup) {
            $cartItem = $cartLookup->get($item['id'] . '-' . ($item['type'] ?? ''));
            $qty = $cartItem->qty ?? 0;

            $item['qty'] = $qty;
            $item['after_offer_price'] = ($item['final_price'] ?? 0) - ($item['offer_discount'] ?? 0);
            $item['total_weight'] = ($item['weight'] ?? 0) * $qty;
            $item['total_base_price'] = ($item['base_price'] ?? 0) * $qty;
            $item['total_item_discount'] = (($item['base_price'] ?? 0) - ($item['final_price'] ?? 0)) * $qty;
            $item['total_item_discount_percent'] = ($item['base_price'] ?? 0) ? round(((($item['base_price'] ?? 0) - ($item['final_price'] ?? 0)) / ($item['base_price'] ?? 1)) * 100, 2) : 0;
            $item['total_final_price'] = ($item['final_price'] ?? 0) * $qty;
            $item['total_offer_discount'] = ($item['offer_discount'] ?? 0) * $qty;
            $item['total_offer_discount_percent'] = ($item['final_price'] ?? 0) ? round((($item['offer_discount'] ?? 0) / ($item['final_price'] ?? 1)) * 100, 2) : 0;
            $item['total_after_offer_price'] = $item['total_final_price'] - $item['total_offer_discount'];
            $item['total_item_points'] = ($item['points'] ?? 0) * $qty;
            $item['total_offer_points'] = ($item['offer_points'] ?? 0) * $qty;
            $item['total_after_offer_points'] = $item['total_item_points'] + $item['total_offer_points'];

            return $item;
        });
    }

    #[Computed]
    public function items_total_quantities()
    {
        return Cart::instance('cart')->count();
    }

    #[Computed]
    public function items_total_weights()
    {
        return $this->processedItems->sum('total_weight');
    }

    #[Computed]
    public function items_total_base_prices()
    {
        return $this->processedItems->sum('total_base_price');
    }

    #[Computed]
    public function items_total_final_prices()
    {
        return $this->processedItems->sum('total_final_price');
    }

    #[Computed]
    public function items_total_discounts()
    {
        return $this->processedItems->sum('total_item_discount');
    }

    #[Computed]
    public function items_discounts_percentage()
    {
        return $this->items_total_base_prices ? round(($this->items_total_discounts * 100) / $this->items_total_base_prices, 2) : 0;
    }

    #[Computed]
    public function total_after_offer_prices()
    {
        return $this->processedItems->sum('total_after_offer_price');
    }

    #[Computed]
    public function offers_total_discounts()
    {
        return $this->processedItems->sum('total_offer_discount');
    }

    #[Computed]
    public function offers_discounts_percentage()
    {
        return $this->items_total_final_prices ? round(($this->offers_total_discounts * 100) / $this->items_total_final_prices, 2) : 0;
    }

    #[Computed]
    public function order_discount_details()
    {
        $orderOffer = $this->orderOffer;
        $total = $this->total_after_offer_prices;
        $discount = 0;
        $percent = 0;
        $points = 0;

        if ($orderOffer) {
            if ($orderOffer->type == Offer::TYPE_PERCENTAGE && $orderOffer->value <= 100) {
                $discount = $total * ($orderOffer->value / 100);
                $percent = round($orderOffer->value);
            } elseif ($orderOffer->type == Offer::TYPE_FIXED) {
                $discount = min($orderOffer->value, $total);
                $percent = $total ? round(($discount * 100) / $total) : 0;
            } elseif ($orderOffer->type == Offer::TYPE_POINTS) {
                $points = $orderOffer->value;
            }
        }

        return [
            'discount' => $discount,
            'percent' => $percent,
            'points' => $points,
        ];
    }

    #[Computed]
    public function order_discount()
    {
        return $this->order_discount_details['discount'];
    }

    #[Computed]
    public function order_discount_percentage()
    {
        return $this->order_discount_details['percent'];
    }

    #[Computed]
    public function order_points()
    {
        return $this->order_discount_details['points'];
    }

    #[Computed]
    public function total_after_order_discount()
    {
        return ceil($this->total_after_offer_prices - $this->order_discount);
    }

    #[Computed]
    public function items_free_shipping()
    {
        return $this->processedItems->contains('free_shipping', 1);
    }

    #[Computed]
    public function offers_free_shipping()
    {
        return $this->processedItems->contains('offer_free_shipping', 1);
    }

    #[Computed]
    public function order_offer_free_shipping()
    {
        return $this->orderOffer->free_shipping ?? 0;
    }

    #[Computed]
    public function total_order_free_shipping()
    {
        return $this->items_free_shipping || $this->offers_free_shipping || $this->order_offer_free_shipping;
    }

    #[Computed]
    public function items_total_points()
    {
        return round($this->processedItems->sum('total_item_points'), 0);
    }

    #[Computed]
    public function offers_total_points()
    {
        return round($this->processedItems->sum('total_offer_points'), 0);
    }

    #[Computed]
    public function after_offers_total_points()
    {
        return round($this->processedItems->sum('total_after_offer_points'), 0);
    }

    #[Computed]
    public function total_points_after_order_points()
    {
        return $this->after_offers_total_points + $this->order_points;
    }

    public function render()
    {
        return view('livewire.front.cart.cart-summary');
    }
}

<?php

namespace App\Livewire\Front\Order\Payment;

use App\Models\Coupon;
use App\Models\Zone;
use App\Services\CouponService;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CouponBlock extends Component
{
    public $items,
        $coupon,
        $coupon_id,
        $coupon_free_shipping,
        $products_after_coupon,
        $collections_after_coupon,
        $coupon_items_discount,
        $coupon_items_points,
        $coupon_order_discount,
        $coupon_order_points,
        $products_best_coupon,
        $collections_best_coupon,
        $order_best_coupon,
        $coupon_applied,
        $success_message,
        $error_message;

    public function render()
    {
        return view('livewire.front.order.payment.coupon-block');
    }

    ############## Check Coupon :: Start ##############
    public function checkCoupon()
    {
        $items = $this->items;

        $productsIds = array_map(fn($item) => $item['id'], array_filter($items, fn($item) => $item['type'] == 'Product'));
        $productsQuantities = array_reduce($items, function ($carry, $item) {
            $carry[$item['id']] =  $item['qty'];

            return $carry;
        });

        $products = getBestOfferForProducts($productsIds)?->map(function ($product) use ($productsQuantities) {
            $product->qty = $productsQuantities[$product->id] ?? 0;

            $product->free_shipping = $product->free_shipping || $product->offer_free_shipping;

            $product->after_offer_price = $product->final_price - $product->offer_discount;
            $product->total_weight = $product->weight * $product->qty;
            $product->total_shipping_weight = !$product->free_shipping ? $product->weight * $product->qty : 0;

            $product->total_base_price = $product->base_price * $product->qty;
            $product->total_product_discount = ($product->base_price - $product->final_price) * $product->qty;
            $product->total_product_discount_percent = $product->base_price ? round((($product->base_price - $product->final_price) / $product->base_price) * 100, 2) : 0;

            $product->total_final_price = $product->final_price * $product->qty;
            $product->total_offer_discount = $product->offer_discount * $product->qty;
            $product->total_offer_discount_percent = $product->final_price ? round(($product->offer_discount / $product->final_price) * 100, 2) : 0;

            $product->total_after_offer_price = $product->total_final_price - $product->total_offer_discount;
            $product->total_product_points = $product->points * $product->qty;
            $product->total_offer_points = $product->offer_points * $product->qty;
            $product->total_after_offer_points = $product->total_product_points + $product->total_offer_points;

            $product->coupon_discount = 0;
            $product->coupon_points = 0;

            return $product;
        });

        $collectionsIds = array_map(fn($item) => $item['id'], array_filter($items, fn($item) => $item['type'] == 'Collection'));
        $collectionsQuantities = array_reduce($items, function ($carry, $item) {
            $carry[$item['id']] =  $item['qty'];

            return $carry;
        });

        $collections = getBestOfferForCollections($collectionsIds)?->map(function ($collection) use ($collectionsQuantities) {
            $collection->qty = $collectionsQuantities[$collection->id] ?? 0;

            $collection->free_shipping = $collection->free_shipping || $collection->offer_free_shipping;

            $collection->after_offer_price = $collection->final_price - $collection->offer_discount;
            $collection->total_weight = $collection->weight * $collection->qty;
            $collection->total_shipping_weight = !$collection->free_shipping ? $collection->weight * $collection->qty : 0;

            $collection->total_base_price = $collection->base_price * $collection->qty;
            $collection->total_collection_discount = ($collection->base_price - $collection->final_price) * $collection->qty;
            $collection->total_collection_discount_percent = $collection->base_price ? round((($collection->base_price - $collection->final_price) / $collection->base_price) * 100, 2) : 0;

            $collection->total_final_price = $collection->final_price * $collection->qty;
            $collection->total_offer_discount = $collection->offer_discount * $collection->qty;
            $collection->total_offer_discount_percent = $collection->final_price ? round(($collection->offer_discount / $collection->final_price) * 100, 2) : 0;

            $collection->total_after_offer_price = $collection->total_final_price - $collection->total_offer_discount;
            $collection->total_collection_points =  $collection->points * $collection->qty;
            $collection->total_offer_points =  $collection->offer_points * $collection->qty;
            $collection->total_after_offer_points =  $collection->total_collection_points + $collection->total_offer_points;

            $collection->coupon_discount =  0;
            $collection->coupon_points =  0;

            return $collection;
        });

        $items_best_prices = array_sum(array_map(fn($item) => $item['best_price'] * $item['qty'], $items));

        $coupon = Coupon::with([
            'zones',
            'supercategories' => function ($q) {
                $q->with(['products']);
            },
            'categories' => function ($q) {
                $q->with(['products']);
            },
            'subcategories' => function ($q) {
                $q->with(['products']);
            },
            'brands' => function ($q) {
                $q->with(['products']);
            },
            'products',
            'collections'
        ])
            ->where('code', $this->coupon)
            // date range
            ->where('expire_at', '>=', Carbon::now())
            ->where(fn($q) => $q->where('number', '>', 0)->orWhere('number', null))
            ->first();

        if ($coupon) {
            // Check minimum order price requirement
            if ($coupon->min_order_price && $items_best_prices < $coupon->min_order_price) {
                $this->coupon_applied = false;
                $this->success_message = null;
                $this->error_message = __('front/homePage.Minimum order price to use this coupon is :min EGP', ['min' => number_format($coupon->min_order_price, 2)]);
                return;
            }

            // Check target zones requirement
            if ($coupon->zones->isNotEmpty()) {
                $userAddress = auth()->check() ? auth()->user()->addresses->where('default', 1)->first() : null;
                $userZoneIds = [];
                if ($userAddress) {
                    $userZoneIds = Zone::where('is_active', 1)
                        ->whereHas('destinations', fn($q) => $q->where('city_id', $userAddress->city_id))
                        ->whereHas('delivery', fn($q) => $q->where('is_active', 1))
                        ->pluck('id')
                        ->toArray();
                }

                if (empty($userZoneIds) || $coupon->zones->pluck('id')->intersect($userZoneIds)->isEmpty()) {
                    $this->coupon_applied = false;
                    $this->success_message = null;
                    $this->error_message = __('front/homePage.This coupon is not valid for your delivery zone');
                    return;
                }
            }

            $this->coupon_id = $coupon->id;

            $couponDiscounts = (new CouponService($products, $collections))->calculateDiscount($coupon->id, $items_best_prices);

            $this->products_best_coupon = $couponDiscounts['products_best_coupon'];
            $this->collections_best_coupon = $couponDiscounts['collections_best_coupon'];
            $this->coupon_items_discount = $couponDiscounts['coupon_items_discount'];
            $this->coupon_items_points = $couponDiscounts['coupon_items_points'];
            $this->coupon_order_discount = $couponDiscounts['coupon_order_discount'];
            $this->coupon_order_points = $couponDiscounts['coupon_order_points'];
            $this->coupon_free_shipping = $couponDiscounts['coupon_free_shipping'];

            $this->coupon_applied = true;

            $this->success_message = __('front/homePage.Coupon applied successfully', ['coupon' => $coupon->code]);
            $this->error_message = null;

            $this->dispatch(
                'swalDone',
                text: $this->success_message,
                icon: 'success'
            );

            $this->dispatch(
                'couponApplied',
                $this->coupon_id,
                $this->products_best_coupon,
                $this->collections_best_coupon,
                $this->coupon_items_discount,
                $this->coupon_items_points,
                $this->coupon_order_discount,
                $this->coupon_order_points,
                $this->coupon_free_shipping,
            );
        } else {
            $this->coupon_applied = false;
            $this->success_message = null;
            $this->error_message = __('front/homePage.Invalid coupon code or expired');
        }
    }
    ############## Check Coupon :: End ##############

    ############## Remove Coupon :: Start ##############
    public function removeCoupon()
    {
        $this->coupon = null;
        $this->coupon_id = null;
        $this->coupon_free_shipping = false;
        $this->products_after_coupon = [];
        $this->collections_after_coupon = [];
        $this->coupon_items_discount = 0;
        $this->coupon_items_points = 0;
        $this->products_best_coupon = [];
        $this->collections_best_coupon = [];
        $this->order_best_coupon = [
            'type' => null,
            'value' => 0,
        ];
        $this->coupon_applied = false;
        $this->success_message = null;
        $this->error_message = null;

        $this->dispatch(
            'swalDone',
            text: __('front/homePage.Coupon removed successfully'),
            icon: 'warning'
        );

        $this->dispatch(
            'couponApplied',
            null,
            [],
            [],
            0,
            0,
            false,
            0,
            false
        );
    }
}

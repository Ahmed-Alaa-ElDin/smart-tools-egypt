<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Coupon;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CouponBlock extends Component
{
    public $coupon,
        $coupon_id,
        $products,
        $products_best_prices,
        $total_points,
        $free_shipping,
        $coupon_price,
        $coupon_points,
        $coupon_shipping,
        $coupon_applied = false,
        $success_message,
        $error_message;

    public function render()
    {
        return view('livewire.front.order.coupon-block');
    }

    ############## Check Coupon :: Start ##############
    public function checkCoupon()
    {
        $this->coupon_price = $this->products_best_prices;
        $this->coupon_points = $this->total_points;
        $this->coupon_shipping = $this->free_shipping;

        $products_ids = $this->products->pluck('id')->unique()->toArray();

        $coupon = Coupon::with([
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
        ])
            ->where('code', $this->coupon)
            ->whereRaw("expire_at > STR_TO_DATE(?, '%Y-%m-%d %H:%i:%s')", Carbon::now('Africa/Cairo')->format('Y-m-d H:i'))
            ->where(fn ($q) => $q->where('number', '>', 0)->orWhere('number', null))
            ->first();

        if ($coupon) {
            $this->coupon_id = $coupon->id;

            // get discount on order
            if ($coupon->on_orders) {
                // percentage discount
                if ($coupon->type == 0 && $coupon->value < 100) {
                    $discount_order_from_coupon = $this->coupon_price * $coupon->value / 100;
                    $this->coupon_price -= $discount_order_from_coupon;
                }
                // fixed discount
                elseif ($coupon->type == 1) {
                    $discount_order_from_coupon = $coupon->value;
                    $this->coupon_price = $discount_order_from_coupon <= $this->coupon_price ? $this->coupon_price - $discount_order_from_coupon : 0;
                }
                // points
                elseif ($coupon->type == 2) {
                    $points_from_coupon = $coupon->value;
                    $this->coupon_points += $points_from_coupon;
                }
            }

            // get free shipping
            $this->coupon_shipping = $coupon->free_shipping ? true : false;

            // get discount on brands
            if ($coupon->brands->count()) {
                $brands_product_from_coupon = $coupon->brands->map(function ($brand) use ($products_ids) {
                    return [
                        'products' => $brand->products->whereIn('id', $products_ids),
                        'type' => $brand->pivot->type,
                        'value' => $brand->pivot->value,
                    ];
                });


                $brands_product_from_coupon->map(function ($brand) {
                    foreach ($brand['products'] as $product) {
                        $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                            return $cartItem->id === $product->id;
                        })->first()->qty;

                        if ($brand['type'] == 0 && $brand['value'] < 100) {
                            $discount_brand_product = $product_qty * ($product->final_price * $brand['value'] / 100);
                            $this->coupon_price -= $discount_brand_product;
                        } elseif ($brand['type'] == 1) {
                            $discount_brand_product = $product_qty * $brand['value'];
                            $this->coupon_price = $discount_brand_product  <= $this->coupon_price ? $this->coupon_price - $discount_brand_product : 0;
                        } elseif ($brand['type'] == 2) {
                            $points_brand_product = $product_qty * $brand['value'];
                            $this->coupon_points += $points_brand_product;
                        }
                    }
                });
            }

            // get discount on subcategories
            if ($coupon->subcategories->count()) {
                $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products_ids) {
                    return [
                        'products' => $subcategory->products->whereIn('id', $products_ids),
                        'type' => $subcategory->pivot->type,
                        'value' => $subcategory->pivot->value,
                    ];
                });

                $subcategories_product_from_coupon->map(function ($subcategory) {
                    foreach ($subcategory['products'] as $product) {
                        $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                            return $cartItem->id === $product->id;
                        })->first()->qty;

                        if ($subcategory['type'] == 0 && $subcategory['value'] < 100) {
                            $discount_subcategory_product = $product_qty * ($product->final_price * $subcategory['value'] / 100);
                            $this->coupon_price -= $discount_subcategory_product;
                        } elseif ($subcategory['type'] == 1) {
                            $discount_subcategory_product = $product_qty * $subcategory['value'];
                            $this->coupon_price = $discount_subcategory_product  <= $this->coupon_price ? $this->coupon_price - $discount_subcategory_product : 0;
                        } elseif ($subcategory['type'] == 2) {
                            $points_subcategory_product = $product_qty * $subcategory['value'];
                            $this->coupon_points += $points_subcategory_product;
                        }
                    }
                });
            }

            // get discount on categories
            if ($coupon->categories->count()) {
                $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products_ids) {
                    return [
                        'products' => $category->products->whereIn('id', $products_ids),
                        'type' => $category->pivot->type,
                        'value' => $category->pivot->value,
                    ];
                });

                $categories_product_from_coupon->map(function ($category) {
                    foreach ($category['products'] as $product) {
                        $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                            return $cartItem->id === $product->id;
                        })->first()->qty;

                        if ($category['type'] == 0 && $category['value'] < 100) {
                            $discount_category_product = $product_qty * ($product->final_price * $category['value'] / 100);
                            $this->coupon_price -= $discount_category_product;
                        } elseif ($category['type'] == 1) {
                            $discount_category_product = $product_qty * $category['value'];
                            $this->coupon_price = $discount_category_product  <= $this->coupon_price ? $this->coupon_price - $discount_category_product : 0;
                        } elseif ($category['type'] == 2) {
                            $points_category_product = $product_qty * $category['value'];
                            $this->coupon_points += $points_category_product;
                        }
                    }
                });
            }

            // get discount on supercategories
            if ($coupon->supercategories->count()) {
                $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products_ids) {
                    return [
                        'products' => $supercategory->products->whereIn('id', $products_ids),
                        'type' => $supercategory->pivot->type,
                        'value' => $supercategory->pivot->value,
                    ];
                });

                $supercategories_product_from_coupon->map(function ($supercategory) {
                    foreach ($supercategory['products'] as $product) {
                        $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                            return $cartItem->id === $product->id;
                        })->first()->qty;

                        if ($supercategory['type'] == 0 && $supercategory['value'] < 100) {
                            $discount_supercategory_product = $product_qty * ($product->final_price * $supercategory['value'] / 100);
                            $this->coupon_price -= $discount_supercategory_product;
                        } elseif ($supercategory['type'] == 1) {
                            $discount_supercategory_product = $product_qty * $supercategory['value'];
                            $this->coupon_price = $discount_supercategory_product  <= $this->coupon_price ? $this->coupon_price - $discount_supercategory_product : 0;
                        } elseif ($supercategory['type'] == 2) {
                            $points_supercategory_product = $product_qty * $supercategory['value'];
                            $this->coupon_points += $points_supercategory_product;
                        }
                    }
                });
            }

            // get discount on products
            if ($coupon->products->count()) {
                $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_ids) {
                    if (in_array($product->id, $products_ids)) {
                        return [
                            'product' => $product,
                            'type' => $product->pivot->type,
                            'value' => $product->pivot->value,
                        ];
                    }
                })->whereNotNull();

                $products_product_from_coupon->map(function ($product) {
                    $product_qty = Cart::instance('cart')->search(function ($cartItem, $rowId) use ($product) {
                        return $cartItem->id === $product['product']->id;
                    })->first()->qty;

                    if ($product['type'] == 0  && $product['value'] < 100) {
                        $discount_product = $product_qty * ($product['product']->final_price * $product['value'] / 100);
                        $this->coupon_price -= $discount_product;
                    } elseif ($product['type'] == 1) {
                        $discount_product = $product_qty * $product['value'];
                        $this->coupon_price = $discount_product  <= $this->coupon_price ? $this->coupon_price - $discount_product : 0;
                    } elseif ($product['type'] == 2) {
                        $points_product = $product_qty * $product['value'];
                        $this->coupon_points += $points_product;
                    }
                });
            }

            $this->coupon_applied = true;
            $this->success_message = __('front/homePage.Coupon applied successfully', ['coupon' => $coupon->code]);
            $this->dispatchBrowserEvent('swalNotification', [
                "text" => $this->success_message,
                'icon' => 'success'
            ]);
            $this->error_message = null;

            $this->emit('couponApplied', $this->coupon_id, $this->coupon_price, $this->coupon_points, $this->coupon_shipping);
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
        $this->coupon_applied = false;
        $this->success_message = null;
        $this->error_message = null;

        $this->dispatchBrowserEvent('swalNotification', [
            "text" => __('front/homePage.Coupon removed successfully'),
            'icon' => 'warning'
        ]);
        $this->error_message = null;

        $this->emit('couponApplied', null, null, null, null);
    }
}

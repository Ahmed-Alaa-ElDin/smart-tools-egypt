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
        $total,
        $points,
        $free_shipping,
        $coupon_discount = 0,
        $coupon_points = 0,
        $coupon_free_shipping = false,
        $coupon_applied = false,
        $success_message,
        $error_message,
        $products_after_coupon = [],
        $order_best_coupon = [
            'discount' => 0.00,
            'points' => 0
        ],
        $products_best_coupon = [];


    public function render()
    {
        return view('livewire.front.order.coupon-block');
    }

    ############## Check Coupon :: Start ##############
    public function checkCoupon()
    {
        $products_ids = $this->products->pluck('id')->unique()->toArray();

        $products = getBestOfferForProducts($products_ids);

        $product_qty = Cart::instance('cart')->content()->pluck('qty', 'id')->toArray();

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
            // date range
            ->where('expire_at', '>=', Carbon::now())
            ->where(fn ($q) => $q->where('number', '>', 0)->orWhere('number', null))
            ->first();

        if ($coupon) {
            $this->coupon_id = $coupon->id;

            // get free shipping
            $this->coupon_free_shipping = $coupon->free_shipping ? true : false;

            // get discount on brands
            if ($coupon->brands->count()) {
                $brands_product_from_coupon = $coupon->brands->map(function ($brand) use ($products) {
                    return [
                        'products' => $products->whereIn('id', $brand->products->pluck('id')),
                        'type' => $brand->pivot->type,
                        'value' => $brand->pivot->value,
                    ];
                });

                $brands_product_from_coupon->map(function ($brand) {
                    foreach ($brand['products'] as $product) {

                        if ($brand['type'] == 0 && $brand['value'] < 100) {
                            $product->coupon_discount = $product->best_price * $brand['value'] / 100;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($brand['type'] == 1) {
                            $product->coupon_discount = $brand['value'] <= $product->best_price ? $brand['value'] : $product->best_price;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($brand['type'] == 2) {
                            $product->coupon_discount = 0.00;
                            $product->coupon_points = $brand['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on subcategories
            if ($coupon->subcategories->count()) {
                $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products) {
                    return [
                        'products' => $products->whereIn('id', $subcategory->products->pluck('id')),
                        'type' => $subcategory->pivot->type,
                        'value' => $subcategory->pivot->value,
                    ];
                });

                $subcategories_product_from_coupon->map(function ($subcategory) {
                    foreach ($subcategory['products'] as $product) {
                        if ($subcategory['type'] == 0 && $subcategory['value'] < 100) {
                            $product->coupon_discount = $product->best_price * $subcategory['value'] / 100;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($subcategory['type'] == 1) {
                            $product->coupon_discount = $subcategory['value'] <= $product->best_price ? $subcategory['value'] : $product->best_price;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($subcategory['type'] == 2) {
                            $product->coupon_discount = 0.00;
                            $product->coupon_points = $subcategory['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on categories
            if ($coupon->categories->count()) {
                $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products) {
                    return [
                        'products' => $products->whereIn('id', $category->products->pluck('id')),
                        'type' => $category->pivot->type,
                        'value' => $category->pivot->value,
                    ];
                });

                $categories_product_from_coupon->map(function ($category) {
                    foreach ($category['products'] as $product) {
                        if ($category['type'] == 0 && $category['value'] < 100) {
                            $product->coupon_discount = $product->best_price * $category['value'] / 100;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($category['type'] == 1) {
                            $product->coupon_discount = $category['value'] <= $product->best_price ? $category['value'] : $product->best_price;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($category['type'] == 2) {
                            $product->coupon_discount = 0.00;
                            $product->coupon_points = $category['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on supercategories
            if ($coupon->supercategories->count()) {
                $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products) {
                    return [
                        'products' => $products->whereIn('id', $supercategory->products->pluck('id')),
                        'type' => $supercategory->pivot->type,
                        'value' => $supercategory->pivot->value,
                    ];
                });

                $supercategories_product_from_coupon->map(function ($supercategory) {
                    foreach ($supercategory['products'] as $product) {

                        if ($supercategory['type'] == 0 && $supercategory['value'] < 100) {
                            $product->coupon_discount = $product->best_price * $supercategory['value'] / 100;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($supercategory['type'] == 1) {
                            $product->coupon_discount = $supercategory['value'] <= $product->best_price ? $supercategory['value'] : $product->best_price;
                            $product->coupon_points = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($supercategory['type'] == 2) {
                            $product->coupon_discount = 0.00;
                            $product->coupon_points = $supercategory['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on products
            if ($coupon->products->count()) {
                $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_ids, $products) {
                    if (in_array($product->id, $products_ids)) {
                        return [
                            'product' => $products->find($product->id),
                            'type' => $product->pivot->type,
                            'value' => $product->pivot->value,
                        ];
                    }
                })->whereNotNull();

                $products_product_from_coupon->map(function ($product) {

                    if ($product['type'] == 0 && $product['value'] < 100) {
                        $product['product']->coupon_discount = $product['product']->best_price * $product['value'] / 100;
                        $product['product']->coupon_points = 0;
                        $this->products_after_coupon[] = $product;
                    } elseif ($product['type'] == 1) {
                        $product['product']->coupon_discount = $product['value'] <= $product['product']->best_price ? $product['value'] : $product['product']->best_price;
                        $product['product']->coupon_points = 0;
                        $this->products_after_coupon[] = $product['product'];
                    } elseif ($product['type'] == 2) {
                        $product['product']->coupon_discount = 0.00;
                        $product['product']->coupon_points = $product['value'];
                        $this->products_after_coupon[] = $product['product'];
                    }
                });
            }

            // Final Products After Coupon Application
            $this->products_best_coupon = collect($this->products_after_coupon)
                ->groupBy('id')
                ->map(function ($products) use ($product_qty) {
                    $max_discount = $products->max('coupon_discount');
                    $max_points = $products->max('coupon_points');
                    $product_qty = $product_qty[$products->first()->id];
                    return [
                        'product' => $products->first()->id,
                        'qty' => $product_qty,
                        'coupon_discount' => $max_discount,
                        'total_discount' =>  $product_qty * $max_discount,
                        'coupon_points' => $max_points,
                        'total_points' => $product_qty * $max_points,
                    ];
                });

            // Total Coupon Discount After Products
            $this->coupon_discount = $this->products_best_coupon->sum('total_discount');
            // Total Coupon Points After Products
            $this->coupon_points = $this->products_best_coupon->sum('total_points');

            // get discount on order
            if ($coupon->on_orders) {
                // percentage discount
                if ($coupon->type == 0 && $coupon->value < 100) {
                    $discount_order_from_coupon = ($this->total - $this->coupon_discount) * $coupon->value / 100;
                    $this->order_best_coupon = [
                        'discount' => $discount_order_from_coupon,
                        'points' => 0
                    ];
                    $this->coupon_discount += $discount_order_from_coupon;
                }
                // fixed discount
                elseif ($coupon->type == 1) {
                    $discount_order_from_coupon = $coupon->value <= ($this->total - $this->coupon_discount) ? $coupon->value : ($this->total - $this->coupon_discount);
                    $this->order_best_coupon = [
                        'discount' => $discount_order_from_coupon,
                        'points' => 0
                    ];
                    $this->coupon_discount += $discount_order_from_coupon <= ($this->total - $this->coupon_discount) ? $discount_order_from_coupon : ($this->total - $this->coupon_discount);
                }
                // points
                elseif ($coupon->type == 2) {
                    $points_from_coupon = $coupon->value;
                    $this->order_best_coupon = [
                        'discount' => 0.00,
                        'points' => $points_from_coupon
                    ];
                    $this->coupon_points += $points_from_coupon;
                }
            }

            $this->coupon_discount_percentage = round($this->coupon_discount / $this->total * 100);

            $this->coupon_applied = true;

            $this->success_message = __('front/homePage.Coupon applied successfully', ['coupon' => $coupon->code]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => $this->success_message,
                'icon' => 'success'
            ]);

            $this->error_message = null;

            $this->emit(
                'couponApplied',
                $this->coupon_id,
                $this->coupon_discount,
                $this->coupon_discount_percentage,
                $this->coupon_points,
                $this->coupon_free_shipping,
                $this->products_best_coupon,
                $this->order_best_coupon
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
        $this->coupon_applied = false;
        $this->success_message = null;
        $this->error_message = null;
        $this->coupon_discount = 0;
        $this->coupon_points = 0;
        $this->coupon_free_shipping = false;
        $this->products_after_coupon = [];
        $this->order_best_coupon = [
            'discount' => 0.00,
            'points' => 0
        ];
        $this->products_best_coupon = [];

        $this->dispatchBrowserEvent('swalDone', [
            "text" => __('front/homePage.Coupon removed successfully'),
            'icon' => 'warning'
        ]);
        $this->error_message = null;

        $this->emit(
            'couponApplied',
            null,
            null,
            null,
            null,
            null,
            [],
            [
                'discount' => 0.00,
                'points' => 0
            ]
        );
    }
}

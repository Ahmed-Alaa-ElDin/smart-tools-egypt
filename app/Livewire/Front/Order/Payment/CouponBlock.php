<?php

namespace App\Livewire\Front\Order\Payment;

use App\Models\Coupon;
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

        $products = array_filter($items, fn ($item) => $item['type'] == 'Product');

        $collections = array_filter($items, fn ($item) => $item['type'] == 'Collection');

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
            'collections'
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
                        'products' => array_filter($products, fn ($product) => in_array($product['id'], $brand->products->pluck('id')->toArray())),
                        'type' => $brand->pivot->type,
                        'value' => $brand->pivot->value,
                    ];
                });

                $brands_product_from_coupon->map(function ($brand) {
                    foreach ($brand['products'] as $product) {

                        if ($brand['type'] == 0 && $brand['value'] <= 100) {
                            $product['coupon_discount'] = $product['best_price'] * $brand['value'] / 100;
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($brand['type'] == 1) {
                            $product['coupon_discount'] = $brand['value'] <= $product['best_price'] ? $brand['value'] : $product['best_price'];
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($brand['type'] == 2) {
                            $product['coupon_discount'] = 0.00;
                            $product['coupon_points'] = $brand['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on subcategories
            if ($coupon->subcategories->count()) {
                $subcategories_product_from_coupon = $coupon->subcategories->map(function ($subcategory) use ($products) {
                    return [
                        'products' => array_filter($products, fn ($product) => in_array($product['id'], $subcategory->products->pluck('id')->toArray())),
                        'type' => $subcategory->pivot->type,
                        'value' => $subcategory->pivot->value,
                    ];
                });

                $subcategories_product_from_coupon->map(function ($subcategory) {
                    foreach ($subcategory['products'] as $product) {
                        if ($subcategory['type'] == 0 && $subcategory['value'] <= 100) {
                            $product['coupon_discount'] = $product['best_price'] * $subcategory['value'] / 100;
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($subcategory['type'] == 1) {
                            $product['coupon_discount'] = $subcategory['value'] <= $product['best_price'] ? $subcategory['value'] : $product['best_price'];
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($subcategory['type'] == 2) {
                            $product['coupon_discount'] = 0.00;
                            $product['coupon_points'] = $subcategory['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on categories
            if ($coupon->categories->count()) {
                $categories_product_from_coupon = $coupon->categories->map(function ($category) use ($products) {
                    return [
                        'products' => array_filter($products, fn ($product) => in_array($product['id'], $category->products->pluck('id')->toArray())),
                        'type' => $category->pivot->type,
                        'value' => $category->pivot->value,
                    ];
                });

                $categories_product_from_coupon->map(function ($category) {
                    foreach ($category['products'] as $product) {
                        if ($category['type'] == 0 && $category['value'] <= 100) {
                            $product['coupon_discount'] = $product['best_price'] * $category['value'] / 100;
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($category['type'] == 1) {
                            $product['coupon_discount'] = $category['value'] <= $product['best_price'] ? $category['value'] : $product['best_price'];
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($category['type'] == 2) {
                            $product['coupon_discount'] = 0.00;
                            $product['coupon_points'] = $category['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on supercategories
            if ($coupon->supercategories->count()) {
                $supercategories_product_from_coupon = $coupon->supercategories->map(function ($supercategory) use ($products) {
                    return [
                        'products' => array_filter($products, fn ($product) => in_array($product['id'], $supercategory->products->pluck('id')->toArray())),
                        'type' => $supercategory->pivot->type,
                        'value' => $supercategory->pivot->value,
                    ];
                });

                $supercategories_product_from_coupon->map(function ($supercategory) {
                    foreach ($supercategory['products'] as $product) {
                        if ($supercategory['type'] == 0 && $supercategory['value'] <= 100) {
                            $product['coupon_discount'] = $product['best_price'] * $supercategory['value'] / 100;
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($supercategory['type'] == 1) {
                            $product['coupon_discount'] = $supercategory['value'] <= $product['best_price'] ? $supercategory['value'] : $product['best_price'];
                            $product['coupon_points'] = 0;
                            $this->products_after_coupon[] = $product;
                        } elseif ($supercategory['type'] == 2) {
                            $product['coupon_discount'] = 0.00;
                            $product['coupon_points'] = $supercategory['value'];
                            $this->products_after_coupon[] = $product;
                        }
                    }
                });
            }

            // get discount on products
            if ($coupon->products->count()) {
                $products_ids = array_column($products, 'id');

                $products_product_from_coupon = $coupon->products->map(function ($product) use ($products_ids, $products) {
                    if (in_array($product->id, $products_ids)) {
                        return [
                            'product' => array_values(array_filter($products, fn ($o_product) => $product['id'] == $o_product['id']))[0],
                            'type' => $product->pivot->type,
                            'value' => $product->pivot->value,
                        ];
                    }
                })->whereNotNull();

                $products_product_from_coupon->map(function ($product) {
                    if ($product['type'] == 0 && $product['value'] <= 100) {
                        $product['product']['coupon_discount'] = $product['product']['best_price'] * $product['value'] / 100;
                        $product['product']['coupon_points'] = 0;
                        $this->products_after_coupon[] = $product['product'];
                    } elseif ($product['type'] == 1) {
                        $product['product']['coupon_discount'] = $product['value'] <= $product['product']['best_price'] ? $product['value'] : $product['product']['best_price'];
                        $product['product']['coupon_points'] = 0;
                        $this->products_after_coupon[] = $product['product'];
                    } elseif ($product['type'] == 2) {
                        $product['product']['coupon_discount'] = 0.00;
                        $product['product']['coupon_points'] = $product['value'];
                        $this->products_after_coupon[] = $product['product'];
                    }
                });
            }


            // Final Products After Coupon Application
            $this->products_best_coupon = collect($this->products_after_coupon)
                ->groupBy('id')
                ->map(function ($products) {
                    $max_discount = $products->max('coupon_discount');
                    $max_points = $products->max('coupon_points');
                    $product_qty = $products->first()['qty'];
                    $product_id = $products->first()['id'];
                    return [
                        'product' => $product_id,
                        'qty' => $product_qty,
                        'coupon_discount' => $max_discount,
                        'total_discount' =>  $product_qty * $max_discount,
                        'coupon_points' => $max_points,
                        'total_points' => $product_qty * $max_points,
                    ];
                });

            // get discount on collections
            if ($coupon->collections->count()) {
                $collections_ids = array_column($collections, 'id');

                $collections_collection_from_coupon = $coupon->collections->map(function ($collection) use ($collections_ids, $collections) {
                    if (in_array($collection->id, $collections_ids)) {
                        return [
                            'collection' => array_values(array_filter($collections, fn ($o_collection) => $collection['id'] == $o_collection['id']))[0],
                            'type' => $collection->pivot->type,
                            'value' => $collection->pivot->value,
                        ];
                    }
                })->whereNotNull();

                $collections_collection_from_coupon->map(function ($collection) {
                    if ($collection['type'] == 0 && $collection['value'] <= 100) {
                        $collection['collection']['coupon_discount'] = $collection['collection']['best_price'] * $collection['value'] / 100;
                        $collection['collection']['coupon_points'] = 0;
                        $this->collections_after_coupon[] = $collection['collection'];
                    } elseif ($collection['type'] == 1) {
                        $collection['collection']['coupon_discount'] = $collection['value'] <= $collection['collection']['best_price'] ? $collection['value'] : $collection['collection']['best_price'];
                        $collection['collection']['coupon_points'] = 0;
                        $this->collections_after_coupon[] = $collection['collection'];
                    } elseif ($collection['type'] == 2) {
                        $collection['collection']['coupon_discount'] = 0.00;
                        $collection['collection']['coupon_points'] = $collection['value'];
                        $this->collections_after_coupon[] = $collection['collection'];
                    }
                });
            }

            // Final Products After Coupon Application
            $this->collections_best_coupon = collect($this->collections_after_coupon)
                ->groupBy('id')
                ->map(function ($collections) {
                    $max_discount = $collections->max('coupon_discount');
                    $max_points = $collections->max('coupon_points');
                    $collection_qty = $collections->first()['qty'];
                    $collection_id = $collections->first()['id'];
                    return [
                        'collection_id' => $collection_id,
                        'qty' => $collection_qty,
                        'coupon_discount' => $max_discount,
                        'total_discount' =>  $collection_qty * $max_discount,
                        'coupon_points' => $max_points,
                        'total_points' => $collection_qty * $max_points,
                    ];
                });

            // Total Coupon Discount After Products
            $this->coupon_items_discount = $this->products_best_coupon->sum('total_discount') + $this->collections_best_coupon->sum('total_discount');
            // Total Coupon Points After Products
            $this->coupon_items_points = $this->products_best_coupon->sum('total_points') + $this->collections_best_coupon->sum('total_points');

            // get discount on order
            if ($coupon->on_orders) {
                $this->order_best_coupon = [
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                ];
            } else {
                $this->order_best_coupon = [
                    'type' => null,
                    'value' => 0,
                ];
            }

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
                $this->coupon_free_shipping,
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
            [
                'type' => null,
                'value' => 0,
            ]
        );
    }
}

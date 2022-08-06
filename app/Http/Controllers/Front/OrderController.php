<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Zone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::with([
            'products' => function ($query) {
                $query->with([
                    'reviews',
                    'thumbnail',
                    'brand',
                    'subcategories' => function ($query) {
                        $query->with([
                            'category' => function ($query) {
                                $query->with('supercategory');
                            }
                        ]);
                    },
                ]);
            },
            'status'
        ])
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('front.orders.index', compact('orders'));
    }

    public function edit($order_id)
    {
        $order = Order::with([
            'products' => function ($query) {
                $query->select([
                    'products.id',
                    'name',
                    'slug',
                    'products.quantity',
                    'base_price',
                    'final_price',
                    'products.points',
                    'free_shipping',
                    'under_reviewing',
                    'brand_id',
                ])
                    ->with([
                        'thumbnail',
                    ]);
            },
        ])
            ->findOrFail($order_id);

        return view('front.orders.edit', compact('order'));
    }

    public function update(Request $request, $order_id)
    {
        // products ids
        $products_ids = $request->products_ids;

        // Get Order data
        $order = Order::findOrFail($order_id);

        // Get Zone data
        $zone = $order->zone;

        // Get Order Products ids and quantities from request
        $products_quantities = array_combine($request->products_ids, $request->quantities);
        $products_total_quantities = array_sum($products_quantities);

        // Get Order Products ids and quantities from Order DB
        $old_products = $order->products;

        // Get best offer for each product
        $best_products = getBestOfferForProducts($request->products_ids);

        // Get Order Products ids and weights from database
        $products_weights = $best_products->where('free_shipping', 0)->pluck('weight', 'id');

        // Get Order Products total weight
        $total_weight = 0;

        foreach ($products_weights as $id => $weight) {
            if (isset($products_quantities[$id])) {
                $total_weight += $weight * $products_quantities[$id];
            }
        }

        // Get summation of products base prices
        $products_base_prices = $best_products->sum(function ($product) use ($products_quantities) {
            return $product->base_price * $products_quantities[$product->id];
        });

        // Get summation of products final prices
        $products_final_prices = $best_products->sum(function ($product) use ($products_quantities) {
            return $product->final_price * $products_quantities[$product->id];
        });

        // Get summation of products best prices
        $products_best_prices = $best_products->sum(function ($product) use ($products_quantities) {
            return $product->best_price * $products_quantities[$product->id];
        });

        // Get summation of products best points
        $products_best_points = $best_products->sum(function ($product) use ($products_quantities) {
            return $product->best_points * $products_quantities[$product->id];
        });

        // Get products discounts value
        $products_discounts = $products_base_prices - $products_final_prices;

        // Get products discounts percentage
        $products_discounts_percentage = round(($products_discounts / $products_base_prices) * 100, 0);

        // Get offers discounts value
        $offers_discounts = $products_final_prices - $products_best_prices;

        // Get offers discounts percentage
        $offers_discounts_percentage = round(($offers_discounts / $products_final_prices) * 100, 0);

        // Get shipping cost
        $delivery_fees = $total_weight < $zone->min_weight ? $zone->min_charge : $zone->min_charge + ($total_weight - $zone->min_weight) * $zone->kg_charge;

        // Get orders discounts value
        $order_offer = Offer::orderOffers()->first();

        if ($order_offer) {
            // Percent Discount
            if ($order_offer->type == 0 && $order_offer->value <= 100) {
                $order_discount = $products_best_prices * ($order_offer->value / 100);
                $best_price_from_orders = $products_best_prices - $order_discount;
                $order_discount_percent = round($order_offer->value);
            }
            // Fixed Discount
            elseif ($order_offer->type == 1) {
                $best_price_from_orders = $products_best_prices - $order_offer->value > 0 ? $products_best_prices - $order_offer->value : 0.00;
                $order_discount = $order_offer->value;
                $order_discount_percent = round(($order_discount * 100) / $products_best_prices);
            }
            // Points
            elseif ($order_offer->type == 2) {
                $order_points = $order_offer->value;
            }
            // Shipping
            $delivery_fees = $order_offer->free_shipping ? 0 : $delivery_fees;
        }

        // Get Total Points
        $total_points = isset($order_points) ? $products_best_points + $order_points : $products_best_points;

        //  get coupon data
        if ($order->coupon_id) {
            $coupon = $order->coupon;

            $coupon_data = getCoupon($coupon, $products_ids, $products_quantities, $products_best_prices);
        }
        $coupon_discount = $order->coupon_id ? $coupon_data['coupon_discount'] : 0;
        $coupon_discount_percentage = $order->coupon_id ? $coupon_data['coupon_discount_percentage'] : 0;
        $coupon_points = $order->coupon_id ? $coupon_data['coupon_points'] : 0;
        $coupon_shipping = $coupon_data['coupon_shipping'];

        // Get Used Balance
        $used_balance = $order->used_balance;

        // Get Used Points
        $used_points = $order->used_points;
        $used_points_egp = $used_points * config('constants.constants.POINT_RATE');

        $delivery_fees = $order->coupon_id && $coupon_shipping === 0 ? 0.00 : $delivery_fees;

        $order_data = [
            'order_id' => $order->id,
            'products_base_prices' => $products_base_prices,
            'products_final_prices' => $products_final_prices,
            'products_best_prices' => $products_best_prices,
            'products_discounts' => $products_discounts,
            'products_discounts_percentage' => $products_discounts_percentage,
            'offers_discounts' => $offers_discounts,
            'offers_discounts_percentage' => $offers_discounts_percentage,
            'total_points' => $total_points,
            'coupon_discount' => $coupon_discount,
            'coupon_discount_percentage' => $coupon_discount_percentage,
            'coupon_points' => $coupon_points,
            'delivery_fees' => $delivery_fees,
            'used_balance' => $used_balance,
            'used_points' => $used_points,
            'used_points_egp' => $used_points_egp,
            'products_total_quantities' => $products_total_quantities,
            'order_total' => round($products_best_prices + $delivery_fees - $used_balance - $used_points_egp - $coupon_discount, 2),
        ];

        // dd($order_new_data);

        return view('front.orders.edit_preview', compact('order_data'));
        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    public function shipping()
    {
        $products_id = [];

        // get products id from cart
        $cart_products_id = Cart::instance('cart')->content()->pluck('id')->toArray();

        // get products id from wishlist
        $wishlist_products_id = Cart::instance('wishlist')->content()->pluck('id')->toArray();

        // get products id from cart and wishlist
        $products_id = array_unique(array_merge($cart_products_id, $wishlist_products_id));

        $products = [];

        // get all products data from database with best price
        $products = getBestOfferForProducts($products_id);

        // put products data in cart_products variable
        $cart_products = $products->whereIn('id', $cart_products_id);

        // put products data in wishlist_products variable
        $wishlist_products = $products->whereIn('id', $wishlist_products_id);

        return view('front.orders.shipping', compact('cart_products', 'wishlist_products'));
    }

    public function billing()
    {
        return view('front.orders.billing');
    }

    public function billingCheck(Request $request)
    {
        $data = $request->all();

        ksort($data);

        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success'
        ];

        $concat_data = '';

        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $concat_data .= $element;
            }
        }

        $secret = env('PAYMOB_HMAC');

        $generated_hmac = hash_hmac('SHA512', $concat_data, $secret);

        if ($generated_hmac == $hmac && $data['success'] == 'true') {
            // update the database with the order data
            $order = Order::with([
                'address' => fn ($q) => $q->with('governorate', 'city'),
                'user',
                'products' => fn ($q) => $q->select('products.id', 'products.quantity', 'product_id'),
            ])->updateOrCreate([
                'user_id'       =>  auth()->user()->id,
                'status_id'     =>  1
            ], [
                'payment_status' => 1,
                'payment_details' => [
                    'amount_cents' => $data['amount_cents'],
                    'order_id' => $data['order'],
                    'transaction_id' => $data['id'],
                    'source_data_sub_type' => $data['source_data_sub_type'],
                ]
            ]);

            // create order
            createBostaOrder($order);

            return redirect()->route('front.order.done');
        } else {
            return redirect()->route('front.order.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }

    public function done()
    {
        return view('front.orders.done');
    }

    public function cancel($order_id)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($order_id);

            if ($order->payment_method == 1) {
                // update the database
                returnTotalOrder($order);
                // Cancel Bosta Order
                cancelBostaOrder($order);
            } elseif (($order->payment_method == 2 || $order->payment_method == 3) && $order->payment_status == 1) {
                $refund = $order->subtotal_final + $order->delivery_fees;

                if ($order->created_at->diffInDays() < 1) {
                    if (voidRequestPaymob(json_decode($order->payment_details)->transaction_id)) {
                        // update the database
                        returnTotalOrder($order);
                        // Cancel Bosta Order
                        cancelBostaOrder($order);
                    }
                } else {
                    if (refundRequestPaymob(json_decode($order->payment_details)->transaction_id, $refund)) {
                        // update the database
                        returnTotalOrder($order);
                        // Cancel Bosta Order
                        cancelBostaOrder($order);
                    }
                }
            } elseif ($order->payment_method == 4) {
                if ($order->bosta_id != null) {
                    // update the database
                    returnTotalOrder($order);
                    // Cancel Bosta Order
                    cancelBostaOrder($order);
                } else {
                    // update the database
                    returnTotalOrder($order);
                }
            }

            DB::commit();

            return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order Canceled Successfully'));
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
}

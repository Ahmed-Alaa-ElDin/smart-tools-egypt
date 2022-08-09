<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Payment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
            ->whereHas('status', function ($query) {
                $query->whereNotIn('id', [1, 10]);
            })
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
        $products_discounts_percentage = $products_base_prices > 0 ? round(($products_discounts / $products_base_prices) * 100, 0) : 0.00;

        // Get offers discounts value
        $offers_discounts = $products_final_prices - $products_best_prices;

        // Get offers discounts percentage
        $offers_discounts_percentage = $products_final_prices ? round(($offers_discounts / $products_final_prices) * 100, 0) : 0.00;

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
        $coupon_shipping = $order->coupon_id ? $coupon_data['coupon_shipping'] : null;

        // Get Used Balance
        $used_balance = $order->used_balance;

        // Get Used Points
        $used_points = $order->used_points;
        $used_points_egp = $used_points * config('constants.constants.POINT_RATE');

        // Get Delivery Fees
        $delivery_fees = $products_total_quantities > 0 ? ($order->coupon_id && $coupon_shipping === 0 ? 0.00 : $delivery_fees) : 0.00;

        // Get Total Price
        $total_price = round($products_best_prices + $delivery_fees - $used_balance - $used_points_egp - $coupon_discount, 2);

        // Return Points and used balance
        if ($total_price < 0) {
            if ($used_points > 0 && abs($total_price) >= $used_points_egp) {
                $total_price = round($total_price + $used_points_egp, 2);
                $used_points_egp = 0;
                $used_points = 0;
            } elseif ($used_points > 0 && abs($total_price) < $used_points_egp) {
                $used_points_egp -= abs($total_price);
                $used_points = $used_points_egp / config('constants.constants.POINT_RATE');
                $total_price = 0;
            }

            if ($used_balance > 0 && abs($total_price) >= $used_balance) {
                $total_price = round($total_price + $used_balance, 2);
                $used_balance = 0;
            } elseif ($used_balance > 0 && abs($total_price) < $used_balance) {
                $used_balance -= abs($total_price);
                $total_price = 0;
            }
        }

        // Get Paid Money
        $payment_method = $order->payment_method;
        $payment_status = $order->should_pay == 0 ? 1 : 0;
        $old_price = $order->subtotal_final + $order->delivery_fees;

        $difference = $payment_status ? $total_price - $old_price : $total_price;

        $new_order = Order::updateOrCreate([
            'user_id' => $order->user_id,
            'status_id' => 10
        ], [
            'address_id' => $order->address_id,
            'phone1' => $order->phone1,
            'phone2'    => $order->phone2,
            'package_type' => $order->package_type,
            'package_desc' => $order->package_desc,
            'num_of_items' => $products_total_quantities,
            'allow_opening' => $order->allow_opening,
            'zone_id'   => $order->zone_id,
            'coupon_id' => $order->coupon_id,
            'coupon_discount' => $coupon_discount,
            'subtotal_base' => $products_final_prices,
            'subtotal_final' => $coupon_discount ? $products_best_prices - $coupon_discount - $used_balance - $used_points_egp : $products_best_prices - $used_balance - $used_points_egp,
            'should_pay' => $difference > 0 ? $difference : 0,
            'should_get' => $difference < 0 ? abs($difference) : 0,
            'total' => $total_price,
            'used_points' => $used_points,
            'used_balance' => $used_balance,
            'gift_points' => $total_points,
            'delivery_fees' => $delivery_fees,
            'total_weight' => $total_weight,
            'payment_method' => $payment_method,
            'tracking_number' => $order->tracking_number,
            'order_delivery_id' => $order->order_delivery_id,
            'notes' => $order->notes,
        ]);

        // Products data
        $order_products = [];

        foreach ($products_ids as $product_id) {
            $order_products[$product_id] =                     [
                'quantity' => $products_quantities[$product_id],
                'price' => $best_products->where('id', $product_id)->first()->best_price
            ];
        }

        $new_order->products()->sync($order_products);

        $order_data = [
            'order_id' => $order->id,
            'new_order_id' => $new_order->id,
            'products_base_prices' => $products_base_prices,
            'products_discounts' => $products_discounts,
            'products_discounts_percentage' => $products_discounts_percentage,
            'products_final_prices' => $products_final_prices,
            'offers_discounts' => $offers_discounts,
            'offers_discounts_percentage' => $offers_discounts_percentage,
            'products_best_prices' => $products_best_prices,
            'total_points' => $total_points,
            'coupon_discount' => $coupon_discount,
            'coupon_discount_percentage' => $coupon_discount_percentage,
            'coupon_points' => $coupon_points,
            'delivery_fees' => $delivery_fees,
            'used_balance' => $used_balance,
            'used_points' => $used_points,
            'used_points_egp' => $used_points_egp,
            'products_total_quantities' => $products_total_quantities,
            'order_total' => $total_price,
            'old_price' => $old_price,
            'difference' => $difference,
            'payment_method' => $payment_method,
            'should_pay' => $new_order->should_pay,
            'should_get' => $new_order->should_get,
        ];

        // return order data;
        return view('front.orders.edit_preview', compact('order_data'));
    }

    public function saveUpdates(Request $request, $old_order_id, $new_order_id)
    {
        $old_order = Order::with(['products', 'user', 'payments'])->findOrFail($old_order_id);
        $new_order = Order::with(['products'])->findOrFail($new_order_id);

        $returned_balance = $old_order->used_balance - $new_order->used_balance;
        $returned_points = $old_order->used_points - $new_order->used_points;
        $returned_gift_points = $new_order->gift_points - $old_order->gift_points;

        DB::beginTransaction();

        if ($new_order->payment_method == 1) {
            if (editBostaOrder($new_order)) {
                try {
                    $old_order->update([
                        'status_id' => 11,
                        'num_of_items' => $new_order->num_of_items,
                        'coupon_discount' => $new_order->coupon_discount,
                        'subtotal_base' => $new_order->subtotal_base,
                        'subtotal_final' => $new_order->subtotal_final,
                        'total' => $new_order->total,
                        'delivery_fees' => $new_order->delivery_fees,
                        'should_pay' => $new_order->should_pay,
                        'should_get' => $new_order->should_get,
                        'used_points' => $new_order->used_points,
                        'used_balance' => $new_order->used_balance,
                        'gift_points' => $new_order->gift_points,
                        'total_weight' => $new_order->total_weight,
                    ]);

                    $order_products = [];
                    $returned_products = [];

                    foreach ($new_order->products as $product) {
                        $order_products[$product->id] = [
                            'quantity' => $product->pivot->quantity,
                            'price' => $product->pivot->price
                        ];
                    }

                    foreach ($old_order->products as $product) {
                        $returned_products[$product->id] = [
                            'quantity' => $product->pivot->quantity,
                        ];
                    }

                    $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
                        $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
                        $product->save();
                    });

                    $old_order->products()->sync($order_products);

                    $old_order->user()->update([
                        'balance' => $old_order->user->balance + $returned_balance,
                        'points' => $old_order->user->points + $returned_points + $returned_gift_points,
                    ]);

                    $new_order->delete();

                    DB::commit();

                    Session::flash('success', __('front/homePage.Order edit request sent successfully'));
                    return redirect()->route('front.orders.index');
                } catch (\Throwable $th) {
                    DB::rollBack();

                    Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                    return redirect()->route('front.orders.index');
                }
            } else {
                Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                return redirect()->route('front.orders.index');
            }
        } elseif ($new_order->payment_method == 2 || $new_order->payment_method == 3) {
            if ($new_order->should_get > 0 && $request->type == 'wallet') {
                if (editBostaOrder($new_order)) {
                    try {
                        $old_order->update([
                            'status_id' => 11,
                            'num_of_items' => $new_order->num_of_items,
                            'coupon_discount' => $new_order->coupon_discount,
                            'subtotal_base' => $new_order->subtotal_base,
                            'subtotal_final' => $new_order->subtotal_final,
                            'total' => $new_order->total,
                            'delivery_fees' => $new_order->delivery_fees,
                            'should_pay' => $new_order->should_pay,
                            'should_get' => $new_order->should_get,
                            'used_points' => $new_order->used_points,
                            'used_balance' => $new_order->used_balance,
                            'gift_points' => $new_order->gift_points,
                            'total_weight' => $new_order->total_weight,
                        ]);

                        $order_products = [];
                        $returned_products = [];

                        foreach ($new_order->products as $product) {
                            $order_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
                                'price' => $product->pivot->price
                            ];
                        }

                        foreach ($old_order->products as $product) {
                            $returned_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
                            ];
                        }

                        $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
                            $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
                            $product->save();
                        });

                        $old_order->products()->sync($order_products);

                        $old_order->user()->update([
                            'balance' => $old_order->user->balance + $returned_balance + $new_order->should_get,
                            'points' => $old_order->user->points + $returned_points + $returned_gift_points,
                        ]);

                        $new_order->delete();

                        DB::commit();

                        Session::flash('success', __('front/homePage.Order edit request sent successfully'));
                        return redirect()->route('front.orders.index');
                    } catch (\Throwable $th) {
                        throw $th;
                        DB::rollBack();

                        Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                        return redirect()->route('front.orders.index');
                    }
                } else {
                    Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                    return redirect()->route('front.orders.index');
                }
            } elseif ($new_order->should_get > 0 && $request->type == 'card') {
                $payment = [
                    'order_id' => $new_order->id,
                    'user_id' => $new_order->user_id,
                    'payment_amount' => 0 - $new_order->should_get,
                    'payment_method' => $new_order->payment_method,
                    'payment_status' => 1,
                    'payment_details' => $old_order->payments->where('payment_amount', '>=', $new_order->should_get)->first()->payment_details,
                ];

                $new_order->payments()->updateOrCreate([
                    'order_id' => $payment['order_id'],
                ], $payment);

                if (refundRequestPaymob(json_decode($payment['payment_details'])->transaction_id, abs($payment['payment_amount'])) && editBostaOrder($new_order)) {
                    try {
                        $old_order->update([
                            'status_id' => 11,
                            'num_of_items' => $new_order->num_of_items,
                            'coupon_discount' => $new_order->coupon_discount,
                            'subtotal_base' => $new_order->subtotal_base,
                            'subtotal_final' => $new_order->subtotal_final,
                            'total' => $new_order->total,
                            'delivery_fees' => $new_order->delivery_fees,
                            'should_pay' => 0,
                            'should_get' => 0,
                            'used_points' => $new_order->used_points,
                            'used_balance' => $new_order->used_balance,
                            'gift_points' => $new_order->gift_points,
                            'total_weight' => $new_order->total_weight,
                        ]);

                        $new_order->payments()->first()->update([
                            'payment_status' => 2,
                        ]);

                        $order_products = [];
                        $returned_products = [];

                        foreach ($new_order->products as $product) {
                            $order_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
                                'price' => $product->pivot->price
                            ];
                        }

                        foreach ($old_order->products as $product) {
                            $returned_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
                            ];
                        }

                        $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
                            $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
                            $product->save();
                        });

                        $old_order->products()->sync($order_products);

                        $old_order->user()->update([
                            'balance' => $old_order->user->balance + $returned_balance,
                            'points' => $old_order->user->points + $returned_points + $returned_gift_points,
                        ]);

                        $new_order->delete();

                        DB::commit();

                        Session::flash('success', __('front/homePage.Order edit request sent successfully'));
                        return redirect()->route('front.orders.index');
                    } catch (\Throwable $th) {
                        throw $th;
                        DB::rollBack();

                        $new_order->payments()->first()->update([
                            'payment_status' => 3,
                        ]);

                        Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                        return redirect()->route('front.orders.index');
                    }
                } else {
                    $new_order->payments()->first()->update([
                        'payment_status' => 3,
                    ]);

                    Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                    return redirect()->route('front.orders.index');
                }
            } elseif ($new_order->should_pay == 0 && $new_order->should_get == 0 && $request->type == 'equal') {
                if (editBostaOrder($new_order)) {
                    try {
                        $old_order->update([
                            'status_id' => 11,
                            'num_of_items' => $new_order->num_of_items,
                            'coupon_discount' => $new_order->coupon_discount,
                            'subtotal_base' => $new_order->subtotal_base,
                            'subtotal_final' => $new_order->subtotal_final,
                            'total' => $new_order->total,
                            'delivery_fees' => $new_order->delivery_fees,
                            'should_pay' => $new_order->should_pay,
                            'should_get' => $new_order->should_get,
                            'used_points' => $new_order->used_points,
                            'used_balance' => $new_order->used_balance,
                            'gift_points' => $new_order->gift_points,
                            'total_weight' => $new_order->total_weight,
                        ]);

                        $order_products = [];
                        $returned_products = [];

                        foreach ($new_order->products as $product) {
                            $order_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
                                'price' => $product->pivot->price
                            ];
                        }

                        foreach ($old_order->products as $product) {
                            $returned_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
                            ];
                        }

                        $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
                            $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
                            $product->save();
                        });

                        $old_order->products()->sync($order_products);

                        $old_order->user()->update([
                            'balance' => $old_order->user->balance + $returned_balance,
                            'points' => $old_order->user->points + $returned_points + $returned_gift_points,
                        ]);

                        $new_order->delete();

                        DB::commit();

                        Session::flash('success', __('front/homePage.Order edit request sent successfully'));
                        return redirect()->route('front.orders.index');
                    } catch (\Throwable $th) {
                        DB::rollBack();

                        Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                        return redirect()->route('front.orders.index');
                    }
                } else {
                    Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                    return redirect()->route('front.orders.index');
                }
            } elseif ($new_order->should_pay > 0 && $request->type == 'pay') {
                $payment = [
                    'order_id' => $new_order->id,
                    'user_id' => $new_order->user_id,
                    'payment_amount' => 0 - $new_order->should_get,
                    'payment_method' => $new_order->payment_method,
                    'payment_status' => 1,
                    'payment_details' => $old_order->payments->where('payment_amount', '>=', $new_order->should_get)->first()->payment_details,
                ];

                $new_order->payments()->updateOrCreate([
                    'order_id' => $payment['order_id'],
                ], $payment);

                payByPaymob($new_order);
            }
        }
        dd($old_order->toArray(), $new_order->toArray());
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
            DB::beginTransaction();

            try {
                // update the database with the order data
                $payment = Payment::with(['order' => function ($query) {
                    $query->with(['user', 'products', 'address']);
                }])
                    ->where('user_id', auth()->user()->id)
                    ->where('payment_amount', $data['amount_cents'] / 100)
                    ->where('payment_status', 1)
                    ->first();

                $payment->update([
                    'payment_status' => 2,
                    'payment_details' => [
                        'amount_cents' => $data['amount_cents'],
                        'order_id' => $data['order'],
                        'transaction_id' => $data['id'],
                        'source_data_sub_type' => $data['source_data_sub_type'],
                    ]
                ]);

                $payment->order->update([
                    'should_pay' => 0.00,
                ]);

                $order = $payment->order;

                // create order
                // createBostaOrder($order);

                DB::commit();

                return redirect()->route('front.order.billing.checked', $payment->order->id);
            } catch (\Throwable $th) {
                DB::rollBack();

                $payment = Payment::with(['order' => function ($query) {
                    $query->with(['user', 'products', 'address']);
                }])
                    ->where('user_id', auth()->user()->id)
                    ->where('payment_amount', $data['amount_cents'] / 100)
                    ->where('payment_status', 1)
                    ->first();

                $payment->update([
                    'payment_status' => 3,
                ]);

                return redirect()->route('front.order.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
            }
        } else {
            $payment = Payment::with(['order' => function ($query) {
                $query->with(['user', 'products', 'address']);
            }])
                ->where('user_id', auth()->user()->id)
                ->where('payment_amount', $data['amount_cents'] / 100)
                ->where('payment_status', 1)
                ->first();

            $payment->update([
                'payment_status' => 3,
            ]);

            return redirect()->route('front.order.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }

    public function billingChecked(Request $request)
    {
        dd($request->all());
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
            DB::beginTransaction();

            try {
                // update the database with the order data
                $payment = Payment::with(['order' => function ($query) {
                    $query->with(['user', 'products', 'address']);
                }])
                    ->where('user_id', auth()->user()->id)
                    ->where('payment_amount', $data['amount_cents'] / 100)
                    ->where('payment_status', 1)
                    ->first();

                $payment->update([
                    'payment_status' => 2,
                    'payment_details' => [
                        'amount_cents' => $data['amount_cents'],
                        'order_id' => $data['order'],
                        'transaction_id' => $data['id'],
                        'source_data_sub_type' => $data['source_data_sub_type'],
                    ]
                ]);

                $payment->order->update([
                    'should_pay' => 0.00,
                ]);

                $order = $payment->order;

                // create order
                // createBostaOrder($order);

                DB::commit();

                return redirect()->route('front.order.billing.checked', $payment->order->id);
            } catch (\Throwable $th) {
                DB::rollBack();

                $payment = Payment::with(['order' => function ($query) {
                    $query->with(['user', 'products', 'address']);
                }])
                    ->where('user_id', auth()->user()->id)
                    ->where('payment_amount', $data['amount_cents'] / 100)
                    ->where('payment_status', 1)
                    ->first();

                $payment->update([
                    'payment_status' => 3,
                ]);

                return redirect()->route('front.order.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
            }
        } else {
            $payment = Payment::with(['order' => function ($query) {
                $query->with(['user', 'products', 'address']);
            }])
                ->where('user_id', auth()->user()->id)
                ->where('payment_amount', $data['amount_cents'] / 100)
                ->where('payment_status', 1)
                ->first();

            $payment->update([
                'payment_status' => 3,
            ]);

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
            } elseif (($order->payment_method == 2 || $order->payment_method == 3) && $order->should_pay == 0) {
                $refund = $order->subtotal_final + $order->delivery_fees;

                if ($order->created_at->diffInDays() < 1) {
                    if (voidRequestPaymob(json_decode($order->payments()->where('payment_amount', '>=', $order->total)->first()->payment_details)->transaction_id)) {
                        // update the database
                        returnTotalOrder($order);
                        // Cancel Bosta Order
                        cancelBostaOrder($order);
                    }
                } else {
                    if (refundRequestPaymob(json_decode($order->payments()->where('payment_amount', '>=', $order->total)->first()->payment_details)->transaction_id, $refund)) {
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

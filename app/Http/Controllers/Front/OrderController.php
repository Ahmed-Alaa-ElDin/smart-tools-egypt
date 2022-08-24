<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    ##################### Orders List :: Start #####################
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
                $query->whereNotIn('id', [1, 15]);
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('front.orders.index', compact('orders'));
    }
    ##################### Orders List :: End #####################

    ##################### Edit Order Before Shipping :: Start #####################
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
    ##################### Edit Order Before Shipping :: End #####################

    ##################### Preview Order's Edits :: Start #####################
    public function updateCalc(Request $request, $order_id)
    {
        // products ids
        $products_ids = $request->products_ids;

        // Get Order data
        $order = Order::with([
            'coupon' => fn ($q) => $q->with([
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
        ])->findOrFail($order_id);

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

        // Get summation of products selling prices
        $products_total_prices = $old_products->sum(function ($product) use ($products_quantities) {
            return $product->pivot->price * $products_quantities[$product->id];
        });

        // Get summation of products selling prices
        $products_total_points = $old_products->sum(function ($product) use ($products_quantities) {
            return $product->pivot->points * $products_quantities[$product->id];
        });

        // Get summation of products best prices
        $products_best_prices = $products_total_prices + $order->coupon_products_discount;

        // Get summation of products best points
        $products_best_points = $products_total_points - $order->coupon_products_points;

        // Get products discounts value
        $products_discounts = $products_base_prices - $products_final_prices;

        // Get products discounts percentage
        $products_discounts_percentage = $products_base_prices > 0 ? round(($products_discounts / $products_base_prices) * 100, 0) : 0.00;

        // Get offers discounts value
        $offers_discounts = $products_final_prices - $products_best_prices;

        // Get offers discounts percentage
        $offers_discounts_percentage = $products_final_prices ? round(($offers_discounts / $products_final_prices) * 100, 0) : 0.00;

        // Get shipping fees
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

        //  get coupon data
        $coupon_discount = 0.00;
        $coupon_discount_percentage = 0;
        $coupon_points = 0;
        $coupon_shipping = null;
        $coupon_products_discount = 0.00;
        $coupon_products_points = 0;

        if ($order->coupon_id) {
            $coupon = $order->coupon;

            // Coupon Free Shipping
            $coupon_shipping = $coupon->free_shipping ? 0 : null;

            // Coupon Products Discount
            $coupon_products_discount = $products_best_prices - $products_total_prices;

            // Coupon Total Discount
            $coupon_discount = $coupon_products_discount + $order->coupon_order_discount;

            // Coupon Total Discount Percentage
            $coupon_discount_percentage = $products_best_prices > 0 ? round($coupon_discount * 100 / $products_best_prices) : 0;

            // Coupon Products Points
            $coupon_products_points = $products_total_points - $products_best_points;

            // Coupon Total Points
            $coupon_points =  $products_total_points - $products_best_points + $order->coupon_order_points;
        }

        // Get Total Points
        $total_points = isset($order_points) ? $products_best_points + $order_points + $coupon_points : $products_best_points + $coupon_points;

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
        $old_price = $order->total;

        $difference = $payment_status ? round($total_price - $old_price, 2) : $total_price;

        $new_order = Order::updateOrCreate([
            'user_id' => $order->user_id,
            'status_id' => 15
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
            'coupon_order_discount' => $order->coupon_order_discount,
            'coupon_order_points' => $order->coupon_order_points,
            'coupon_products_discount' => $coupon_products_discount,
            'coupon_products_points' => $coupon_products_points,
            'coupon_discount' => $coupon_discount,
            'coupon_points' => $coupon_points,
            'subtotal_base' => $products_final_prices,
            'subtotal_final' => $coupon_discount ? $products_best_prices - $coupon_discount - $used_balance - $used_points_egp : $products_best_prices - $used_balance - $used_points_egp,
            'should_pay' => $difference > 0 ? abs($difference) : 0,
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

        $new_order->statuses()->attach(15);

        // Products data
        $order_products = [];

        foreach ($products_ids as $product_id) {
            $order_products[$product_id] = [
                'quantity' => $products_quantities[$product_id],
                'price' => $old_products->find($product_id)->pivot->price,
                'points' => $old_products->find($product_id)->pivot->points
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
    ##################### Preview Order's Edits :: End #####################

    ##################### Save Order's Edits :: Start #####################
    public function update(Request $request, $old_order_id, $new_order_id)
    {
        $old_order = Order::with(['products', 'user', 'payments'])->findOrFail($old_order_id);
        $new_order = Order::with(['products'])->findOrFail($new_order_id);

        $returned_balance = $old_order->used_balance - $new_order->used_balance;
        $returned_points = $old_order->used_points - $new_order->used_points;
        $returned_gift_points = $new_order->gift_points - $old_order->gift_points;


        // New Products Quantities
        $order_products = [];
        foreach ($new_order->products as $product) {
            $order_products[$product->id] = [
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price,
                'points' => $product->pivot->points
            ];
        }

        // Old Products Quantities
        $returned_products = [];
        foreach ($old_order->products as $product) {
            $returned_products[$product->id] = [
                'quantity' => $product->pivot->quantity,
            ];
        }

        DB::beginTransaction();

        // Cash On Delivery
        if ($new_order->payment_method == 1) {
            if (editBostaOrder($new_order, $old_order_id)) {
                try {
                    $old_order->update([
                        'status_id' => 12,
                        'num_of_items' => $new_order->num_of_items,
                        'coupon_order_discount' => $new_order->coupon_order_discount,
                        'coupon_order_points' => $new_order->coupon_order_points,
                        'coupon_products_discount' => $new_order->coupon_products_discount,
                        'coupon_products_points' => $new_order->coupon_products_points,
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

                    $old_order->statuses()->attach([16, 12]);

                    $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
                        $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
                        $product->save();
                    });

                    $old_order->products()->sync($order_products);

                    $old_order->user()->update([
                        'balance' => $old_order->user->balance + $returned_balance,
                        'points' => $old_order->user->points + $returned_points + $returned_gift_points,
                    ]);

                    $old_order->payments()->first()->update([
                        'payment_amount' => $new_order->total
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
        }

        // Card or installment
        elseif ($new_order->payment_method == 2 || $new_order->payment_method == 3) {
            if ($new_order->should_get > 0 && $request->type == 'wallet') {
                if (editBostaOrder($new_order, $old_order_id)) {
                    try {
                        $old_order->update([
                            'status_id' => 12,
                            'num_of_items' => $new_order->num_of_items,
                            'coupon_order_discount' => $new_order->coupon_order_discount,
                            'coupon_order_points' => $new_order->coupon_order_points,
                            'coupon_products_discount' => $new_order->coupon_products_discount,
                            'coupon_products_points' => $new_order->coupon_products_points,
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

                        $old_order->statuses()->attach([16, 12]);

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
                    'payment_amount' => -1 * $new_order->should_get,
                    'payment_method' => $new_order->payment_method,
                    'payment_status' => 1,
                    'payment_details' => $old_order->payments->where('payment_amount', '>=', $new_order->should_get)->first()->payment_details,
                ];

                $new_order->payments()->updateOrCreate([
                    'order_id' => $payment['order_id'],
                    'payment_status' => 1,
                ], $payment);

                if (refundRequestPaymob(json_decode($payment['payment_details'])->transaction_id, abs($payment['payment_amount'])) && editBostaOrder($new_order, $old_order_id)) {
                    try {
                        $old_order->update([
                            'status_id' => 12,
                            'num_of_items' => $new_order->num_of_items,
                            'coupon_order_discount' => $new_order->coupon_order_discount,
                            'coupon_order_points' => $new_order->coupon_order_points,
                            'coupon_products_discount' => $new_order->coupon_products_discount,
                            'coupon_products_points' => $new_order->coupon_products_points,
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

                        $old_order->statuses()->attach([16, 12]);

                        $new_order->payments()->first()->update([
                            'payment_status' => 2,
                        ]);

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
                if (editBostaOrder($new_order, $old_order_id)) {
                    try {
                        $old_order->update([
                            'status_id' => 12,
                            'num_of_items' => $new_order->num_of_items,
                            'coupon_order_discount' => $new_order->coupon_order_discount,
                            'coupon_order_points' => $new_order->coupon_order_points,
                            'coupon_products_discount' => $new_order->coupon_products_discount,
                            'coupon_products_points' => $new_order->coupon_products_points,
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

                        $old_order->statuses()->attach([16, 12]);

                        $order_products = [];
                        $returned_products = [];

                        foreach ($new_order->products as $product) {
                            $order_products[$product->id] = [
                                'quantity' => $product->pivot->quantity,
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
                    'old_order_id' => $old_order->id,
                    'user_id' => $new_order->user_id,
                    'payment_amount' => $new_order->should_pay,
                    'payment_method' => $new_order->payment_method,
                    'payment_status' => 1,
                ];

                $payment = $new_order->payments()->updateOrCreate([
                    'order_id' => $payment['order_id'],
                    'payment_status' => 1,
                ], $payment);

                DB::commit();

                $payment_key = payByPaymob($payment);

                if ($payment_key) {
                    return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . ($new_order->payment_method == 3 ? env('PAYMOB_IFRAM_ID_INSTALLMENTS') : env('PAYMOB_IFRAM_ID_CARD_TEST')) . "?payment_token=$payment_key");
                } else {
                    return redirect()->route('front.orders.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
                }
            }
        }

        // Vodafone Cash
        elseif ($new_order->payment_method == 4) {
            // Old Order Paid
            if ($old_order->should_pay == 0) {
                // Pay the Difference
                if ($new_order->should_pay > 0 && $request->type == 'pay') {
                    $payment = [
                        'order_id' => $new_order->id,
                        'old_order_id' => $old_order->id,
                        'user_id' => $new_order->user_id,
                        'payment_amount' => $new_order->should_pay,
                        'payment_method' => 4,
                        'payment_status' => 1,
                    ];

                    $payment = $new_order->payments()->updateOrCreate([
                        'order_id' => $payment['order_id'],
                        'payment_status' => 1,
                    ], $payment);

                    $old_order->update([
                        'status_id' => 2,
                        'should_pay' => $new_order->should_pay,
                    ]);

                    if ($old_order->statuses()->latest()->first()->id != 2) {
                        $old_order->statuses()->attach(2);
                    }

                    DB::commit();

                    return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order edit request sent successfully'));
                }
                // Old Order Price Equal New Order Price
                elseif ($new_order->should_pay == 0.00 && $request->type == 'equal') {
                    if (editBostaOrder($new_order, $old_order_id)) {
                        try {
                            $old_order->update([
                                'status_id' => 12,
                                'num_of_items' => $new_order->num_of_items,
                                'coupon_order_discount' => $new_order->coupon_order_discount,
                                'coupon_order_points' => $new_order->coupon_order_points,
                                'coupon_products_discount' => $new_order->coupon_products_discount,
                                'coupon_products_points' => $new_order->coupon_products_points,
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

                            $old_order->statuses()->attach([16, 12]);

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
                }
                // Get the Difference
                // To Vodafone Wallet
                elseif ($new_order->should_get > 0 && $request->type == 'vodafone') {
                    $payment = [
                        'order_id' => $new_order->id,
                        'old_order_id' => $old_order->id,
                        'user_id' => $new_order->user_id,
                        'payment_amount' => $new_order->should_get,
                        'payment_method' => 4,
                        'payment_status' => 1,
                    ];

                    $payment = $new_order->payments()->updateOrCreate([
                        'order_id' => $payment['order_id'],
                        'payment_status' => 1,
                    ], $payment);

                    $old_order->update([
                        'status_id' => 14,
                        'should_get' => $new_order->should_get,
                    ]);

                    $old_order->statuses()->attach([11, 12, 14]);

                    DB::commit();

                    return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order edit request sent successfully'));
                }
                // To Balance
                elseif ($new_order->should_get > 0 && $request->type == 'wallet') {
                    if (editBostaOrder($new_order, $old_order_id)) {
                        try {
                            $old_order->update([
                                'status_id' => 12,
                                'num_of_items' => $new_order->num_of_items,
                                'coupon_order_discount' => $new_order->coupon_order_discount,
                                'coupon_order_points' => $new_order->coupon_order_points,
                                'coupon_products_discount' => $new_order->coupon_products_discount,
                                'coupon_products_points' => $new_order->coupon_products_points,
                                'subtotal_base' => $new_order->subtotal_base,
                                'subtotal_final' => $new_order->subtotal_final,
                                'total' => $new_order->total,
                                'delivery_fees' => $new_order->delivery_fees,
                                'should_pay' => $new_order->should_pay,
                                'should_get' => 0.00,
                                'used_points' => $new_order->used_points,
                                'used_balance' => $new_order->used_balance,
                                'gift_points' => $new_order->gift_points,
                                'total_weight' => $new_order->total_weight,
                            ]);

                            $old_order->statuses()->attach([16, 12]);

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
                }
            }
            // Old Order Not Paid
            elseif ($old_order->should_pay > 0) {
                try {
                    $old_order->update([
                        'status_id' => 2,
                        'num_of_items' => $new_order->num_of_items,
                        'coupon_order_discount' => $new_order->coupon_order_discount,
                        'coupon_order_points' => $new_order->coupon_order_points,
                        'coupon_products_discount' => $new_order->coupon_products_discount,
                        'coupon_products_points' => $new_order->coupon_products_points,
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

                    $payment = $old_order->payments()->where('payment_status', 1)->first();

                    if ($payment) {
                        $payment->update([
                            'payment_amount' => $old_order->should_pay,
                        ]);
                    }

                    $old_order->statuses()->attach([16, 12, 2]);

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
            }
        }
    }
    ##################### Save Order's Edits :: End #####################

    ##################### Cancel Total Order :: Start #####################
    public function cancel($order_id, $new_order_id = null)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('payments')->findOrFail($order_id);

            $payments = $order->payments();

            if ($order->payment_method == 1) {
                // update the database
                returnTotalOrder($order);
                // Cancel Bosta Order
                cancelBostaOrder($order);
            } elseif (($order->payment_method == 2 || $order->payment_method == 3) && $order->should_pay == 0) {
                if ($order->created_at->diffInDays() < 1) {
                    foreach ($payments->get() as $payment) {
                        $refund_success = [];
                        $refund_success[] = voidRequestPaymob(json_decode($payment->payment_details)->transaction_id);
                        if (!array_search(false, $refund_success)) {
                            // update the database
                            returnTotalOrder($order);
                            // Cancel Bosta Order
                            cancelBostaOrder($order);
                        }
                    }
                } else {
                    foreach ($payments->get() as $payment) {
                        $refund_success = [];
                        $refund_success[] = refundRequestPaymob(json_decode($payment->payment_details)->transaction_id, json_decode($payment->payment_details)->amount_cents);
                        if (!array_search(false, $refund_success)) {
                            // update the database
                            returnTotalOrder($order);
                            // Cancel Bosta Order
                            cancelBostaOrder($order);
                        }
                    }
                }
            } elseif ($order->payment_method == 4) {
                if ($order->order_delivery_id != null) {
                    // Make Refund Request
                    $payment = [
                        'order_id' => $order->id,
                        'user_id' => $order->user->id,
                        'payment_amount' => $order->total,
                        'payment_method' => 4,
                        'payment_status' => 4,
                    ];

                    $order->payments()->create($payment);

                    // update the database
                    returnTotalOrder($order);

                    $order->update([
                        'status_id' => 14,
                        'should_get' => $order->total,
                    ]);

                    $order->statuses()->attach(14);

                    // Cancel Bosta Order
                    cancelBostaOrder($order);
                } else {
                    // update the database
                    returnTotalOrder($order);
                }
            }

            // delete the temp order
            if ($new_order_id != null) {
                $new_order = Order::findOrFail($new_order_id);

                $new_order->products()->detach();

                $new_order->delete();
            }

            DB::commit();

            return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order Canceled Successfully'));
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
    ##################### Cancel Total Order :: End #####################

    ##################### Return Total Order :: Start #####################
    public function return(Request $request, $order_id)
    {
        if ($request->method() == 'GET') {
            $order = Order::with([
                'products' => fn ($q) => $q->with('thumbnail')
            ])->findOrFail($order_id);

            return view('front.orders.return-products', compact('order'));
        } else {
            // todo: return all products
        }
    }
    ##################### Return Total Order :: End #####################

    ##################### Preview Returned Products :: Start #####################
    public function returnCalc(Request $request, $order_id)
    {
        // products ids
        $products_ids = $request->products_ids;

        // get old order data from db
        $order = Order::with(['products', 'zone'])->findOrFail($order_id);

        // Get zone data
        $zone = $order->zone;

        // Get Order Products ids and quantities from request
        $products_quantities = array_combine($request->products_ids, $request->quantities);
        $products_total_quantities = array_sum($products_quantities);

        // Get Order Products ids and quantities from Order DB
        $old_products = $order->products;
        $old_products_total_quantities = $old_products->map(fn ($q) => $q->pivot->quantity)->sum();

        // total price & points of returned products
        $returned_products_data = $old_products->map(function ($product) use ($products_quantities) {
            $returned_quantity = $products_quantities[$product->id];
            $returned_price = $returned_quantity * $product->pivot->price;
            $returned_points = $returned_quantity * $product->pivot->points;

            return [
                'returned_price' => $returned_price,
                'returned_points' => $returned_points,
            ];
        });

        $returned_price = $returned_products_data->sum('returned_price');

        $returned_points = $returned_products_data->sum('returned_points');

        $old_products_points = $old_products->map(function ($product) {
            return $product->pivot->points * $product->pivot->quantity;
        })->sum();

        ############ Old Order Data ############
        // subtotal final
        $subtotal_final = $order->subtotal_final;
        $old_total = $order->total;
        $payment_method = $order->payment_method;
        $coupon_discount = $order->coupon_discount;
        $old_delivery_fees = $order->delivery_fees;
        $used_balance = $order->used_balance;
        $used_points = $order->used_points;
        $used_points_egp = $used_points * config('constants.constants.POINT_RATE');

        $gift_points = $order->gift_points;
        $coupon_points = $order->coupon_points;


        ############ Order Discounts and Points ############
        // order discount
        $order_discount = $subtotal_final + $old_delivery_fees - $old_total - $coupon_discount - $used_balance - $used_points_egp;
        $order_discount_factor = $subtotal_final ? $order_discount / $subtotal_final : 0.00;
        $returned_order_discount = round($returned_price * $order_discount_factor, 2);

        // order points
        $order_points = $gift_points - $old_products_points - $coupon_points;
        $order_points_factor = $subtotal_final ? $order_points / $subtotal_final : 0;
        $returned_order_points = round($returned_price * $order_points_factor);

        ############ Coupon Discounts and Points ############
        // coupon discount
        $coupon_discount_factor = $subtotal_final ? $coupon_discount / $subtotal_final : 0.00;
        $returned_coupon_discount = round($returned_price * $coupon_discount_factor, 2);

        // coupon points
        $coupon_points_factor = $subtotal_final ? $coupon_points / $subtotal_final : 0;
        $returned_coupon_points = round($returned_price * $coupon_points_factor);

        $returned_price = $returned_price - $returned_order_discount - $returned_coupon_discount;
        $returned_points += $returned_order_points + $returned_coupon_points;

        // Get Order Products ids and weights from database
        $products_weights = $old_products->where('free_shipping', 0)->pluck('weight', 'id');

        // Get Order Products total weight
        $total_weight = $products_weights->map(function ($weight, $id) use ($products_quantities) {
            if (isset($products_quantities[$id])) {
                return $weight * $products_quantities[$id];
            }
        })->sum();

        // delivery fees
        $delivery_fees = $total_weight < $zone->min_weight ? $zone->min_charge : $zone->min_charge + ($total_weight - $zone->min_weight) * $zone->kg_charge;

        $order_data = [
            "order_id" => $order->id,
            "old_order_total" => $old_total,
            "old_products_total_quantities" => $old_products_total_quantities,
            "old_product_gift_points" => $gift_points,
            "payment_method" => $payment_method,
            "returned_price" => $returned_price,
        ];

        return view('front.orders.return_preview', compact('order_data'));
    }
    ##################### Preview Returned Products :: End #####################


    ##################### Go To Shipping Details During Placing the Order :: Start #####################
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
    ##################### Go To Shipping Details During Placing the Order :: End #####################

    ##################### Go To Billing Details During Placing the Order :: Start #####################
    public function billing()
    {
        return view('front.orders.billing');
    }
    ##################### Go To Billing Details During Placing the Order :: End #####################

    ##################### Confirm the Paymob Billing :: Start #####################
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
                // get payment data
                $payment = Payment::with(['order' => function ($query) {
                    $query->with(['user', 'products', 'address']);
                }])
                    ->where('paymob_order_id', $data['order'])
                    ->first();

                // update payment status
                $payment->update([
                    'payment_status' => 2,
                    'payment_details' => [
                        'amount_cents' => $data['amount_cents'],
                        'transaction_id' => $data['id'],
                        'source_data_sub_type' => $data['source_data_sub_type'],
                    ]
                ]);

                // get order from payment

                // Old Orders
                if ($payment->old_order_id != null) {
                    $old_order = Order::with(['products', 'user', 'payments'])->findOrFail($payment->old_order_id);
                    $new_order = Order::with(['products'])->findOrFail($payment->order_id);

                    $returned_balance = $old_order->used_balance - $new_order->used_balance;
                    $returned_points = $old_order->used_points - $new_order->used_points;
                    $returned_gift_points = $new_order->gift_points - $old_order->gift_points;

                    $new_order->update([
                        'should_pay' => 0.00
                    ]);

                    $payment->update([
                        'order_id' => $payment->old_order_id,
                        'old_order_id' => null
                    ]);

                    $order_products = [];
                    $returned_products = [];

                    foreach ($new_order->products as $product) {
                        $order_products[$product->id] = [
                            'quantity' => $product->pivot->quantity,
                            'price' => $product->pivot->price,
                            'points' => $product->pivot->points,
                        ];
                    }

                    foreach ($old_order->products as $product) {
                        $returned_products[$product->id] = [
                            'quantity' => $product->pivot->quantity,
                        ];
                    }

                    if (editBostaOrder($new_order, $payment->old_order_id)) {
                        try {
                            $old_order->update([
                                'status_id' => 12,
                                'num_of_items' => $new_order->num_of_items,
                                'coupon_order_discount' => $new_order->coupon_order_discount,
                                'coupon_order_points' => $new_order->coupon_order_points,
                                'coupon_products_discount' => $new_order->coupon_products_discount,
                                'coupon_products_points' => $new_order->coupon_products_points,
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

                            $old_order->statuses()->attach([16, 12]);

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
                    }
                }
                // New Orders
                else {
                    $order = $payment->order;

                    // update the database with the order data
                    $order->update([
                        'should_pay' => 0.00,
                    ]);

                    // Order ==> New Order
                    $bosta_order = createBostaOrder($order);

                    if ($bosta_order['status']) {
                        // update order in database
                        $order->update([
                            'tracking_number' => $bosta_order['data']['trackingNumber'],
                            'order_delivery_id' => $bosta_order['data']['_id'],
                            'status_id' => 3,
                        ]);

                        $order->statuses()->attach(3);

                        // update user's balance
                        $user = User::find(auth()->user()->id);

                        $user->update([
                            'points' => $user->points - $order->used_points + $order->gift_points ?? 0,
                            'balance' => $user->balance - $order->used_balance ?? 0,
                        ]);

                        // update coupon usage
                        if ($order->coupon_id != null) {
                            $coupon = Coupon::find($order->coupon_id);

                            $coupon->update([
                                'number' => $coupon->number != null && $coupon->number > 0 ? $coupon->number - 1 : $coupon->number,
                            ]);
                        }

                        // todo :: edit offer usage

                        // clear cart
                        Cart::instance('cart')->destroy();
                        Cart::instance('cart')->store($user->id);

                        // edit products database
                        foreach ($order->products as $product) {
                            $product->update([
                                'quantity' => $product->quantity - $product->pivot->quantity >= 0  ? $product->quantity - $product->pivot->quantity : 0,
                            ]);
                        }

                        DB::commit();

                        // Send Email To User

                        // Send SMS To User

                        // redirect to done page
                        Session::flash('success', __('front/homePage.Order Created Successfully'));
                        return redirect()->route('front.orders.done')->with('order_id', $order->id);
                    } else {
                        Session::flash('error', __('front/homePage.Order Creation Failed, Please Try Again'));
                        return redirect()->route('front.orders.billing');
                    }
                }
            } catch (\Throwable $th) {
                DB::rollBack();

                $payment = Payment::where('paymob_order_id', $data['order'])
                    ->first();

                $new_payment = Payment::create([
                    'order_id' => $payment->order_id,
                    'old_order_id' => $payment->old_order_id,
                    'user_id' => $payment->user_id,
                    'payment_amount' => $payment->payment_amount,
                    'payment_method' => $payment->payment_method,
                    'payment_status' => $payment->payment_status,
                    'paymob_order_id' => $payment->paymob_order_id,
                    'payment_details' => $payment->payment_details,
                ]);

                $payment->update([
                    'payment_status' => 3,
                ]);

                return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
            }
        } else {
            $payment = Payment::where('paymob_order_id', $data['order'])
                ->first();

            $new_payment = Payment::create([
                'order_id' => $payment->order_id,
                'old_order_id' => $payment->old_order_id,
                'user_id' => $payment->user_id,
                'payment_amount' => $payment->payment_amount,
                'payment_method' => $payment->payment_method,
                'payment_status' => $payment->payment_status,
                'paymob_order_id' => $payment->paymob_order_id,
                'payment_details' => $payment->payment_details,
            ]);

            $payment->update([
                'payment_status' => 3,
            ]);

            return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }
    ##################### Confirm the Paymob Billing :: End #####################

    ##################### Order Done :: Start #####################
    public function done()
    {
        $order_id = session('order_id', null);

        return view('front.orders.done', compact('order_id'));
    }
    ##################### Order Done :: End #####################

    ##################### Go To Paymob Iframe :: Start #####################
    public function goToPayment($order_id)
    {
        $order = Order::with(['user', 'products', 'address'])->findOrFail($order_id);

        $payment = $order->payments()->where('payment_status', 1)->first();

        if ($payment && ($order->payment_method == 2 || $order->payment_method == 3)) {
            $payment_key = payByPaymob($payment);

            if ($payment_key) {
                return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . ($order->payment_method == 3 ? env('PAYMOB_IFRAM_ID_INSTALLMENTS') : env('PAYMOB_IFRAM_ID_CARD_TEST')) . "?payment_token=$payment_key");
            } else {
                return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
            }
        } else {
            return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }
    ##################### Go To Paymob Iframe :: Start #####################

    ##################### Update Order's Status By Bosta :: Start #####################
    public function updateStatus(Request $request)
    {
        $order = Order::findOrFail($request['businessReference']);


        $order->update([
            'status_id' => $request['state'],
        ]);

        $order->statuses()->attach($request['state'], ['notes' => $request['exceptionReason'] ?? null]);

        return response()->json(['success' => true]);
    }
    ##################### Update Order's Status By Bosta :: Start #####################

    ##################### Track the Order :: Start #####################
    public function track(Request $request)
    {
        $order = Order::with('statuses')->find($request['order_id']);

        if ($order) {
            $statuses = $order->statuses()->orderBy('pivot_id', 'desc')->get();

            return view('front.orders.track', compact('order', 'statuses'));
        } else {
            return response()->json(['success' => false]);
        }
    }
    ##################### Track the Order :: Start #####################
}

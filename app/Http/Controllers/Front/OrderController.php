<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    ##################### Orders List :: Start #####################
    public function index()
    {
        // dd(Order::with('products','collections')->get());
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
            'collections' => function ($query) {
                $query->select([
                    'collections.id',
                    'collections.name',
                    'collections.slug',
                    'collections.base_price',
                ])->with([
                    'products' => fn ($q) => $q->select('products.id'),
                    'reviews',
                    'thumbnail',
                ]);
            },
            'status',
            'payment',
            'transactions',
        ])
            ->where('user_id', auth()->user()->id)
            ->whereHas('status', function ($query) {
                $query->whereNotIn('id', [201, 15]);
            })
            ->orderBy('id', 'desc')
            ->paginate(5);

        // dd($orders);

        return view('front.orders.index', compact('orders'));
    }
    ##################### Orders List :: End #####################

    ##################### Edit Order Before Shipping:: Start #####################
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
            }, 'collections' => function ($query) {
                $query
                    ->select([
                        'collections.id',
                        'collections.name',
                        'collections.slug',
                        'collections.base_price',
                        'collections.final_price',
                        'collections.points',
                        'collections.free_shipping',
                        'collections.under_reviewing',
                        'quantity'
                    ])
                    ->with([
                        'products' => fn ($q) => $q->select('products.id'),
                        'thumbnail',
                    ]);
            },
        ])
            ->findOrFail($order_id);

        return view('front.orders.edit', compact('order'));
    }
    ##################### Edit Order Before Shipping:: End #####################

    ##################### Preview Order's Edits :: Start #####################
    public function updateCalc(Request $request, $order_id)
    {
        // products ids
        $products_ids = $request->products_ids;
        $collections_ids = $request->collections_ids;

        // Get Order data
        $order = Order::with([
            'payment',
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
            ]),
        ])->findOrFail($order_id);

        // Get Zone data
        $zone = $order->zone;

        // Get Order Products ids and quantities from request
        if ($products_ids) {
            $products_quantities = array_combine($request->products_ids, $request->products_quantities);
            $products_total_quantities = array_sum($products_quantities);
        } else {
            $products_total_quantities = 0;
        }

        // Get Order Collections ids and quantities from request
        if ($collections_ids) {
            $collections_quantities = array_combine($request->collections_ids, $request->collections_quantities);
            $collections_total_quantities = array_sum($collections_quantities);
        } else {
            $collections_total_quantities = 0;
        }

        $items_total_quantities = $products_total_quantities + $collections_total_quantities;

        // Get Order Products ids and quantities from Order DB
        $old_products = $order->products;

        // Get Order Collections ids and quantities from Order DB
        $old_collections = $order->collections;

        // Get best offer for each product & collection
        if ($products_ids) {
            $best_products = getBestOfferForProducts($request->products_ids)->map(function ($product) use (&$order, &$products_quantities) {
                $product->after_offer_price = $product->final_price - $product->offer_discount;
                $product->qty = $products_quantities[$product->id] ?? 0;
                $product->total_weight = $product->weight * $product->qty;
                $product->total_shipping_weight = !$product->free_shipping ? $product->weight * $product->qty : 0;
                $product->total_base_price = $product->base_price * $product->qty;
                $product->total_product_discount = ($product->base_price - $product->final_price) * $product->qty;
                $product->total_product_discount_percent = $product->base_price ? round((($product->base_price - $product->final_price) / $product->base_price) * 100, 2) : 0;
                $product->total_final_price = $product->final_price * $product->qty;
                $product->total_offer_discount = $product->offer_discount * $product->qty;
                $product->total_offer_discount_percent = $product->final_price ? round(($product->offer_discount / $product->final_price) * 100, 2) : 0;
                $product->total_after_offer_price = $product->total_final_price - $product->total_offer_discount;
                $product->total_product_points =  $product->points * $product->qty;
                $product->total_offer_points =  $product->offer_points * $product->qty;
                $product->total_after_offer_points =  $product->total_product_points + $product->total_offer_points;
                $product->coupon_discount =  0;
                $product->coupon_points =  0;

                return $product;
            });
        } else {
            $best_products = collect([]);
        }

        if ($collections_ids) {
            $best_collections = getBestOfferForCollections($request->collections_ids)->map(function ($collection) use (&$order, &$collections_quantities) {
                $collection->after_offer_price = $collection->final_price - $collection->offer_discount;
                $collection->qty = $collections_quantities[$collection->id] ?? 0;
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
        } else {
            $best_collections = collect([]);
        }

        // Get Best Items
        $best_items = $best_collections->concat($best_products);

        // Get Order Products & Collections total weight
        $total_weight = $best_items->sum('total_weight');

        // Order Offer
        $order_offer = Offer::orderOffers()->first();

        // ------------------------------------------------------------------------------------------------------
        // A - Coupon
        // ------------------------------------------------------------------------------------------------------

        // Get Coupon Data :: Start
        if ($order->coupon) {
            $coupon_data = getCouponData($best_items, $order->coupon);

            // add coupon discount and points to items
            $best_items->map(function ($item) use (&$coupon_data) {
                if ($item->type == "Product" && isset($coupon_data['products_best_coupon'][$item->id])) {
                    $item->coupon_discount =  $coupon_data['products_best_coupon'][$item->id]['coupon_discount'];
                    $item->coupon_points =  $coupon_data['products_best_coupon'][$item->id]['coupon_points'];
                } elseif ($item->type == "Collection" && isset($coupon_data['collections_best_coupon'][$item->id])) {
                    $item->coupon_discount =  $coupon_data['collections_best_coupon'][$item->id]['coupon_discount'];
                    $item->coupon_points =  $coupon_data['collections_best_coupon'][$item->id]['coupon_points'];
                }

                return $item;
            });

            $coupon_total_discount = $coupon_data['coupon_total_discount'];
            $coupon_total_points = $coupon_data['coupon_total_points'];
            $order_best_coupon = $coupon_data['order_best_coupon'];
        } else {
            $coupon_total_discount = 0;
            $coupon_total_points = 0;
            $order_best_coupon = [
                'type' => null,
                'value' => 0,
            ];
        }
        // Get Coupon Data :: End

        // ------------------------------------------------------------------------------------------------------
        // B - Shipping
        // ------------------------------------------------------------------------------------------------------
        // 1 - Items Offers Free Shipping
        $items_free_shipping = !$best_items->contains(fn ($item) => $item['offer_free_shipping'] == 0);

        // 2 - Order Offer Free Shipping
        $order_offer_free_shipping = $order_offer ? $order_offer->free_shipping : false;

        // 3 - Coupon Free Shipping
        $coupon_free_shipping = $order->coupon ? $order->coupon->free_shipping : false;

        // 4 - Total Order Free Shipping (After Items & Order Offers)
        $total_order_free_shipping = $items_free_shipping || $order_offer_free_shipping || $coupon_free_shipping;

        // Calculate Shipping Fees
        if (!$total_order_free_shipping && $items_total_quantities) {
            $items_total_shipping_weights = $best_items->sum('total_shipping_weight');

            // Get the best Delivery Cost
            $min_charge = $zone->min_charge;
            $min_weight = $zone->min_weight;
            $kg_charge = $zone->kg_charge;
            $shipping_fees = $items_total_shipping_weights < $min_weight ? $min_charge : $min_charge + ($items_total_shipping_weights - $min_weight) * $kg_charge;
        } else {
            $shipping_fees = 0.00;
        }

        // ------------------------------------------------------------------------------------------------------
        // C - Prices
        // ------------------------------------------------------------------------------------------------------

        // 1 - Base Items Prices
        $items_total_base_prices = $best_items->sum('total_base_price');
        // 2 - Final Items prices (Base Price - Item Discount)
        $items_total_final_prices =  $best_items->sum('total_final_price');
        $items_total_discounts = $items_total_base_prices - $items_total_final_prices;
        $items_discounts_percentage = $items_total_base_prices ? round(($items_total_discounts * 100) / $items_total_base_prices, 2) : 0;
        // 3 - After Offers Prices (Final Price - Offers Discount)
        $total_after_offer_prices = $best_items->sum('total_after_offer_price');
        $offers_total_discounts = $best_items->sum('total_offer_discount');
        $offers_discounts_percentage = $items_total_final_prices ? round(($offers_total_discounts * 100) / $items_total_final_prices, 2) : 0;

        //  4- Order Discount
        $order_discount = $order_offer && $order_offer->type == 0 && $order_offer->value <= 100 ? $total_after_offer_prices * ($order_offer->value / 100) : ($order_offer && $order_offer->type == 1 ? $total_after_offer_prices - $order_offer->value > 0 ? $order_offer->value : $total_after_offer_prices : 0);
        $order_discount_percent = $order_offer && $order_offer->type == 0 && $order_offer->value <= 100 ? round($order_offer->value, 2) : ($order_offer && $order_offer->type == 1 && $total_after_offer_prices ? round(($order_discount * 100) / $total_after_offer_prices, 2) : 0);

        // 5 - Prices After Order Offer
        $total_after_order_discount = $total_after_offer_prices - $order_discount;

        // * - Get Order Coupon Discount
        if ($order_best_coupon['value'] > 0) {
            if ($order_best_coupon['type'] == 0 && $order_best_coupon['value'] <= 100) {
                $coupon_order_discount = $total_after_order_discount * $order_best_coupon['value'] / 100;
                $coupon_order_points = 0;
            } elseif ($order_best_coupon['type'] == 1) {
                $coupon_order_discount = $order_best_coupon['value'] <= $total_after_order_discount ? $order_best_coupon['value'] : $total_after_order_discount;
                $coupon_order_points = 0;
            } elseif ($order_best_coupon['type'] == 2) {
                $coupon_order_discount = 0;
                $coupon_order_points = $order_best_coupon['value'];
            }
        } else {
            $coupon_order_discount = 0;
            $coupon_order_points = 0;
        }

        $total_coupon_discount = ($coupon_order_discount + $coupon_total_discount) < $total_after_order_discount ? $coupon_order_discount + $coupon_total_discount : 0.00;
        $total_coupon_discount_percentage = $total_after_order_discount ? round($total_coupon_discount * 100 / $total_after_order_discount, 2) : 0;

        // 6 - Total After Coupon Discounts & shipping fees
        $total_order = $total_after_order_discount - $total_coupon_discount + $shipping_fees;

        // ------------------------------------------------------------------------------------------------------
        // D - Points
        // ------------------------------------------------------------------------------------------------------

        // 1 - Items Points
        $items_total_points = round($best_items->sum('total_item_points'), 0);
        // 2 - Offers Points
        $offers_total_points = round($best_items->sum('total_offer_points'), 0);
        // 3 - Points After Offers (Items Points + Offers Points)
        $after_offers_total_points = round($best_items->sum('total_after_offer_points'), 0);
        // 4 - Points After Order Points
        $order_offers_points = $order_offer && $order_offer->type == 2 ? $order_offer->value : 0;
        $total_points_after_order_points = $after_offers_total_points + $order_offers_points;
        // 5 - Points After Coupon Points
        $total_points_after_coupon_points = $total_points_after_order_points + $coupon_order_points + $coupon_total_points;

        // Get Paid Money
        $payment_methods = $order->payment_methods;
        $main_payment_method = $order->payment->main_payment_method;
        $paid = $order->payment->paid;
        $old_price = $order->payment->total;
        $should_pay = $total_order > $paid ? round($total_order - $paid, 2) : 0.00;
        $should_get = $total_order < $paid ? round($paid - $total_order, 2) : 0.00;
        $difference = round($total_order - $paid, 2);

        DB::beginTransaction();

        try {
            $temp_order = Order::where('user_id', $order->user_id)->whereIn('status_id', [304, 305])->first() ?? new Order;

            $temp_order->fill([
                'user_id' => $order->user_id,
                'address_id' => $order->address_id,
                'phone1' => $order->phone1,
                'phone2' => $order->phone2,
                'status_id' => 304,
                'num_of_items' => $items_total_quantities,
                'allow_opening' => $order->allow_opening,
                'zone_id' => $order->zone_id,
                'coupon_id' => $order->coupon_id,
                'items_points' => $total_points_after_coupon_points,
                'offers_items_points' => $offers_total_points,
                'offers_order_points' => $order_offers_points,
                'coupon_items_points' => $coupon_total_points,
                'coupon_order_points' => $coupon_order_points,
                'gift_points' => $total_points_after_coupon_points,
                'total_weight' => $total_weight,
                'tracking_number' => $order->tracking_number,
                'package_type' => $order->package_type,
                'package_desc' => $order->package_desc,
                'order_delivery_id' => $order->order_delivery_id,
                'notes' => $order->notes,
                'old_order_id' => $order->id,
            ]);

            $temp_order->save();

            $temp_order->statuses()->attach(304);

            // Add the payment to the order
            $payment = $temp_order->payment()->updateOrCreate([
                'order_id' => $temp_order->id,
            ], [
                'subtotal_base' => $items_total_base_prices,
                'items_discount' => $items_total_discounts,
                'offers_items_discount' => $offers_total_discounts,
                'offers_order_discount' => $order_discount,
                'coupon_items_discount' => $coupon_total_discount,
                'coupon_order_discount' => $coupon_order_discount,
                'delivery_fees' => $shipping_fees,
                'total' => $total_order,
            ]);

            if ($difference > 0) {
                // Remove old transactions
                $temp_order->transactions()->delete();

                // Extract paid transactions
                $paid_transactions = [];
                foreach ($order->transactions()->where('payment_status', 2)->get() as $transaction) {
                    $paid_transactions[] = [
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_method' => $transaction->payment_method,
                        'payment_amount' => $transaction->payment_amount,
                        'payment_status' => $transaction->payment_status,
                        'paymob_order_id' => $transaction->paymob_order_id,
                        'payment_details' => $transaction->payment_details,
                    ];
                }

                // Add paid transactions
                if (count($paid_transactions)) {
                    $temp_order->transactions()->createMany($paid_transactions);
                }

                // Add the main payment method transaction
                $temp_order->transactions()->create([
                    'payment_id' => $payment->id,
                    'order_id' => $temp_order->id,
                    'old_order_id' => $order->id,
                    'user_id' => $temp_order->user_id,
                    'payment_method' => $main_payment_method ?? 1,
                    'payment_amount' => $should_pay,
                    'payment_status' => 1,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($should_pay * 100, 0, '', ''),
                        "points" => 0,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);
            } elseif ($difference < 0) {
                // Remove old transactions
                $temp_order->transactions()->delete();

                // Get old transaction done using user's Card or Vodafone Cash
                $old_order_used_other_transaction = $order->transactions
                    ->whereNotIn('payment_method', [10, 11])
                    ->where('payment_status', 2)
                    ->first();
                $old_order_used_other = $old_order_used_other_transaction ? $old_order_used_other_transaction->payment_amount : 0.00;
                $old_order_used_other_transaction_payment_details = $old_order_used_other_transaction ? json_decode($old_order_used_other_transaction->payment_details) : null;

                // Get old transaction done using user's balance
                $old_order_used_balance_transaction = $order->transactions
                    ->where('payment_method', 10)
                    ->where('payment_status', 2)
                    ->first();
                $old_order_used_balance = $old_order_used_balance_transaction ? $old_order_used_balance_transaction->payment_amount : 0.00;

                // Get old transaction done using user's points
                $old_order_used_points_transaction = $order->transactions
                    ->where('payment_method', 11)
                    ->where('payment_status', 2)
                    ->first();
                $old_order_used_points_egp = $old_order_used_points_transaction ? $old_order_used_points_transaction->payment_amount : 0.00;
                $old_order_used_points =  $old_order_used_points_egp / config('constants.constants.POINT_RATE') ?? 0;

                // Get old transaction done using user's points + balance
                $old_order_paid = $old_order_used_other + $old_order_used_balance + $old_order_used_points_egp;

                $returned__other_balance_points_egp = $old_order_paid > $total_order ? $old_order_paid - $total_order : 0.00;

                if ($returned__other_balance_points_egp) {
                    // calculate the other payment methods to be returned
                    $returned_other = $returned__other_balance_points_egp >= $old_order_used_other ? $old_order_used_other : $returned__other_balance_points_egp;

                    $returned__other_balance_points_egp -= $returned_other;

                    // calculate the points to be returned
                    $returned_points_egp = $returned__other_balance_points_egp >= $old_order_used_points_egp ? $old_order_used_points_egp : $returned__other_balance_points_egp;
                    $returned_points = $returned_points_egp / config('constants.constants.POINT_RATE');

                    $returned__other_balance_points_egp -= $returned_points_egp;

                    // calculate the balance to be returned
                    $returned_balance = $returned__other_balance_points_egp >= $old_order_used_balance ? $old_order_used_balance : $returned__other_balance_points_egp;

                    $returned__other_balance_points_egp -= $returned_balance;
                } else {
                    $returned_other = 0.00;
                    $returned_balance = 0.00;
                    $returned_points = 0;
                    $returned_points_egp = 0.00;
                }

                // Remaining Balance & Points
                $remaining_other = $old_order_used_other - $returned_other;
                $remaining_balance = $old_order_used_balance - $returned_balance;
                $remaining_points = $old_order_used_points - $returned_points;
                $remaining_points_egp = $old_order_used_points_egp - $returned_points_egp;

                if ($returned_other > 0) {
                    $temp_order->transactions()->create([
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => $returned_other,
                        'payment_method' => $old_order_used_other_transaction->payment_method,
                        'payment_status' => 4,
                        'paymob_order_id' => $old_order_used_other_transaction->paymob_order_id,
                        'payment_details' => json_encode([
                            "amount_cents" => number_format($returned_other * 100, 0, '', ''),
                            "points" => 0,
                            "transaction_id" => $old_order_used_other_transaction_payment_details->transaction_id,
                            "source_data_sub_type" => $old_order_used_other_transaction_payment_details->source_data_sub_type
                        ]),
                    ]);
                }

                if ($returned_balance > 0) {
                    $temp_order->transactions()->create([
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => $returned_balance,
                        'payment_method' => 10,
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => number_format($returned_balance * 100, 0, '', ''),
                            "points" => 0,
                            "transaction_id" => null,
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ]),
                    ]);
                }

                if ($returned_points > 0) {
                    $temp_order->transactions()->create([
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => $returned_points_egp,
                        'payment_method' => 11,
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => number_format($returned_points_egp * 100, 0, '', ''),
                            "points" => $returned_points,
                            "transaction_id" => null,
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ]),
                    ]);
                }

                if ($remaining_other > 0) {
                    $temp_order->transactions()->create([
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_method' => $old_order_used_other_transaction->payment_method,
                        'payment_status' => 2,
                        'paymob_order_id' => $old_order_used_other_transaction->paymob_order_id,
                        'payment_details' => json_encode([
                            "amount_cents" => number_format($returned_other * 100, 0, '', ''),
                            "points" => 0,
                            "transaction_id" => $old_order_used_other_transaction_payment_details->transaction_id,
                            "source_data_sub_type" => $old_order_used_other_transaction_payment_details->source_data_sub_type
                        ]),
                    ]);
                }

                if ($remaining_balance > 0) {
                    $temp_order->transactions()->create([
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => $remaining_balance,
                        'payment_method' => 10,
                        'payment_status' => 2,
                        'payment_details' => json_encode([
                            "amount_cents" => number_format($remaining_balance * 100, 0, '', ''),
                            "points" => 0,
                            "transaction_id" => null,
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ]),
                    ]);
                }

                if ($remaining_points > 0) {
                    $temp_order->transactions()->create([
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => $remaining_points_egp,
                        'payment_method' => 11,
                        'payment_status' => 2,
                        'payment_details' => json_encode([
                            "amount_cents" => number_format($remaining_points_egp * 100, 0, '', ''),
                            "points" => $remaining_points,
                            "transaction_id" => null,
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ]),
                    ]);
                }
            } else {
                // Remove old transactions
                $temp_order->transactions()->delete();

                // Extract old order transactions
                $transactions = [];
                foreach ($order->transactions as $transaction) {
                    $transactions[] = [
                        'payment_id' => $payment->id,
                        'order_id' => $temp_order->id,
                        'old_order_id' => $order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_method' => $transaction->payment_method,
                        'payment_amount' => $transaction->payment_amount,
                        'payment_status' => $transaction->payment_status,
                        'paymob_order_id' => $transaction->paymob_order_id,
                        'payment_details' => $transaction->payment_details,
                    ];
                }

                // Add transactions
                if (count($transactions)) {
                    $temp_order->transactions()->createMany($transactions);
                }
            }


            // Add Products and Collections to the order
            // get order's products
            $final_products = [];

            foreach ($best_products as $product) {
                $final_products[$product['id']] = [
                    'quantity' => $product['qty'],
                    'original_price' => $product['original_price'],
                    'price' => $product['best_price'] - $product['coupon_discount'],
                    'points' => $product['best_points'],
                    'coupon_discount' => $product['coupon_discount'],
                    'coupon_points' => $product['coupon_points'],
                ];
            };


            // get order's collections
            $final_collections = [];

            foreach ($best_collections as $collection) {
                $final_collections[$collection['id']] = [
                    'quantity' => $collection['qty'],
                    'original_price' => $collection['original_price'],
                    'price' => $collection['best_price'] - $collection['coupon_discount'],
                    'points' => $collection['best_points'],
                    'coupon_discount' => $collection['coupon_discount'],
                    'coupon_points' => $collection['coupon_points'],
                ];
            };

            // update order's products
            $temp_order->products()->detach();
            if (count($final_products)) {
                $temp_order->products()->attach(
                    $final_products
                );
            }

            // update order's collections
            $temp_order->collections()->detach();
            if (count($final_collections)) {
                $temp_order->collections()->attach(
                    $final_collections
                );
            }

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            return redirect()->back();
        }

        $order_data = [
            'order_id' => $order->id,
            'temp_order_id' => $temp_order->id ?? null,
            'products_base_prices' => $items_total_base_prices ?? 0.00,
            'products_discounts' => $items_total_discounts ?? 0.00,
            'products_discounts_percentage' => $items_discounts_percentage ?? 0,
            'products_final_prices' => $items_total_final_prices ?? 0.00,
            'offers_discounts' => $offers_total_discounts ?? 0.00,
            'offers_discounts_percentage' => $offers_discounts_percentage ?? 0,
            'order_offers_discounts' => $order_discount ?? 0.00,
            'order_offers_discounts_percentage' => $order_discount_percent ?? 0,
            'products_best_prices' => $total_after_offer_prices ?? 0.00,
            'total_points' => $total_points_after_coupon_points ?? 0,
            'coupon_discount' => $total_coupon_discount,
            'coupon_discount_percentage' => $total_coupon_discount_percentage,
            'coupon_points' => $coupon_total_points ?? 0,
            'delivery_fees' => $shipping_fees ?? 0.00,
            'used_balance' => $used_balance ?? 0.00,
            'used_points' => $used_points ?? 0,
            'used_points_egp' => $used_points_egp ?? 0.00,
            'products_total_quantities' => $items_total_quantities ?? 0,
            'order_total' => $total_order ?? 0.00,
            'old_price' => $old_price ?? 0,
            'paid' => $paid ?? 0,
            'difference' => $difference ?? 0,
            'payment_methods' => $payment_methods ?? [],
            'main_payment_method' => $main_payment_method ?? null,
            'should_pay' => $should_pay ?? 0,
            'should_get' => $should_get ?? 0,
        ];

        // return order data;
        return view('front.orders.edit_preview', compact('order_data'));
    }
    ##################### Preview Order's Edits :: End #####################

    ##################### Save Order's Edits :: Start #####################
    public function update(Request $request, $old_order_id, $temp_order_id)
    {
        $old_order = Order::with(['products', 'collections', 'user', 'payment', 'transactions', 'points'])->findOrFail($old_order_id);
        $temp_order = Order::with(['products', 'collections', 'payment', 'transactions', 'points'])->findOrFail($temp_order_id);
        $pended_balance_refund = $temp_order->transactions()->where('payment_method', 10)->where('payment_status', 4)->sum('payment_amount') ?? 0;
        $pended_points_refund = $temp_order->transactions()->where('payment_method', 11)->where('payment_status', 4)->sum('payment_details->points') ?? 0;

        // Old Products Quantities
        $returned_products = [];
        foreach ($old_order->products as $product) {
            $returned_products[$product->id] = [
                'quantity' => $product->pivot->quantity,
            ];
        }

        // New Products Data
        $new_products = [];
        if ($temp_order->products->count()) {
            foreach ($temp_order->products as $product) {
                $new_products[$product['id']] = [
                    'quantity' => $product->pivot->quantity,
                    'original_price' => $product->pivot->original_price,
                    'price' => $product->pivot->price,
                    'points' => $product->pivot->points,
                    'coupon_discount' => $product->pivot->coupon_discount,
                    'coupon_points' => $product->pivot->coupon_points,
                ];
            }
        }

        // Old Collections Quantities
        $returned_collections = [];
        foreach ($old_order->collections as $collection) {
            $returned_collections[$collection->id] = [
                'quantity' => $collection->pivot->quantity,
            ];
        }

        // New Collections Data
        $new_collections = [];
        if ($temp_order->collections->count()) {
            foreach ($temp_order->collections as $collection) {
                $new_collections[$collection['id']] = [
                    'quantity' => $collection->pivot->quantity,
                    'original_price' => $collection->pivot->original_price,
                    'price' => $collection->pivot->price,
                    'points' => $collection->pivot->points,
                    'coupon_discount' => $collection->pivot->coupon_discount,
                    'coupon_points' => $collection->pivot->coupon_points,
                ];
            }
        }

        $temp_order->collections()->attach($old_order->collections);

        DB::beginTransaction();

        try {
            // return old gift points
            $old_order->points()->where('status', 0)->delete();

            // Cash On Delivery
            if (is_null($old_order->unpaid_payment_method) || $old_order->unpaid_payment_method == 1) {
                if (editBostaOrder($temp_order, $old_order)) {
                    // Update Order Data
                    $old_order->update([
                        'status_id' => 306,
                        'num_of_items' => $temp_order->num_of_items,
                        'items_points' => $temp_order->items_points,
                        'offers_items_points' => $temp_order->offers_items_points,
                        'offers_order_points' => $temp_order->offers_order_points,
                        'coupon_items_points' => $temp_order->coupon_items_points,
                        'coupon_order_points' => $temp_order->coupon_order_points,
                        'gift_points' => $temp_order->gift_points,
                        'total_weight' => $temp_order->total_weight,
                    ]);

                    // Update order status
                    $old_order->statuses()->attach([305, 306]);

                    // Update Transactions
                    $old_order->transactions()->delete();
                    $temp_order->transactions()->update([
                        'payment_id' => $temp_order->payment->id,
                        'order_id' => $old_order->id,
                        'old_order_id' => Null,
                    ]);

                    // Update Payment
                    $old_order->payment()->delete();
                    $temp_order->payment()->update([
                        'order_id' => $old_order->id,
                    ]);

                    // Return Old Products
                    // Direct Products
                    $old_order->products()->each(function ($product) {
                        $product->quantity += $product->pivot->quantity;
                        $product->save();
                    });
                    $old_order->products()->detach();

                    // Through Collections
                    $old_order->collections()->each(function ($collection) {
                        $collection->products()->each(function ($product) use (&$collection) {
                            $product->quantity += $collection->pivot->quantity * $product->pivot->quantity;
                            $product->save();
                        });
                    });
                    $old_order->collections()->detach();


                    // Subtract New Products
                    // Direct Products
                    $temp_order->products()->each(function ($product) use ($temp_order) {
                        $product->quantity -= $product->pivot->quantity;
                        $product->save();
                    });
                    $old_order->products()->attach($new_products);

                    // Through Collections
                    $temp_order->collections()->each(function ($collection) use ($temp_order) {
                        $collection->products()->each(function ($product) use (&$collection) {
                            $product->quantity -= ($collection->pivot->quantity * $product->pivot->quantity);
                            $product->save();
                        });
                    });
                    $old_order->collections()->attach($new_collections);

                    // Return Balance
                    if ($pended_balance_refund) {
                        $old_order->user()->update([
                            'balance' => $old_order->user->balance + $pended_balance_refund,
                        ]);

                        $old_order->transactions()->where('payment_method', 10)->where('payment_status', 4)->update([
                            'payment_status' => 5
                        ]);
                    }

                    // Return Points
                    if ($pended_points_refund) {
                        $old_order->user->points()->create([
                            'order_id' => $old_order->id,
                            'value' => $pended_points_refund,
                            'status' => 1
                        ]);

                        $old_order->transactions()->where('payment_method', 11)->where('payment_status', 4)->update([
                            'payment_status' => 5
                        ]);
                    }

                    // Add gift points
                    // Add Points to the user if present
                    if ($temp_order->gift_points) {
                        $old_order->user->points()->create([
                            'order_id' => $old_order->id,
                            'value' => $temp_order->gift_points,
                            'status' => 0
                        ]);
                    }


                    $temp_order->forceDelete();
                } else {
                    Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                    return redirect()->route('front.orders.index');
                }
            }

            // Card or installment
            elseif ($old_order->unpaid_payment_method == 2 || $old_order->unpaid_payment_method == 3) {
                if ($temp_order->should_get > 0 && $request->type == 'wallet') {
                    if (editBostaOrder($temp_order, $old_order_id)) {

                        try {
                            $old_order->update([
                                'status_id' => 12,
                                'num_of_items' => $temp_order->num_of_items,
                                'coupon_order_discount' => $temp_order->coupon_order_discount,
                                'coupon_order_points' => $temp_order->coupon_order_points,
                                'coupon_products_discount' => $temp_order->coupon_products_discount,
                                'coupon_products_points' => $temp_order->coupon_products_points,
                                'subtotal_base' => $temp_order->subtotal_base,
                                'subtotal_final' => $temp_order->subtotal_final,
                                'total' => $temp_order->total,
                                'delivery_fees' => $temp_order->delivery_fees,
                                'should_pay' => $temp_order->should_pay,
                                'should_get' => $temp_order->should_get,
                                'used_points' => $temp_order->used_points,
                                'used_balance' => $temp_order->used_balance,
                                'gift_points' => $temp_order->gift_points,
                                'total_weight' => $temp_order->total_weight,
                            ]);

                            $old_order->statuses()->attach([16, 12]);

                            $payment = [
                                'order_id' => $old_order->id,
                                'user_id' => $old_order->user_id,
                                'payment_amount' => -1 * $temp_order->should_get,
                                'payment_method' => 10,
                                'payment_status' => 4,
                                'payment_details' => null,
                            ];

                            $old_order->payments()->create($payment);

                            $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
                                $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
                                $product->save();
                            });

                            $old_order->products()->sync($order_products);

                            $old_order->user()->update([
                                'balance' => $old_order->user->balance + $returned_balance + $temp_order->should_get,
                                'points' => $old_order->user->points + $returned_points + $returned_gift_points,
                            ]);

                            $temp_order->forceDelete();

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
                } elseif ($temp_order->should_get > 0 && $request->type == 'card') {
                    $old_payment = $old_order->payments()
                        ->where('payment_amount', '>=', $temp_order->should_get)
                        ->where('payment_status', 2)
                        ->first();

                    while (is_null($old_payment)) {
                        $old_small_payment = $old_order
                            ->payments()
                            ->where('payment_status', 2)
                            ->where('payment_amount', '>', 0)
                            ->first();

                        $payment = [
                            'order_id' => $temp_order->id,
                            'user_id' => $temp_order->user_id,
                            'payment_amount' => -1 * $old_small_payment->payment_amount,
                            'payment_method' => $old_order->unpaid_payment_method,
                            'payment_status' => 5,
                            'payment_details' => $old_small_payment->payment_details,
                            'old_order_id' => $old_order->id
                        ];

                        $new_payment = $temp_order->payments()->updateOrCreate([
                            'order_id' => $payment['order_id'],
                            'payment_status' => 5,
                        ], $payment);

                        if (refundRequestPaymob(json_decode($new_payment->payment_details)->transaction_id, abs($new_payment->payment_amount))) {
                            $temp_order->payments()->first()->update([
                                'order_id' => $old_order->id,
                                'payment_status' => 4,
                            ]);

                            $old_small_payment->update([
                                'payment_amount' => 0,
                            ]);

                            $temp_order->update([
                                'should_get' => $temp_order->should_get - abs($new_payment->payment_amount)
                            ]);
                        }

                        $old_payment = $old_order->payments()
                            ->where('payment_amount', '>=', $temp_order->should_get)
                            ->where('payment_status', 2)
                            ->first();
                    }

                    $payment = [
                        'order_id' => $temp_order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => -1 * $temp_order->should_get,
                        'payment_method' => $old_order->unpaid_payment_method,
                        'payment_status' => 1,
                        'payment_details' => $old_payment->payment_details,
                        'old_order_id' => $old_order->id
                    ];

                    $temp_order->payments()->updateOrCreate([
                        'order_id' => $payment['order_id'],
                        'payment_status' => 1,
                    ], $payment);

                    if (refundRequestPaymob(json_decode($payment['payment_details'])->transaction_id, abs($payment['payment_amount'])) && editBostaOrder($temp_order, $old_order_id)) {
                        try {
                            $old_order->update([
                                'status_id' => 12,
                                'num_of_items' => $temp_order->num_of_items,
                                'coupon_order_discount' => $temp_order->coupon_order_discount,
                                'coupon_order_points' => $temp_order->coupon_order_points,
                                'coupon_products_discount' => $temp_order->coupon_products_discount,
                                'coupon_products_points' => $temp_order->coupon_products_points,
                                'subtotal_base' => $temp_order->subtotal_base,
                                'subtotal_final' => $temp_order->subtotal_final,
                                'total' => $temp_order->total,
                                'delivery_fees' => $temp_order->delivery_fees,
                                'should_pay' => 0,
                                'should_get' => 0,
                                'used_points' => $temp_order->used_points,
                                'used_balance' => $temp_order->used_balance,
                                'gift_points' => $temp_order->gift_points,
                                'total_weight' => $temp_order->total_weight,
                            ]);

                            $old_order->statuses()->attach([16, 12]);

                            $temp_order->payments()->first()->update([
                                'order_id' => $old_order->id,
                                'payment_status' => 4,
                            ]);

                            $old_payment->update([
                                'payment_amount' => $old_payment->payment_amount - $temp_order->should_get,
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

                            $temp_order->forceDelete();

                            DB::commit();

                            Session::flash('success', __('front/homePage.Order edit request sent successfully'));
                            return redirect()->route('front.orders.index');
                        } catch (\Throwable $th) {
                            DB::rollBack();

                            $temp_order->payments()->first()->update([
                                'payment_status' => 3,
                            ]);

                            Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                            return redirect()->route('front.orders.index');
                        }
                    } else {
                        $temp_order->payments()->first()->update([
                            'payment_status' => 3,
                        ]);

                        Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
                        return redirect()->route('front.orders.index');
                    }
                } elseif ($temp_order->should_pay == 0 && $temp_order->should_get == 0 && $request->type == 'equal') {
                    if (editBostaOrder($temp_order, $old_order_id)) {
                        try {
                            $old_order->update([
                                'status_id' => 12,
                                'num_of_items' => $temp_order->num_of_items,
                                'coupon_order_discount' => $temp_order->coupon_order_discount,
                                'coupon_order_points' => $temp_order->coupon_order_points,
                                'coupon_products_discount' => $temp_order->coupon_products_discount,
                                'coupon_products_points' => $temp_order->coupon_products_points,
                                'subtotal_base' => $temp_order->subtotal_base,
                                'subtotal_final' => $temp_order->subtotal_final,
                                'total' => $temp_order->total,
                                'delivery_fees' => $temp_order->delivery_fees,
                                'should_pay' => $temp_order->should_pay,
                                'should_get' => $temp_order->should_get,
                                'used_points' => $temp_order->used_points,
                                'used_balance' => $temp_order->used_balance,
                                'gift_points' => $temp_order->gift_points,
                                'total_weight' => $temp_order->total_weight,
                            ]);

                            $old_order->statuses()->attach([16, 12]);

                            $order_products = [];
                            $returned_products = [];

                            foreach ($temp_order->products as $product) {
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

                            $temp_order->forceDelete();

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
                } elseif ($temp_order->should_pay > 0 && $request->type == 'pay') {
                    $payment = [
                        'order_id' => $temp_order->id,
                        'old_order_id' => $old_order->id,
                        'user_id' => $temp_order->user_id,
                        'payment_amount' => $temp_order->should_pay,
                        'payment_method' => $old_order->unpaid_payment_method,
                        'payment_status' => 1,
                    ];

                    $payment = $temp_order->payments()->updateOrCreate([
                        'order_id' => $payment['order_id'],
                        'payment_status' => 1,
                    ], $payment);

                    DB::commit();

                    $payment_key = payByPaymob($payment);

                    if ($payment_key) {
                        return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . ($old_order->unpaid_payment_method == 3 ? env('PAYMOB_IFRAM_ID_INSTALLMENTS') : env('PAYMOB_IFRAM_ID_CARD_TEST')) . "?payment_token=$payment_key");
                    } else {
                        return redirect()->route('front.orders.payment')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
                    }
                }
            }

            // Vodafone Cash
            // elseif ($old_order->unpaid_payment_method == 4) {
            //     // Old Order Paid
            //     if ($old_order->should_pay == 0) {
            //         // Pay the Difference
            //         if ($temp_order->should_pay > 0 && $request->type == 'pay') {
            //             $payment = [
            //                 'order_id' => $old_order->id,
            //                 'old_order_id' => null,
            //                 'user_id' => $temp_order->user_id,
            //                 'payment_amount' => $temp_order->should_pay,
            //                 'payment_method' => 4,
            //                 'payment_status' => 1,
            //             ];

            //             $old_order->payments()->updateOrCreate([
            //                 'order_id' => $payment['order_id'],
            //                 'payment_status' => 1,
            //             ], $payment);

            //             $old_order->update([
            //                 'status_id' => 2,
            //                 'num_of_items' => $temp_order->num_of_items,
            //                 'coupon_order_discount' => $temp_order->coupon_order_discount,
            //                 'coupon_order_points' => $temp_order->coupon_order_points,
            //                 'coupon_products_discount' => $temp_order->coupon_products_discount,
            //                 'coupon_products_points' => $temp_order->coupon_products_points,
            //                 'subtotal_base' => $temp_order->subtotal_base,
            //                 'subtotal_final' => $temp_order->subtotal_final,
            //                 'total' => $temp_order->total,
            //                 'delivery_fees' => $temp_order->delivery_fees,
            //                 'should_pay' => $temp_order->should_pay,
            //                 'should_get' => $temp_order->should_get,
            //                 'used_points' => $temp_order->used_points,
            //                 'used_balance' => $temp_order->used_balance,
            //                 'gift_points' => $temp_order->gift_points,
            //                 'total_weight' => $temp_order->total_weight,
            //             ]);

            //             $old_order->statuses()->attach([16, 12, 2]);

            //             $old_order->products()->sync($order_products);

            //             $old_order->user()->update([
            //                 'balance' => $old_order->user->balance + $returned_balance + $temp_order->should_get,
            //                 'points' => $old_order->user->points + $returned_points + $returned_gift_points,
            //             ]);

            //             // edit products database
            //             $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                 $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                 $product->save();
            //             });

            //             $temp_order->forceDelete();

            //             DB::commit();

            //             return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order edit request sent successfully'));
            //         }
            //         // Old Order Price Equal New Order Price
            //         elseif ($temp_order->should_pay == 0.00 && $request->type == 'equal') {
            //             if (editBostaOrder($temp_order, $old_order_id)) {
            //                 try {
            //                     $old_order->update([
            //                         'status_id' => 12,
            //                         'num_of_items' => $temp_order->num_of_items,
            //                         'coupon_order_discount' => $temp_order->coupon_order_discount,
            //                         'coupon_order_points' => $temp_order->coupon_order_points,
            //                         'coupon_products_discount' => $temp_order->coupon_products_discount,
            //                         'coupon_products_points' => $temp_order->coupon_products_points,
            //                         'subtotal_base' => $temp_order->subtotal_base,
            //                         'subtotal_final' => $temp_order->subtotal_final,
            //                         'total' => $temp_order->total,
            //                         'delivery_fees' => $temp_order->delivery_fees,
            //                         'should_pay' => $temp_order->should_pay,
            //                         'should_get' => $temp_order->should_get,
            //                         'used_points' => $temp_order->used_points,
            //                         'used_balance' => $temp_order->used_balance,
            //                         'gift_points' => $temp_order->gift_points,
            //                         'total_weight' => $temp_order->total_weight,
            //                     ]);

            //                     $old_order->statuses()->attach([16, 12]);

            //                     $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                         $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                         $product->save();
            //                     });

            //                     $old_order->products()->sync($order_products);

            //                     $old_order->user()->update([
            //                         'balance' => $old_order->user->balance + $returned_balance,
            //                         'points' => $old_order->user->points + $returned_points + $returned_gift_points,
            //                     ]);

            //                     // edit products database
            //                     $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                         $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                         $product->save();
            //                     });

            //                     $temp_order->forceDelete();

            //                     DB::commit();

            //                     Session::flash('success', __('front/homePage.Order edit request sent successfully'));
            //                     return redirect()->route('front.orders.index');
            //                 } catch (\Throwable $th) {
            //                     DB::rollBack();

            //                     Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
            //                     return redirect()->route('front.orders.index');
            //                 }
            //             } else {
            //                 Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
            //                 return redirect()->route('front.orders.index');
            //             }
            //         }
            //         // Get the Difference
            //         // To Vodafone Wallet
            //         elseif ($temp_order->should_get > 0 && $request->type == 'vodafone') {
            //             $payment = [
            //                 'order_id' => $old_order->id,
            //                 'old_order_id' => null,
            //                 'user_id' => $temp_order->user_id,
            //                 'payment_amount' => -1 * $temp_order->should_get,
            //                 'payment_method' => 4,
            //                 'payment_status' => 5,
            //             ];

            //             $payment = $old_order->payments()->updateOrCreate([
            //                 'order_id' => $payment['order_id'],
            //                 'payment_status' => 5,
            //             ], $payment);

            //             $old_order->update([
            //                 'status_id' => 14,
            //                 'num_of_items' => $temp_order->num_of_items,
            //                 'coupon_order_discount' => $temp_order->coupon_order_discount,
            //                 'coupon_order_points' => $temp_order->coupon_order_points,
            //                 'coupon_products_discount' => $temp_order->coupon_products_discount,
            //                 'coupon_products_points' => $temp_order->coupon_products_points,
            //                 'subtotal_base' => $temp_order->subtotal_base,
            //                 'subtotal_final' => $temp_order->subtotal_final,
            //                 'total' => $temp_order->total,
            //                 'delivery_fees' => $temp_order->delivery_fees,
            //                 'should_pay' => $temp_order->should_pay,
            //                 'should_get' => $temp_order->should_get,
            //                 'used_points' => $temp_order->used_points,
            //                 'used_balance' => $temp_order->used_balance,
            //                 'gift_points' => $temp_order->gift_points,
            //                 'total_weight' => $temp_order->total_weight,
            //             ]);

            //             $old_order->statuses()->attach([11, 12, 14]);

            //             $old_order->products()->sync($order_products);

            //             $old_order->user()->update([
            //                 'balance' => $old_order->user->balance + $returned_balance + $temp_order->should_get,
            //                 'points' => $old_order->user->points + $returned_points + $returned_gift_points,
            //             ]);

            //             // edit products database
            //             $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                 $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                 $product->save();
            //             });

            //             $temp_order->forceDelete();

            //             DB::commit();

            //             return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order edit request sent successfully'));
            //         }
            //         // To Balance
            //         elseif ($temp_order->should_get > 0 && $request->type == 'wallet') {
            //             if (editBostaOrder($temp_order, $old_order_id)) {
            //                 try {
            //                     $old_order->update([
            //                         'status_id' => 12,
            //                         'num_of_items' => $temp_order->num_of_items,
            //                         'coupon_order_discount' => $temp_order->coupon_order_discount,
            //                         'coupon_order_points' => $temp_order->coupon_order_points,
            //                         'coupon_products_discount' => $temp_order->coupon_products_discount,
            //                         'coupon_products_points' => $temp_order->coupon_products_points,
            //                         'subtotal_base' => $temp_order->subtotal_base,
            //                         'subtotal_final' => $temp_order->subtotal_final,
            //                         'total' => $temp_order->total,
            //                         'delivery_fees' => $temp_order->delivery_fees,
            //                         'should_pay' => $temp_order->should_pay,
            //                         'should_get' => 0.00,
            //                         'used_points' => $temp_order->used_points,
            //                         'used_balance' => $temp_order->used_balance,
            //                         'gift_points' => $temp_order->gift_points,
            //                         'total_weight' => $temp_order->total_weight,
            //                     ]);

            //                     $old_order->statuses()->attach([16, 12]);

            //                     $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                         $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                         $product->save();
            //                     });

            //                     $old_order->products()->sync($order_products);

            //                     $old_order->user()->update([
            //                         'balance' => $old_order->user->balance + $returned_balance + $temp_order->should_get,
            //                         'points' => $old_order->user->points + $returned_points + $returned_gift_points,
            //                     ]);

            //                     // edit products database
            //                     $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                         $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                         $product->save();
            //                     });

            //                     $temp_order->forceDelete();

            //                     DB::commit();

            //                     Session::flash('success', __('front/homePage.Order edit request sent successfully'));
            //                     return redirect()->route('front.orders.index');
            //                 } catch (\Throwable $th) {
            //                     throw $th;
            //                     DB::rollBack();

            //                     Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
            //                     return redirect()->route('front.orders.index');
            //                 }
            //             } else {
            //                 Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
            //                 return redirect()->route('front.orders.index');
            //             }
            //         }
            //     }
            //     // Old Order Not Paid
            //     elseif ($old_order->should_pay > 0) {
            //         try {
            //             $old_order->update([
            //                 'status_id' => 2,
            //                 'num_of_items' => $temp_order->num_of_items,
            //                 'coupon_order_discount' => $temp_order->coupon_order_discount,
            //                 'coupon_order_points' => $temp_order->coupon_order_points,
            //                 'coupon_products_discount' => $temp_order->coupon_products_discount,
            //                 'coupon_products_points' => $temp_order->coupon_products_points,
            //                 'subtotal_base' => $temp_order->subtotal_base,
            //                 'subtotal_final' => $temp_order->subtotal_final,
            //                 'total' => $temp_order->total,
            //                 'delivery_fees' => $temp_order->delivery_fees,
            //                 'should_pay' => $temp_order->should_pay,
            //                 'should_get' => $temp_order->should_get,
            //                 'used_points' => $temp_order->used_points,
            //                 'used_balance' => $temp_order->used_balance,
            //                 'gift_points' => $temp_order->gift_points,
            //                 'total_weight' => $temp_order->total_weight,
            //             ]);

            //             $payment = $old_order->payments()->where('payment_status', 1)->first();

            //             if ($payment) {
            //                 $payment->update([
            //                     'payment_amount' => $old_order->should_pay,
            //                 ]);
            //             }

            //             $old_order->statuses()->attach([16, 12, 2]);

            //             $old_order->products()->sync($order_products);

            //             $old_order->user()->update([
            //                 'balance' => $old_order->user->balance + $returned_balance + $temp_order->should_get,
            //                 'points' => $old_order->user->points + $returned_points + $returned_gift_points,
            //             ]);

            //             // edit products database
            //             $old_order->products()->each(function ($product) use ($returned_products, $order_products) {
            //                 $product->quantity = $product->quantity + $returned_products[$product->id]['quantity'] - $order_products[$product->id]['quantity'];
            //                 $product->save();
            //             });

            //             $temp_order->forceDelete();

            //             DB::commit();

            //             Session::flash('success', __('front/homePage.Order edit request sent successfully'));
            //             return redirect()->route('front.orders.index');
            //         } catch (\Throwable $th) {
            //             throw $th;
            //             DB::rollBack();

            //             Session::flash('error', __('front/homePage.Something went wrong, please try again later'));
            //             return redirect()->route('front.orders.index');
            //         }
            //     }
            // }

            DB::commit();

            // dd('sad');
            Session::flash('success', __('front/homePage.Order edit request sent successfully'));
            return redirect()->route('front.orders.index');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }
    }
    ##################### Save Order's Edits :: End #####################

    ##################### Cancel Total Order :: Start #####################
    public function cancel($order_id, $temp_order_id = null)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('payment', 'transactions')->findOrFail($order_id);

            $transactions = $order->transactions->where('payment_status', 2);

            // Return the Order
            if (returnTotalOrder($order)) {
                // Cancel Bosta Order
                if ($order->order_delivery_id) {
                    cancelBostaOrder($order);
                }

                foreach ($transactions as $transaction) {
                    if ($transaction->payment_method == 1) {
                        $transaction->update([
                            'payment_status' => 3
                        ]);
                    } elseif ($transaction->payment_method == 2 || $transaction->payment_method == 3) {
                        // Void within 24 h
                        if ($transaction->created_at->diffInDays() < 1) {
                            $transaction->update([
                                'payment_status' => 4,
                            ]);

                            $refund_status = voidRequestPaymob(json_decode($transaction->payment_details)->transaction_id);

                            if ($refund_status) {
                                $transaction->update([
                                    'payment_status' => 5,
                                ]);
                            } else {
                                $order->transactions()->create([
                                    'payment_id' => $transaction->payment_id,
                                    'order_id' => $transaction->order_id,
                                    'user_id' => $transaction->user_id,
                                    'payment_amount' => $transaction->payment_amount,
                                    'payment_method' => $transaction->payment_method,
                                    'payment_status' => 6,
                                    'paymob_order_id' => $transaction->paymob_order_id,
                                    'payment_details' => $transaction->payment_details,
                                ]);

                                $transaction->update([
                                    'payment_status' => 2,
                                ]);

                                $order->update(['status_id' => 405]);
                                $order->statuses()->attach(405);
                            }
                        }

                        // Refund after 24 h
                        else {
                            $transaction->update([
                                'payment_status' => 4,
                            ]);

                            $refund_status = refundRequestPaymob(json_decode($transaction->payment_details)->transaction_id, json_decode($transaction->payment_details)->amount_cents);

                            if ($refund_status) {
                                $transaction->update([
                                    'payment_status' => 5,
                                ]);
                            } else {
                                $order->transactions()->create([
                                    'payment_id' => $transaction->payment_id,
                                    'order_id' => $transaction->order_id,
                                    'user_id' => $transaction->user_id,
                                    'payment_amount' => $transaction->payment_amount,
                                    'payment_method' => $transaction->payment_method,
                                    'payment_status' => 6,
                                    'paymob_order_id' => $transaction->paymob_order_id,
                                    'payment_details' => $transaction->payment_details,
                                ]);

                                $transaction->update([
                                    'payment_status' => 2,
                                ]);

                                $order->update(['status_id' => 405]);
                                $order->statuses()->attach(405);
                            }
                        }
                    } elseif ($transaction->payment_method == 4) {
                        $transaction->update([
                            'payment_status' => 4,
                        ]);
                    } elseif ($transaction->payment_method == 10) {
                        // Return balance to user
                        $order->user->balance += $transaction->payment_amount;
                        $order->user->save();

                        // Update transaction status :: Refunded
                        $transaction->update([
                            'payment_status' => 5,
                        ]);
                    } elseif ($transaction->payment_method == 11) {
                        $order->points()->create([
                            'user_id' => $order->user_id,
                            'value' => json_decode($transaction->payment_details)->points,
                            'status' => 1
                        ]);
                    }
                }
            }

            if ($temp_order_id != null) {
                $temp_order = Order::findOrFail($temp_order_id);

                $temp_order->points()->delete();
                // $temp_order->products()->delete();
                // $temp_order->collections()->delete();

                $temp_order->forceDelete();
            }

            DB::commit();

            return redirect()->route('front.orders.index')->with('success', __('front/homePage.Order Canceled Successfully'));
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }
    ##################### Cancel Total Order :: End #####################

    ##################### Return Total Order :: Start #####################
    public function return(Request $request, $order_id)
    {
        $order = Order::with([
            'products' => fn ($q) => $q->with('thumbnail')
        ])->findOrFail($order_id);

        return view('front.orders.return_products', compact('order'));
    }
    ##################### Return Total Order :: End #####################

    ##################### Preview Returned Products :: Start #####################
    public function returnCalc($order_id, Request $request)
    {
        // get old order data from db
        $order = Order::with([
            'products',
            'zone'
        ])->findOrFail($order_id);

        if ($request->type == "return") {
            $request = new Request([
                'products_ids' => $order->products->pluck('id')->toArray(),
                'quantities' => $order->products->pluck('pivot.quantity')->toArray(),
            ]);
        }

        // Validation
        $request->validate([
            'products_ids.*' => 'exists:products,id',
            'quantities.*' => 'numeric|min:0',
        ]);

        // products ids
        $products_ids = $request->products_ids;

        ############ Return Order Data :: Start ############
        // Get Order Products ids and quantities from request
        $returned_products_quantities = array_combine($request->products_ids, $request->quantities);
        $returned_products_total_quantities = array_sum($returned_products_quantities);

        // Get Order Products ids and quantities from Order DB
        $products = $order->products;
        $old_products_quantities = $products->mapWithKeys(fn ($product) => [$product->id => $product->pivot->quantity]);
        $old_products_total_quantities = $old_products_quantities->sum();

        // Validate Quantities
        $quantities_check = collect($returned_products_quantities)->map(function ($quantity, $id) use ($old_products_quantities) {
            if ($quantity <= $old_products_quantities[$id]) {
                return true;
            }
            return false;
        })->doesntContain(false);

        if (!$quantities_check) {
            return redirect()->back()->withErrors(['quantities' => __('front/homePage.max exceeded')]);
        }

        // total price & points of returned products
        $returned_products_data = $products->map(function ($product) use ($returned_products_quantities) {
            $returned_quantity = $returned_products_quantities[$product->id];

            // Best Prices
            $returned_product_best_price = $returned_quantity * $product->pivot->price;
            $returned_products_best_points = $returned_quantity * $product->pivot->points;

            // Coupons
            $returned_products_coupon_discount = $returned_quantity * $product->pivot->coupon_discount;
            $returned_products_coupon_points = $returned_quantity * $product->pivot->coupon_points;

            // Total
            $returned_price = $returned_quantity * $product->pivot->price -  $returned_quantity * $product->pivot->coupon_discount;
            $returned_points = $returned_quantity * $product->pivot->points + $returned_quantity * $product->pivot->coupon_points;

            // Weight
            $weight = $returned_quantity * $product->weight;

            return [
                'id' => $product->id,
                'returned_quantity' => $returned_quantity,
                'returned_product_best_price' => $returned_product_best_price,
                'returned_products_best_points' => $returned_products_best_points,
                'returned_products_coupon_discount' => $returned_products_coupon_discount,
                'returned_products_coupon_points' => $returned_products_coupon_points,
                'returned_price' => $returned_price,
                'returned_points' => $returned_points,
                'weight' => $weight
            ];
        });

        //  Total Best Price of returned products
        $returned_products_best_price = $returned_products_data->sum('returned_product_best_price');

        //  Total Best Points of returned products
        $returned_products_best_points = $returned_products_data->sum('returned_product_best_points');

        //  Total Coupon Discount of returned products
        $returned_products_coupon_discount = $returned_products_data->sum('returned_products_coupon_discount');

        //  Total Coupon Points of returned products
        $returned_products_coupon_points = $returned_products_data->sum('returned_products_coupon_points');

        //  Total Price of returned products
        $returned_price = $returned_products_data->sum('returned_price');

        //  Total Points of returned products
        $returned_points = $returned_products_total_quantities == $old_products_total_quantities ? $order->gift_points : $returned_products_data->sum('returned_points');

        // Get summation of products final prices
        $products_final_prices = $products->sum(function ($product) use ($returned_products_quantities) {
            return $product->final_price * $returned_products_quantities[$product->id];
        });
        ############ Return Order Data :: End ############

        ############ Old Order Data :: Start ############
        $old_total = $order->total;
        $payment_method = $order->payment_method;
        $old_used_balance = $order->used_balance;
        $old_used_points = $order->used_points;
        $old_used_points_egp = $old_used_points * config('constants.constants.POINT_RATE');

        $old_gift_points = $order->gift_points;
        $old_coupon_points = $order->coupon_products_points + $order->coupon_order_points;
        ############ Old Order Data :: End ############

        ###################### Delivery Fees :: Start ######################
        // Get zone data
        $zone = $order->zone;

        // Total returned products weight
        $returned_weight = $returned_products_data->sum('weight');

        // returning fees
        $returning_fees = $returned_products_total_quantities > 0 ? ($returned_weight < $zone->min_weight ? $zone->min_charge : $zone->min_charge + ($returned_weight - $zone->min_weight) * $zone->kg_charge) : 0.00;
        ###################### Delivery Fees :: End ######################


        // Returned Order Subtotal (Before subtraction of used points or balance)
        $return_subtotal = $returning_fees - $returned_price;

        if ($return_subtotal <= 0) {
            // Return the used points to the user's Points
            $returned_to_points_egp = $old_used_points_egp <= abs($return_subtotal) ? $old_used_points_egp : abs($return_subtotal);
            $returned_to_points =  $returned_to_points_egp / config('constants.constants.POINT_RATE');

            // Returned Order Subtotal (After subtraction of used points only)
            $return_total = $return_subtotal + $returned_to_points_egp;

            // Return the used balance to the user's Balance
            $returned_to_balance = $old_used_balance <= abs($return_total) ? $old_used_balance : abs($return_total);

            // Returned Order Subtotal (After subtraction of used points or balance)
            $return_total += $returned_to_balance;
        } else {
            $returned_to_points_egp = 0;
            $returned_to_points =  0;
            $returned_to_balance = 0;
            $return_total = $return_subtotal;
        }


        $return_total = abs($return_total) <= $order->total ? $return_total : -1 * ($order->total);

        DB::beginTransaction();

        try {
            $new_order = Order::whereIn('status_id', [7, 17])->where([
                'user_id' => $order->user_id,
                'old_order_id' => $order->id
            ])->first() ?? new Order;

            $new_order->fill([
                'user_id' => $order->user_id,
                'old_order_id' => $order->id,
                'status_id' => 7,
                'address_id' => $order->address_id,
                'phone1' => $order->phone1,
                'phone2'    => $order->phone2,
                'package_type' => $order->package_type,
                'package_desc' => $order->package_desc,
                'num_of_items' => $returned_products_total_quantities,
                'allow_opening' => $order->allow_opening,
                'zone_id'   => $order->zone_id,
                'coupon_id' => $order->coupon_id,
                'coupon_order_discount' => 0,
                'coupon_order_points' => 0,
                'coupon_products_discount' => $returned_products_coupon_discount,
                'coupon_products_points' => $returned_products_coupon_points,
                'subtotal_base' => $products_final_prices,
                'subtotal_final' => $returned_price,
                'total' => $return_total,
                'should_pay' => $return_total >= 0 ? $return_total : 0.00,
                'should_get' => $return_total <= 0 ? abs($return_total) : 0.00,
                'used_points' => -1 * $returned_to_points,
                'used_balance' => -1 * $returned_to_balance,
                'gift_points' => -1 * $returned_points,
                'delivery_fees' => $returning_fees,
                'total_weight' => $returned_weight,
                'payment_method' => $payment_method,
                'notes' => $order->notes,
                'old_order_id' => $order->id
            ]);

            $new_order->save();

            // Save Order Request

            // Add Status (Under Returning)
            if ($new_order->statuses()->count() == 0 || $new_order->statuses()->orderBy('pivot_created_at', 'desc')->first()->id != 7) {
                $new_order->statuses()->attach(7);
            }

            // Products data
            $return_order_products = [];

            foreach ($products_ids as $product_id) {
                $return_order_products[$product_id] = [
                    'quantity' => $returned_products_quantities[$product_id],
                    'price' => $products->find($product_id)->pivot->price,
                    'points' => $products->find($product_id)->pivot->points,
                    'coupon_discount' => $products->find($product_id)->pivot->coupon_discount,
                    'coupon_points' => $products->find($product_id)->pivot->coupon_points,
                ];
            }

            // Attach the return products to the order
            $new_order->products()->sync($return_order_products);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back();
        }

        $order_data = [
            "new_order_id" => $new_order->id,
            "old_order_id" => $order->id,
            "old_order_total" => $old_total,
            "old_products_total_quantities" => $old_products_total_quantities,
            "old_product_gift_points" => $old_gift_points,
            "payment_method" => $payment_method,
            "returned_price" => $returned_price,
            "returned_points" => $returned_points,
            "returning_fees" => $returning_fees,
            "returned_products_total_quantities" => $returned_products_total_quantities,
            "return_subtotal" => $return_subtotal,
            "return_total" => $return_total,
            "returned_to_points_egp" => $returned_to_points_egp,
            "returned_to_points" => $returned_to_points,
            "returned_to_balance" => $returned_to_balance,
        ];

        return view('front.orders.return_preview', compact('order_data'));
    }
    ##################### Preview Returned Products :: End #####################

    ##################### Confirm Returned Products :: Start #####################
    public function returnConfirm($order_id, Request $request)
    {
        $order = Order::findOrFail($order_id);

        $order->update([
            'payment_method' => $request->type == "cod" ? 1 : ($request->type == "card" ? 2 : ($request->type == "vodafone" ? 4 : ($request->type == "wallet" ? 10 : 0))),
            'status_id' => 17
        ]);

        $order->statuses()->attach(17);

        return redirect()->route('front.orders.done');
    }
    ##################### Confirm Returned Products :: End #####################

    ##################### Cancel Returning Request :: Start #####################
    public function returnCancel($order_id)
    {
        $order = Order::findOrFail($order_id);

        $order->forceDelete();

        return redirect()->back()->with('success', __('front/homePage.Returning request has been deleted successfully'));
    }
    ##################### Cancel Returning Request :: End #####################

    ##################### Go To Shipping Details During Placing the Order :: Start #####################
    public function shipping()
    {
        $cart_products_id = [];
        $products_id = [];
        $cart_collections_id = [];
        $collections_id = [];

        // get items id from cart
        Cart::instance('cart')->content()->map(function ($item) use (&$products_id, &$collections_id, &$cart_products_id, &$cart_collections_id) {
            if ($item->options->type == 'Product') {
                $products_id[] = $item->id;
                $cart_products_id[] = $item->id;
            } elseif ($item->options->type == 'Collection') {
                $collections_id[] = $item->id;
                $cart_collections_id[] = $item->id;
            }
        });

        // get items id from cart
        $products_id = array_unique($products_id);
        $collections_id = array_unique($collections_id);

        // get all items data from database with best price
        $products = getBestOfferForProducts($products_id);
        $collections = getBestOfferForCollections($collections_id);

        // put items data in cart_items variable
        $cart_products = $products->whereIn('id', $cart_products_id);
        $cart_collections = $collections->whereIn('id', $cart_collections_id);
        $cart_items = $cart_collections->concat($cart_products)->toArray();

        return view('front.orders.shipping', compact('cart_items'));
    }
    ##################### Go To Shipping Details During Placing the Order :: End #####################

    ##################### Go To Billing Details During Placing the Order :: Start #####################
    public function payment()
    {
        $cart_products_id = [];
        $products_id = [];
        $cart_collections_id = [];
        $collections_id = [];

        // get items id from cart
        Cart::instance('cart')->content()->map(function ($item) use (&$products_id, &$collections_id, &$cart_products_id, &$cart_collections_id) {
            if ($item->options->type == 'Product') {
                $products_id[] = $item->id;
                $cart_products_id[] = $item->id;
            } elseif ($item->options->type == 'Collection') {
                $collections_id[] = $item->id;
                $cart_collections_id[] = $item->id;
            }
        });

        // get items id from cart
        $products_id = array_unique($products_id);
        $collections_id = array_unique($collections_id);

        // get all items data from database with best price
        $products = getBestOfferForProducts($products_id);
        $collections = getBestOfferForCollections($collections_id);

        // put items data in cart_items variable
        $cart_products = $products->whereIn('id', $cart_products_id);
        $cart_collections = $collections->whereIn('id', $cart_collections_id);
        $cart_items = $cart_collections->concat($cart_products)->toArray();

        return view('front.orders.payment', compact('cart_items'));
    }
    ##################### Go To Billing Details During Placing the Order :: End #####################

    ##################### Confirm the Paymob Billing :: Start #####################
    public function paymentCheck(Request $request)
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
                $transaction = Transaction::with(['order' => function ($query) {
                    $query->with(['user', 'products', 'address']);
                }])
                    ->where('paymob_order_id', $data['order'])
                    ->first();

                // update transaction status
                $transaction->update([
                    'payment_status' => 2,
                    'payment_details' => json_encode([
                        "amount_cents" => $data['amount_cents'],
                        "points" => 0,
                        "transaction_id" => $data['id'],
                        "source_data_sub_type" => $data['source_data_sub_type']
                    ])
                ]);

                // get order from transaction

                // todo :: Old Orders
                if ($transaction->old_order_id != null) {
                    $old_order = Order::with(['products', 'user', 'payments'])->findOrFail($transaction->old_order_id);
                    $new_order = Order::with(['products'])->findOrFail($transaction->order_id);

                    $returned_balance = $old_order->used_balance - $new_order->used_balance;
                    $returned_points = $old_order->used_points - $new_order->used_points;
                    $returned_gift_points = $new_order->gift_points - $old_order->gift_points;

                    $new_order->update([
                        'should_pay' => 0.00
                    ]);

                    $transaction->update([
                        'order_id' => $transaction->old_order_id,
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

                    if (editBostaOrder($new_order, $transaction->old_order_id)) {
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

                            $new_order->forceDelete();

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
                    $order = $transaction->order;

                    // Order ==> New Order
                    $bosta_order = createBostaOrder($order, $transaction->payment_method);

                    if ($bosta_order['status']) {

                        // update user's Points
                        $order->points()->update([
                            'status' => 1
                        ]);

                        DB::commit();

                        // todo :: Send Email To User

                        // todo :: Send SMS To User

                        // redirect to done page
                        Session::flash('success', __('front/homePage.Order Created Successfully'));
                        return redirect()->route('front.orders.done')->with('order_id', $order->id);
                    } else {
                        DB::rollBack();

                        Session::flash('error', __('front/homePage.Order Creation Failed, Please Try Again'));
                        return redirect()->route('front.orders.payment');
                    }
                }
            } catch (\Throwable $th) {
                DB::rollBack();

                $transaction = Transaction::where('paymob_order_id', $data['order'])
                    ->first();

                $new_transaction = Transaction::create([
                    'order_id' => $transaction->order_id,
                    'old_order_id' => $transaction->old_order_id,
                    'user_id' => $transaction->user_id,
                    'payment_amount' => $transaction->payment_amount,
                    'payment_method' => $transaction->payment_method,
                    'payment_status' => $transaction->payment_status,
                    'paymob_order_id' => $transaction->paymob_order_id,
                    'payment_details' => $transaction->payment_details,
                ]);

                $transaction->update([
                    'order_id' => $transaction->order_id,
                    'payment_status' => 3,
                    'payment_details' => $data['data_message']
                ]);

                return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
            }
        } else {
            $transaction = Transaction::where('paymob_order_id', $data['order'])
                ->first();

            $new_transaction = Transaction::create([
                'order_id' => $transaction->order_id,
                'old_order_id' => $transaction->old_order_id,
                'user_id' => $transaction->user_id,
                'payment_amount' => $transaction->payment_amount,
                'payment_method' => $transaction->payment_method,
                'payment_status' => $transaction->payment_status,
                'paymob_order_id' => $transaction->paymob_order_id,
                'payment_details' => $transaction->payment_details,
            ]);

            $transaction->update([
                'order_id' => $transaction->order_id,
                'payment_status' => 3,
                'payment_details' => $data['data_message']
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
        $order = Order::with(['user', 'products', 'address', 'transactions'])->findOrFail($order_id);

        $transaction = $order->transactions->where('payment_status', 1)->first();

        if ($transaction && ($transaction->payment_method == 2 || $transaction->payment_method == 3)) {
            $payment_key = payByPaymob($order, $transaction);

            if ($payment_key) {
                return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . ($transaction->payment_method == 3 ? env('PAYMOB_IFRAM_ID_INSTALLMENTS') : env('PAYMOB_IFRAM_ID_CARD_TEST')) . "?payment_token=$payment_key");
            } else {
                return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
            }
        } else {
            return redirect()->route('front.orders.index')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }
    ##################### Go To Paymob Iframe :: Start #####################

    ##################### Go To Paymob Iframe :: Start #####################
    public function goToRefund($order_id)
    {
        $order = Order::with(['user', 'products', 'address', 'transactions'])->findOrFail($order_id);

        $transaction = $order->transactions->whereIn('payment_method', [2, 3])->where('payment_status', 2)->first();

        DB::beginTransaction();

        try {
            $transaction->update([
                'payment_status' => 4,
            ]);

            $refund_status = refundRequestPaymob(json_decode($transaction->payment_details)->transaction_id, json_decode($transaction->payment_details)->amount_cents);

            if ($refund_status) {
                $transaction->update([
                    'payment_status' => 5,
                ]);
            } else {
                $transaction->update([
                    'payment_status' => 2,
                ]);

                $order->transactions()->create([
                    'payment_id' => $transaction->payment_id,
                    'order_id' => $transaction->order_id,
                    'user_id' => $transaction->user_id,
                    'payment_amount' => $transaction->payment_amount,
                    'payment_method' => $transaction->payment_method,
                    'payment_status' => 6,
                    'paymob_order_id' => $transaction->paymob_order_id,
                    'payment_details' => $transaction->payment_details,
                ]);

                $order->update(['status_id' => 405]);
                $order->statuses()->attach(405);
            }

            DB::commit();


            // todo
            $transaction = $order->transactions->where('payment_status', 1)->first();

            if ($transaction && ($transaction->payment_method == 2 || $transaction->payment_method == 3)) {
                $payment_key = payByPaymob($order, $transaction);

                if ($payment_key) {
                    return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . ($transaction->payment_method == 3 ? env('PAYMOB_IFRAM_ID_INSTALLMENTS') : env('PAYMOB_IFRAM_ID_CARD_TEST')) . "?payment_token=$payment_key");
                } else {
                    return redirect()->route('front.orders.index')->with('error', __('front/homePage.Refund Failed, Please Try Again'));
                }
            } else {
                return redirect()->route('front.orders.index')->with('error', __('front/homePage.Refund Failed, Please Try Again'));
            }

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return redirect()->route('front.orders.index')->with('error', __('front/homePage.Refund Failed, Please Try Again'));
        }
    }
    ##################### Go To Paymob Iframe :: Start #####################


    ##################### Update Order's Status By Bosta :: Start #####################
    public function updateStatus(Request $request)
    {
        $order = Order::findOrFail($request['businessReference']);

        $order->update([
            'status_id' => $request['state'],
            'delivered_at' => $request['state'] == 45 ? now() : null,
        ]);

        $order->statuses()->attach($request['state'], ['notes' => $request['exceptionReason'] ?? null]);

        return response()->json(['success' => true]);
    }
    ##################### Update Order's Status By Bosta :: Start #####################

    ##################### Track the Order :: Start #####################
    public function track(Request $request)
    {
        $order = Order::with('statuses')->findOrFail($request['order_id']);

        $statuses = $order->statuses()->orderBy('pivot_id', 'desc')->get();

        return view('front.orders.track', compact('order', 'statuses'));
    }
    ##################### Track the Order :: Start #####################
}

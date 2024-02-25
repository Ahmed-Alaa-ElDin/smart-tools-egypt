<?php

namespace App\Livewire\Front\Order\Payment;

use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Zone;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrderPaymentSummary extends Component
{
    public $items;

    public $items_total_quantities = 0;
    public $items_total_base_prices = 0;
    public $items_total_final_prices = 0;
    public $items_total_discounts = 0;
    public $items_discounts_percentage = 0;
    public $total_after_offer_prices = 0;
    public $offers_total_discounts = 0;
    public $offers_discounts_percentage = 0;
    public $offers_free_shipping = 0;
    public $order_discount = 0;
    public $order_discount_percent = 0;
    public $order_offer_free_shipping = 0;
    public $total_after_order_discount = 0;
    public $total_after_coupon_discount = 0;
    public $total_order_free_shipping = 0;
    public $shipping_fees = 0;
    public $items_total_weights = 0;
    public $items_total_shipping_weights = 0;
    public $best_zone_id = null;
    public $city_name = null;
    public $address = null;

    public $items_total_points = 0;
    public $offers_total_points = 0;
    public $after_offers_total_points = 0;
    public $order_points = 0;
    public $total_points_after_order_points = 0;
    public $coupon_items_points = 0;
    public $coupon_order_points = 0;
    public $coupon_total_points = 0;
    public $total_points_after_coupon_points = 0;

    public $coupon_id = null;
    public $products_best_coupon = [];
    public $collections_best_coupon = [];
    public $coupon_items_discount = 0;
    public $coupon_order_discount = 0;
    public $coupon_total_discount = 0;
    public $coupon_total_discount_percent = 0;
    public $coupon_free_shipping = false;
    public $order_best_coupon = [
        'type' => null,
        'value' => 0,
    ];

    public $payment_method = null, $balance = 0, $points = 0, $points_egp = 0;

    protected $listeners = [
        'couponApplied',
        'updatePaymentMethod',
        'submit',
    ];

    ############# Render :: Start #############
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
                $item['total_shipping_weight'] = !$item['free_shipping'] ? $item['weight'] * $item['qty'] : 0;
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

                if (is_null($this->coupon_id)) {
                    $item['coupon_discount'] =  0;
                    $item['coupon_points'] =  0;
                }

                return $item;
            }, $this->items);

            $this->items_total_weights = array_sum(array_column($this->items, 'total_weight'));
            $this->items_total_shipping_weights = array_sum(array_column($this->items, 'total_shipping_weight'));

            // Order Offer
            $order_offer = Offer::orderOffers()->first();

            // ------------------------------------------------------------------------------------------------------
            // A - Shipping
            // ------------------------------------------------------------------------------------------------------
            // 1 - Items Offers Free Shipping
            $this->offers_free_shipping = !in_array(0, array_column($this->items, 'offer_free_shipping'));

            // 2 - Order Offer Free Shipping
            if ($order_offer) {
                // Order Free Shipping
                $this->order_offer_free_shipping = $order_offer->free_shipping;
            }

            // 3 - Total Order Free Shipping (After Items & Order Offers)
            $this->total_order_free_shipping = $this->offers_free_shipping || $this->order_offer_free_shipping || $this->coupon_free_shipping;

            $this->getShippingFees();

            // ------------------------------------------------------------------------------------------------------
            // B - Prices
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
                    $this->order_discount_percent = round($order_offer->value, 2);
                }
                // Fixed Discount
                elseif ($order_offer->type == 1) {
                    $this->order_discount = $this->total_after_offer_prices - $order_offer->value > 0 ? $order_offer->value : $this->total_after_offer_prices;
                    $this->order_discount_percent = $this->total_after_offer_prices ? round(($this->order_discount * 100) / $this->total_after_offer_prices, 2) : 0;
                }
                // Points
                elseif ($order_offer->type == 2) {
                    $this->order_points = $order_offer->value;
                }
            }

            // 5 - Prices After Order Offer
            $this->total_after_order_discount = $this->total_after_offer_prices - $this->order_discount;

            // 6 - Total After Coupon Discounts
            $this->total_after_coupon_discount = ($this->total_after_order_discount + $this->shipping_fees) > $this->coupon_total_discount ? $this->total_after_order_discount - $this->coupon_total_discount + $this->shipping_fees : 0.00;

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
            // 5 - Points After Coupon Points
            $this->total_points_after_coupon_points = $this->total_points_after_order_points + $this->coupon_total_points;
        } else {
            $this->items_total_base_prices = 0;
            $this->items_total_final_prices = 0;
            $this->items_total_discounts = 0;
            $this->items_discounts_percentage = 0;
            $this->total_after_offer_prices = 0;
            $this->offers_total_discounts = 0;
            $this->offers_discounts_percentage = 0;
            $this->offers_free_shipping = 0;
            $this->order_discount = 0;
            $this->order_discount_percent = 0;
            $this->order_points = 0;
            $this->order_offer_free_shipping = 0;
            $this->total_after_order_discount = 0;
            $this->total_order_free_shipping = 0;
            $this->shipping_fees = 0;
            $this->items_total_shipping_weights = 0;
            $this->best_zone_id = null;
            $this->city_name = null;
            $this->address = null;
        }

        return view('livewire.front.order.payment.order-payment-summary');
    }
    ############# Render :: End #############

    ############# Get Shipping Fees :: Start #############
    public function getShippingFees()
    {
        if (auth()->check() && !$this->total_order_free_shipping) {
            $this->address = auth()->user()->addresses->where('default', 1)->first();

            $items_total_shipping_weights = $this->items_total_shipping_weights;

            if ($this->address) {
                // Get City Id
                $city_id = $this->address->city_id;

                $this->city_name = $this->address->city->name;

                // Get Destinations and Zones for the city
                $zones = Zone::with(['destinations'])
                    ->where('is_active', 1)
                    ->whereHas('destinations', fn ($q) => $q->where('city_id', $city_id))
                    ->whereHas('delivery', fn ($q) => $q->where('is_active', 1))
                    ->get();

                // Get the best Delivery Cost
                $prices = $zones->map(function ($zone) use ($items_total_shipping_weights) {
                    $min_charge = $zone->min_charge;
                    $min_weight = $zone->min_weight;
                    $kg_charge = $zone->kg_charge;

                    if ($items_total_shipping_weights < $min_weight) {
                        return [
                            'zone_id' => $zone->id,
                            'charge' => $min_charge
                        ];
                    } else {
                        return [
                            'zone_id' => $zone->id,
                            'charge' => $min_charge + ($items_total_shipping_weights - $min_weight) * $kg_charge
                        ];
                    }
                });

                $this->shipping_fees = $prices->min('charge');

                $best_zone = $prices->filter(function ($price) {
                    return $price['charge'] == $this->shipping_fees;
                });

                if ($best_zone->count()) {
                    $this->best_zone_id = $best_zone->first()['zone_id'];
                } else {
                    $this->best_zone_id = null;
                }
            }
        } else {
            $this->shipping_fees = 0.00;
        }
    }
    ############# Get Shipping Fees :: End #############

    ############## Get Coupon Data :: Start ##############
    public function couponApplied($coupon_id, $products_best_coupon, $collections_best_coupon, $coupon_items_discount, $coupon_items_points, $coupon_free_shipping, $order_best_coupon)
    {
        $this->coupon_id = $coupon_id;
        $this->coupon_items_discount = $coupon_items_discount;
        $this->coupon_items_points = $coupon_items_points;
        $this->coupon_free_shipping = $coupon_free_shipping;

        $this->items = array_map(function ($item) use ($products_best_coupon, $collections_best_coupon) {
            if ($item['type'] == 'Product') {
                if (in_array($item['id'], array_keys($products_best_coupon))) {
                    $item['coupon_discount'] = $products_best_coupon[$item['id']]['coupon_discount'];
                    $item['coupon_points'] = $products_best_coupon[$item['id']]['coupon_points'];
                } else {
                    $item['coupon_discount'] = 0;
                    $item['coupon_points'] = 0;
                }
            } elseif ($item['type'] == 'Collection') {
                if (in_array($item['id'], array_keys($collections_best_coupon))) {
                    $item['coupon_discount'] = $collections_best_coupon[$item['id']]['coupon_discount'];
                    $item['coupon_points'] = $collections_best_coupon[$item['id']]['coupon_points'];
                } else {
                    $item['coupon_discount'] = 0;
                    $item['coupon_points'] = 0;
                }
            }
            return $item;
        }, $this->items);

        if ($order_best_coupon['value'] > 0) {
            if ($order_best_coupon['type'] == 0 && $order_best_coupon['value'] <= 100) {
                $this->coupon_order_discount = $this->total_after_order_discount * $order_best_coupon['value'] / 100;
                $this->coupon_order_points = 0;
            } elseif ($order_best_coupon['type'] == 1) {
                $this->coupon_order_discount = $order_best_coupon['value'] <= $this->total_after_order_discount ? $order_best_coupon['value'] : $this->total_after_order_discount;
                $this->coupon_order_points = 0;
            } elseif ($order_best_coupon['type'] == 2) {
                $this->coupon_order_discount = 0;
                $this->coupon_order_points = $order_best_coupon['value'];
            }
        } else {
            $this->coupon_order_discount = 0;
            $this->coupon_order_points = 0;
        }

        $this->coupon_total_discount = $this->coupon_items_discount + $this->coupon_order_discount;
        $this->coupon_total_discount_percent = $this->total_after_order_discount ? round(($this->coupon_total_discount * 100) / $this->total_after_order_discount, 2) : 0;

        $this->coupon_total_points = $this->coupon_items_points + $this->coupon_order_points;
    }
    ############## Get Coupon Data :: End ##############

    public function updatePaymentMethod($payment_method = null)
    {
        $this->payment_method = $payment_method;
    }

    public function submit($payment_method, $balance, $points, $points_egp)
    {
        $this->payment_method = $payment_method;
        $this->balance = $balance;
        $this->points = $points;
        $this->points_egp = $points_egp;

        DB::beginTransaction();

        try {
            $this->balance = $this->total_after_coupon_discount > $this->balance ? $this->balance : $this->total_after_coupon_discount;

            $this->points_egp = ($this->total_after_coupon_discount - $this->balance) > $this->points_egp ? $this->points_egp : (($this->total_after_coupon_discount) - $this->balance);
            $this->points = floor($this->points_egp / config('settings.points_conversion_rate'));

            // Get the order from database
            $order = Order::with([
                'status',
                'payment',
                'transactions',
                'address'
            ])->whereIn('status_id', [201, 202])
                ->where('user_id', auth()->user()->id)
                ->firstOrFail();

            // Update the Order
            $order->update([
                'status_id'             =>      202,
                'num_of_items'          =>      $this->items_total_quantities,
                'allow_opening'         =>      1,
                'zone_id'               =>      $this->best_zone_id,
                'coupon_id'             =>      $this->coupon_id,
                'items_points'          =>      $this->items_total_points,
                'offers_items_points'   =>      $this->offers_total_points,
                'offers_order_points'   =>      $this->order_points,
                'coupon_items_points'   =>      $this->coupon_items_points,
                'coupon_order_points'   =>      $this->coupon_order_points,
                'gift_points'           =>      $this->total_points_after_coupon_points,
                'total_weight'          =>      $this->items_total_weights,
            ]);

            // Change the state of the order
            if ($order->statuses()->count() == 0 || $order->statuses()->orderBy('pivot_created_at', 'desc')->first()->id != 202) {
                $order->statuses()->attach(202);
            }

            // Add the payment to the order
            $payment = $order->payment()->updateOrCreate([
                'order_id' => $order->id
            ], [
                'subtotal_base' => $this->items_total_base_prices,
                'items_discount' => $this->items_total_discounts,
                'offers_items_discount' => $this->offers_total_discounts,
                'offers_order_discount' => $this->order_discount,
                'coupon_items_discount' => $this->coupon_items_discount,
                'coupon_order_discount' => $this->coupon_order_discount,
                'delivery_fees' => $this->shipping_fees,
                'total' => $this->total_after_coupon_discount,
            ]);

            // Update user balance if used
            if ($this->balance > 0) {
                $payment->transactions()->updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method' => 10,
                    'payment_status' => 2,
                ], [
                    'payment_amount' => $this->balance,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($this->balance * 100, 0, '', ''),
                        "points" => 0,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);

                $order->user->update([
                    'balance' => $order->user->balance - $this->balance > 0 ? $order->user->balance - $this->balance : 0
                ]);
            }

            // Update user points if used
            if ($this->points_egp > 0) {
                $used_points = $this->points;

                $payment->transactions()->updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_method' => 11,
                    'payment_status' => 2,
                ], [
                    'payment_amount' => $this->points_egp,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($this->points_egp * 100, 0, '', ''),
                        "points" => $used_points,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);

                while ($used_points > 0) {
                    $oldest_points = $order
                        ->user
                        ->points
                        ->where('status', 1)
                        ->where('created_at', '>=', Carbon::now()->subDays(90)->toDateTimeString())
                        ->sortBy('created_at')
                        ->first();

                    if ($oldest_points->value <= $used_points) {
                        $used_points -= $oldest_points->value;
                        $oldest_points->delete();
                    } else {
                        $oldest_points->update([
                            'value' => $oldest_points->value - $used_points
                        ]);
                        $used_points = 0;
                    }

                    $order->user->load('points');
                }
            }

            // Add Points to the user if present
            if ($this->total_points_after_coupon_points) {
                $order->user->points()->create([
                    'order_id' => $order->id,
                    'value' => $this->total_points_after_coupon_points,
                    'status' => 0
                ]);
            }

            // Create a transaction according to the order payment method
            $should_pay = $this->total_after_coupon_discount - $this->balance - $this->points_egp;

            if ($should_pay > 0) {
                $transaction = $payment->transactions()->updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_status' => 1,
                ], [
                    'payment_amount' => $should_pay,
                    'payment_method' => $this->payment_method,
                    'payment_details' => json_encode([
                        "amount_cents" => number_format($should_pay * 100, 0, '', ''),
                        "points" => 0,
                        "transaction_id" => null,
                        "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                    ]),
                ]);
            } else {
                $payment->transactions()->where([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_status' => 1
                ])->delete();
            }

            // Add Products and Collections to the order
            // get order's products
            $products = array_filter($this->items, fn ($item) => $item['type'] == 'Product');

            $final_products = [];

            foreach ($products as $product) {
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
            $collections = array_filter($this->items, fn ($item) => $item['type'] == 'Collection');

            $final_collections = [];

            foreach ($collections as $collection) {
                $final_collections[$collection['id']] = [
                    'quantity' => $collection['qty'],
                    'original_price' => $collection['original_price'],
                    'price' => $collection['best_price'] - $collection['coupon_discount'],
                    'points' => $collection['best_points'],
                    'coupon_discount' => $collection['coupon_discount'],
                    'coupon_points' => $collection['coupon_points'],
                ];
            };

            ################### Modify Products and Collections :: Start ###################
            // Add Previous Order Amounts of Collections and Products
            // Get Products from database
            $order->products()->each(function ($product) {
                $product->quantity = $product->quantity + $product->pivot->quantity;
                $product->save();
            });

            // Get Collections from database
            $order->collections()->each(function ($collection) {
                $collection->products()->each(function ($product) use ($collection) {
                    $product->quantity = $product->quantity + ($collection->pivot->quantity * $product->pivot->quantity);
                    $product->save();
                });
            });


            // update order's products
            if (count($final_products)) {
                $order->products()->sync(
                    $final_products
                );
            } else {
                $order->products()->detach();
            }

            // update order's collections
            if (count($final_collections)) {
                $order->collections()->sync(
                    $final_collections
                );
            } else {
                $order->collections()->detach();
            }


            // Remove Order Amounts from Collections and Products
            $order->products()->each(function ($product) use (&$final_products) {
                $product->quantity = $product->quantity - $final_products[$product->id]['quantity'];
                $product->save();
            });

            $order->collections()->each(function ($collection) use (&$final_collections) {
                $products = $collection->products();

                $products->each(function ($product) use (&$collection, &$final_collections) {
                    $product->quantity = $product->quantity - ($final_collections[$collection->id]['quantity'] * $product->pivot->quantity);
                    $product->save();
                });
            });

            ################### Modify Products and Collections :: End ###################

            // Update Coupon Count
            if ($order->coupon_id != null) {
                $coupon = Coupon::find($order->coupon_id);

                $coupon->update([
                    'number' => $coupon->number != null && $coupon->number > 0 ? $coupon->number - 1 : $coupon->number,
                ]);
            }

            if ($should_pay <= 0 || $this->payment_method == 1) {

                $order->statuses()->attach(203);

                $bosta_order = createBostaOrder($order, $this->payment_method);

                if ($bosta_order['status']) {

                    if ($should_pay <= 0) {
                        $order->points()->update([
                            'status' => 1
                        ]);
                    }

                    // Clear Cart
                    Cart::instance('cart')->destroy();
                    Cart::instance('cart')->store($order->user->id);

                    DB::commit();

                    // redirect to done page
                    Session::flash('success', __('front/homePage.Order Created Successfully'));
                    redirect()->route('front.orders.done')->with('order_id', $order->id);
                } else {
                    $this->dispatch(
                        'swalDone',
                        text: __("front/homePage.Order hasn't been created"),
                        icon: 'error'
                    );
                }
            } elseif ($this->payment_method == 2) {
                $payment_key = payByPaymob($order, $transaction);

                if ($payment_key) {
                    $order->update([
                        'status_id' => 203,
                    ]);

                    $order->statuses()->attach(203);

                    // Clear Cart
                    Cart::instance('cart')->destroy();
                    Cart::instance('cart')->store($order->user->id);

                    DB::commit();

                    return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAM_ID_CARD') . "?payment_token=$payment_key");
                } else {
                    return redirect()->route('front.orders.payment')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
                }
            } elseif ($this->payment_method == 3) {
                $payment_key = payByPaymob($order, $transaction);

                if ($payment_key) {
                    $order->update([
                        'status_id' => 203,
                    ]);

                    $order->statuses()->attach(203);

                    // Clear Cart
                    Cart::instance('cart')->destroy();
                    Cart::instance('cart')->store($order->user->id);

                    DB::commit();
                    return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAM_ID_INSTALLMENTS')  . "?payment_token=$payment_key");
                } else {
                    return redirect()->route('front.orders.payment')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
                }
            } elseif ($this->payment_method == 4) {
                $order->update([
                    'status_id' => 203,
                ]);

                $order->statuses()->attach(203);

                // Clear Cart
                Cart::instance('cart')->destroy();
                Cart::instance('cart')->store($order->user->id);

                DB::commit();
                // redirect to done page
                Session::flash('success', __('front/homePage.Order Created Successfully'));
                redirect()->route('front.orders.done')->with('order_id', $order->id);
            }
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }
}

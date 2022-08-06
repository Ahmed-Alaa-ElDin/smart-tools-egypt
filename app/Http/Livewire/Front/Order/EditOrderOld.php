<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Coupon;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Subtotal;

class EditOrder extends Component
{
    public $order,
        $products = [],
        $subtotal = 0.00,
        $delivery_fees = 0.00,
        $total = 0.00,
        $used_balance = 0.00,
        $used_points_egp = 0.00,
        $points = 0,
        $quantity,
        $zone,
        $coupon,
        $products_weights;


    public function mount()
    {
        $this->products = $this->order->products->toArray();
        $this->products_weights = array_sum(array_map(function ($product) {
            if (!$product['free_shipping']) {
                return $product['weight'] * $product['pivot']['quantity'];
            }
        }, $this->products));
        $this->quantity = $this->order->num_of_items;
        $this->points = $this->order->gift_points;
        $this->subtotal = $this->order->subtotal_final;
        $this->delivery_fees = $this->order->delivery_fees;
        $this->total = $this->subtotal + $this->delivery_fees;
        $this->used_balance = $this->order->used_balance;
        $this->used_points_egp = $this->order->used_points * config('constants.constants.POINT_RATE');
        $this->zone = Zone::findOrFail($this->order->zone_id);
        $this->coupon = $this->order->coupon_id ? Coupon::findOrFail($this->order->coupon_id) : null;
    }

    public function render()
    {
        return view('livewire.front.orders.edit-order');
    }

    public function updated($field)
    {
        $this->calcQuantity();
        $this->calcSubtotal();
        $this->calcDelivery();
    }

    public function calcSubtotal()
    {
        $this->subtotal = array_sum(array_map(function ($product) {
            return $product['pivot']['price'] * $product['pivot']['quantity'];
        }, $this->products)) - $this->used_balance - $this->used_points_egp;
    }

    public function calcDelivery()
    {
        if ($this->coupon && $this->coupon->free_shipping) {
            $this->delivery_fees = 0;
        } else {
            // get products weights
            $this->products_weights = array_sum(array_map(function ($product) {
                if (!$product['free_shipping']) {
                    return $product['weight'] * $product['pivot']['quantity'];
                }
            }, $this->products));

            // get delivery fees
            $this->delivery_fees = $this->quantity > 0 ? ($this->products_weights < $this->zone->min_weight ?
                $this->zone->min_charge :
                $this->zone->min_charge + ($this->products_weights - $this->zone->min_weight) * $this->zone->kg_charge) : 0.00;
        }
    }

    public function calcQuantity()
    {
        $this->quantity = array_sum(array_map(function ($product) {
            return $product['pivot']['quantity'];
        }, $this->products));
    }

    public function calcPoints()
    {
        $this->points = array_sum(array_map(function ($product) {
            return $product['pivot']['quantity'] * $product['best_offer']['best_points'];
        }, $this->products));
    }

    public function removeProduct($key)
    {
        $this->products[$key]['pivot']['quantity'] = 0;
        $this->calcQuantity();
        $this->calcSubtotal();
        $this->calcDelivery();
    }

    public function saveEdits()
    {
        if ($this->quantity > 0) {

            $this->calcPoints();

            if ($this->coupon) {
                $coupon_data = getCoupon($this->coupon, $this->products, $this->subtotal, $this->points, $this->delivery_fees);
            }

            DB::beginTransaction();

            try {
                $order = $this->order;

                $subtotal_base = array_sum(array_map(function ($product) {
                    return $product['base_price'] * $product['pivot']['quantity'];
                }, $this->products));

                $used_balance = $this->subtotal >= 0 ? $this->used_balance : (($this->used_balance - abs($this->subtotal) > 0) ? $this->used_balance - abs($this->subtotal) : 0);
                $refund_balance = $this->used_balance - $used_balance;
                // $used_points = ($this->subtotal + $used_balance < 0) ? $this->used_points_egp : (($this->used_points_egp - abs($this->subtotal) * config('constants.constants.POINT_RATE) > 0) ? $this->used_points_egp - abs($this->subtotal) * config('constants.constants.POINT_RATE) : 0);
                dd($this->subtotal - $used_balance);

                if ($order->payment_method == 1) {
                    // update the database
                    $order->update([
                        'num_of_items' => $this->quantity,
                        'coupon_discount' => $coupon_data['coupon_discount'] ?? 0.00,
                        'subtotal_base' => $subtotal_base ?? 0.00,
                        'subtotal_final' => $this->subtotal ?? 0.00,
                        'delivery_fees' => $this->coupon ? $coupon_data['coupon_shipping'] : ($this->delivery_fees ?? 0.00),
                        'gift_points' => $this->coupon ? $coupon_data['coupon_shipping'] : ($this->points ?? 0),
                        'total_weight' => $this->products_weights ?? 0.00,
                        'used_balance' => 0,
                    ]);

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
        } else {
            DB::beginTransaction();

            try {
                $order = $this->order;

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
}

<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrderBillingDetails extends Component
{
    public $points, $points_egp;
    public $balance;
    public $payment_method;
    public $iframe;
    public $ready;

    protected $listeners = [
        'setOrderFinalPrice',
    ];

    public function rules()
    {
        return [
            'points' => 'numeric|min:0|integer|max:' . auth()->user()->points,
            'balance' => 'numeric|min:0|max:' . auth()->user()->balance,
        ];
    }

    public function mount()
    {
        $this->ready = false;
        $this->points = 0;
        $this->points_egp = 0;
        $this->balance = 0;
    }

    public function render()
    {
        return view('livewire.front.order.order-billing-details');
    }

    public function updatedPoints($points)
    {
        $this->validateOnly('points');

        $this->points_egp =  $points * config('constants.constants.POINT_RATE');
    }

    public function updatedBalance($balance)
    {
        $this->validateOnly('balance');
    }

    public function payBy($payment_method)
    {
        $this->payment_method = $payment_method;
        $this->ready = true;
    }

    public function confirm($payment_method)
    {
        $this->payment_method = $payment_method;

        $this->emit('getOrderFinalPrice');
    }

    public function setOrderFinalPrice($array_data)
    {
        DB::beginTransaction();

        try {
            $this->balance = ($array_data['subtotal_final'] + $array_data['delivery_fees']) > $this->balance ? $this->balance : ($array_data['subtotal_final'] + $array_data['delivery_fees']);

            $this->points_egp = (($array_data['subtotal_final'] + $array_data['delivery_fees']) - $this->balance) > $this->points_egp ? $this->points_egp : (($array_data['subtotal_final'] + $array_data['delivery_fees']) - $this->balance);
            $this->points = floor($this->points_egp / config('constants.constants.POINT_RATE'));

            // get order's products
            $products = Cart::instance('cart')->content()->keyBy("id")->map(function ($item) use ($array_data) {
                $product = collect($array_data['products'])->where('id', $item->id)->first();

                return [
                    'quantity' => $item->qty,
                    'price' => $product['best_price'],
                    'points' => $product['best_points'],
                    'coupon_discount' => isset($array_data['products_best_coupon'][$item->id]) ? $array_data['products_best_coupon'][$item->id]['coupon_discount'] : 0.00,
                    'coupon_points' =>  isset($array_data['products_best_coupon'][$item->id]) ? $array_data['products_best_coupon'][$item->id]['coupon_points'] : 0,
                ];
            })->toArray();

            // update order in database
            $order = Order::with([
                'status',
                'payments',
                'address'
            ])->whereIn('status_id', [1, 2])->where('user_id', auth()->user()->id)->first();

            if ($order) {
                $order->update([
                    'num_of_items'              =>      Cart::instance('cart')->count(),
                    'zone_id'                   =>      $array_data['zone_id'],
                    'coupon_id'                 =>      $array_data['coupon_id'],
                    'coupon_discount'           =>      $array_data['coupon_discount'] ?? 0.00,
                    'coupon_points'             =>      $array_data['coupon_points'] ?? 0.00,
                    'coupon_order_discount'     =>      $array_data['order_best_coupon']['discount'],
                    'coupon_order_points'       =>      $array_data['order_best_coupon']['points'],
                    'coupon_products_discount'  =>      $array_data['coupon_discount'] - $array_data['order_best_coupon']['discount'],
                    'coupon_products_points'    =>      $array_data['coupon_points'] - $array_data['order_best_coupon']['points'],
                    'subtotal_base'             =>      $array_data['subtotal_base'],
                    'subtotal_final'            =>      $array_data['subtotal_final'] - $this->points_egp - $this->balance,
                    'delivery_fees'             =>      $array_data['delivery_fees'],
                    'total'                     =>      $array_data['total'] - $this->points_egp - $this->balance,
                    'should_pay'                =>      $array_data['total'] - $this->points_egp - $this->balance,
                    'should_get'                =>      0.00,
                    'used_points'               =>      $this->points ?? 0,
                    'gift_points'               =>      $array_data['gift_points'] ?? 0,
                    'used_balance'              =>      $this->balance ?? 0,
                    'total_weight'              =>      $array_data['weight'],
                    'payment_method'            =>      $this->payment_method,
                    'status_id'                 =>      2,
                ]);

                if ($order->statuses()->latest()->first()->id != 2) {
                    $order->statuses()->attach(2);
                }

                $payment = Payment::updateOrCreate([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_status' => 1,
                ], [
                    'payment_amount' => $order->should_pay,
                    'payment_method' => $order->payment_method,
                ]);

                // update order's products
                $order->products()->sync(
                    $products
                );

                DB::commit();
            } else {
                redirect()->route('front.order.shipping');
            }

            if ($order->payment_method == 1) {

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


                    // redirect to done page
                    Session::flash('success', __('front/homePage.Order Created Successfully'));
                    redirect()->route('front.orders.done')->with('order_id', $order->id);
                } else {
                    Session::flash('error', __('front/homePage.Order Creation Failed, Please Try Again'));
                    redirect()->route('front.orders.billing');
                }
            } elseif ($order->payment_method == 2) {
                $payment_key = payByPaymob($payment);

                if ($payment_key) {
                    return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAM_ID_CARD_TEST') . "?payment_token=$payment_key");
                } else {
                    return redirect()->route('front.orders.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
                }
            } elseif ($order->payment_method == 3) {
                $payment_key = payByPaymob($payment);

                if ($payment_key) {
                    return redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAM_ID_INSTALLMENTS')  . "?payment_token=$payment_key");
                } else {
                    return redirect()->route('front.orders.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
                }
            } elseif ($order->payment_method == 4) {
                $user = $order->user;

                $user->update([
                    'points' => $user->points - $order->used_points + $order->gift_points ?? 0,
                    'balance' => $user->balance - $order->used_balance ?? 0,
                ]);

                // clear cart
                Cart::instance('cart')->destroy();
                Cart::instance('cart')->store($user->id);

                // edit products database
                foreach ($order->products as $product) {
                    $product->update([
                        'quantity' => $product->quantity - $product->pivot->quantity >= 0  ? $product->quantity - $product->pivot->quantity : 0,
                    ]);
                }

                // redirect to done page
                Session::flash('success', __('front/homePage.Order Created Successfully'));
                redirect()->route('front.orders.done')->with('order_id', $order->id);
            }
        } catch (\Throwable $th) {
            DB::rollback();

            dd($th);
        }
    }
}

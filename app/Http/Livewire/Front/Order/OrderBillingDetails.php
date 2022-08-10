<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Order;
use App\Models\Payment;
use Gloudemans\Shoppingcart\Facades\Cart;
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
        $this->balance = ($array_data['subtotal_final'] + $array_data['delivery_fees']) > $this->balance ? $this->balance : ($array_data['subtotal_final'] + $array_data['delivery_fees']);

        $this->points_egp = (($array_data['subtotal_final'] + $array_data['delivery_fees']) - $this->balance) > $this->points_egp ? $this->points_egp : (($array_data['subtotal_final'] + $array_data['delivery_fees']) - $this->balance);
        $this->points = floor($this->points_egp / config('constants.constants.POINT_RATE'));

        // get order's products
        $products = Cart::instance('cart')->content()->keyBy("id")->map(function ($item) use ($array_data) {
            return [
                'quantity' => $item->qty,
                'price' => collect($array_data['products'])->where('id', $item->id)->first()['best_price'],
            ];
        })->toArray();

        // update order in database
        $order = Order::with([
            'status',
            'payments'
        ])->updateOrCreate(
            [
                'user_id'       =>  auth()->user()->id,
                'status_id'     =>  1
            ],
            [
                'num_of_items'      =>      Cart::instance('cart')->count(),
                'zone_id'           =>      $array_data['zone_id'],
                'coupon_id'         =>      $array_data['coupon_id'],
                'coupon_discount'   =>      $array_data['coupon_discount'] ?? 0.00,
                'subtotal_base'     =>      $array_data['subtotal_base'],
                'subtotal_final'    =>      $array_data['subtotal_final'] - $this->points_egp - $this->balance,
                'delivery_fees'     =>      $array_data['delivery_fees'],
                'total'             =>      $array_data['subtotal_final'] - $this->points_egp - $this->balance + $array_data['delivery_fees'],
                'should_pay'        =>      $array_data['subtotal_final'] - $this->points_egp - $this->balance + $array_data['delivery_fees'],
                'should_get'        =>      0.00,
                'used_points'       =>      $this->points ?? 0,
                'gift_points'       =>      $array_data['gift_points'] ?? 0,
                'used_balance'      =>      $this->balance ?? 0,
                'total_weight'      =>      $array_data['weight'],
                'payment_method'    =>      $this->payment_method,
                'status_id'         =>      $this->payment_method == 4 ? 2 : 1,
            ]
        );

        $payment = Payment::updateOrCreate([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'payment_status' => 1,
        ],[
            'payment_amount' => $order->should_pay,
            'payment_method' => $order->payment_method,
        ]);

        // update order's products
        $order->products()->sync(
            $products
        );

        if ($order->payment_method == 1) {
            createBostaOrder($order);
        } elseif ($order->payment_method == 2) {
            $this->payByPaymob($order, $payment);
        } elseif ($order->payment_method == 3) {
            $this->payByPaymob($order, $payment);
        } elseif ($order->payment_method == 4) {
            // empty cart
            Cart::instance('cart')->destroy();

            // redirect to done page
            Session::flash('success', __('front/homePage.Order Created Successfully'));
            redirect()->route('front.order.done')->with('order_id', $order->id);
        }
    }

    public function payByPaymob($order,$payment)
    {
        try {
            // create paymob auth token
            $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
                "api_key" => env('PAYMOB_TOKEN')
                ])->json();

                $auth_token = $first_step['token'];

            // create paymob order
            $second_step = Http::acceptJson()->post('https://accept.paymob.com/api/ecommerce/orders', [
                "auth_token" =>  $auth_token,
                "delivery_needed" => "false",
                "amount_cents" => number_format(($order->should_pay) * 100, 0, '', ''),
                "currency" => "EGP",
                "items" => []
            ])->json();

            $order_id = $second_step['id'];

            $payment->update([
                'paymob_order_id' => $order_id,
            ]);

            // create paymob transaction
            $third_step = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
                "auth_token" => $auth_token,
                "amount_cents" => number_format(($order->should_pay) * 100, 0, '', ''),
                "expiration" => 3600,
                "order_id" => $order_id,
                "billing_data" => [
                    "apartment" => "NA",
                    "email" => $order->user->email ?? 'test@smarttoolsegypt.com',
                    "floor" => "NA",
                    "first_name" => $order->user->f_name,
                    "street" => "NA",
                    "building" => "NA",
                    "phone_number" => $order->phone1,
                    "shipping_method" => "NA",
                    "postal_code" => "NA",
                    "city" => "NA",
                    "country" => "NA",
                    "last_name" => $order->user->l_name ?? $order->user->f_name,
                    "state" => "NA"
                ],
                "currency" => "EGP",
                "integration_id" => $order->payment_method == 3 ? env('PAYMOB_CLIENT_ID_INSTALLMENTS') : env('PAYMOB_CLIENT_ID_CARD_TEST'),
            ])->json();

            $payment_key = $third_step['token'];

            // redirect to paymob payment page
            redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . ($order->payment_method == 3 ? env('PAYMOB_IFRAM_ID_INSTALLMENTS') : env('PAYMOB_IFRAM_ID_CARD_TEST')) . "?payment_token=$payment_key");
        } catch (\Throwable $th) {
            redirect()->route('front.order.billing')->with('error', __('front/homePage.Payment Failed, Please Try Again'));
        }
    }
}

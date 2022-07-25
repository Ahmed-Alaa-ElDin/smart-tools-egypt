<?php

namespace App\Http\Livewire\Front\Order;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
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
            'balance' => 'numeric|min:0|max:' . auth()->user()->points,
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
        $this->points_egp = $array_data['subtotal_final'] > $this->points_egp ? $this->points_egp : $array_data['subtotal_final'];

        $this->points = $this->points_egp / config('constants.constants.POINT_RATE');

        $this->balance = $array_data['subtotal_final'] > $this->balance ? $this->balance : $array_data['subtotal_final'];

        // get order's products
        $products = Cart::instance('cart')->content()->map(function ($item) {
            return [
                'product_id'    => $item->id,
                'quantity'      => $item->qty,
            ];
        })->toArray();

        // update order in database
        $order = Order::with([
            'address' => fn ($q) => $q->with('governorate', 'city'),
            'user',
            'products' => fn ($q) => $q->select('products.id', 'products.quantity', 'product_id'),
        ])->updateOrCreate(
            [
                'user_id'       =>  auth()->user()->id,
                'status_id'     =>  1
            ],
            [
                'num_of_items'      =>      Cart::instance('cart')->count(),
                'zone_id'           =>      $array_data['zone_id'],
                'coupon_id'         =>      $array_data['coupon_id'],
                'subtotal_base'     =>      $array_data['subtotal_base'],
                'subtotal_final'    =>      $array_data['subtotal_final'] - $this->points_egp - $this->balance,
                'delivery_fees'     =>      $array_data['delivery_fees'],
                'used_points'       =>      $this->points ?? 0,
                'gift_points'       =>      $array_data['gift_points'] ?? 0,
                'used_balance'      =>      $this->balance ?? 0,
                'total_weight'      =>      $array_data['weight'],
                'payment_method'    =>      $this->payment_method,
                'payment_status'    =>      0,
                'status_id'         =>      $this->payment_method == 4 ? 2 : 1,
            ]
        );

        // update order's products
        $order->products()->sync(
            $products
        );

        if ($this->payment_method == 1) {
            $this->createOrder($order, $array_data);
        } elseif ($this->payment_method == 2) {
            $this->payByPaymob($order);
        } elseif ($this->payment_method == 3) {
            $this->payByPaymob($order);
        } elseif ($this->payment_method == 4) {
            // redirect to done page
            Session::flash('success', __('front/homePage.Order Created Successfully'));
            redirect()->route('front.order.done')->with('order_id', $order->id);
        }
    }

    public function createOrder($order, $array_data)
    {
        $order_data = [
            "specs" => [
                "packageDetails"    =>      [
                    "itemsCount" => $order->num_of_items,
                    "description" => $order->package_desc,
                ],
                "size"              =>      "SMALL",
                "weight"            =>      $order->total_weight,
            ],
            "dropOffAddress" => [
                "city"          =>      $order->address->governorate->getTranslation('name', 'en'),
                "district"      =>      $order->address->city->getTranslation('name', 'en'),
                "firstLine"     =>      $order->address->details ?? $order->address->city->getTranslation('name', 'en'),
                "secondLine"    =>      $order->address->landmarks ?? '',
            ],
            "receiver" => [
                "phone"         =>      $order->user->phones->where('default', 1)->first()->phone,
                "firstName"     =>      $order->user->f_name,
                "lastName"      =>      $order->user->l_name ?? '',
                "email"         =>      $order->user->email ?? '',
            ],
            "businessReference" => "$order->id",
            "type"      =>      10,
            "notes"     =>      $order->notes ?? '',
            "cod"       =>      $order->payment_method == 1 ? $order->subtotal_final + $order->delivery_fees : 0.00,
            "allowToOpenPackage" => true,
        ];

        // create bosta order
        $bosta_response = Http::withHeaders([
            'Authorization'     =>  env('BOSTA_API_KEY'),
            'Content-Type'      =>  'application/json',
            'Accept'            =>  'application/json'
        ])->post('https://app.bosta.co/api/v0/deliveries', $order_data);

        $decoded_bosta_response = $bosta_response->json();

        if ($bosta_response->successful()) {
            // update order in database
            $order->update([
                'tracking_number' => $decoded_bosta_response['trackingNumber'],
                'order_delivery_id' => $decoded_bosta_response['_id'],
                'status_id' => 3,
            ]);

            // update user's balance
            $user = User::find(auth()->user()->id);

            $user->update([
                'points' => $user->points - $this->points + $order->gift_points ?? 0,
                'balance' => $user->balance - $this->balance ?? 0,
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

            // edit products database
            foreach ($order->products as $product) {
                $product->update([
                    'quantity' => $product->quantity - $product->pivot->quantity >= 0  ? $product->quantity - $product->pivot->quantity : 0,
                ]);
            }

            // redirect to done page
            Session::flash('success', __('front/homePage.Order Created Successfully'));
            redirect()->route('front.order.done')->with('order_id', $order->id);
        } else {
            Session::flash('error', __('front/homePage.Order Creation Failed, Please Try Again'));
            redirect()->route('front.order.billing');
        }
    }

    public function payByPaymob($order)
    {
        $first_step = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
            "api_key" => env('PAYMOB_TOKEN')
        ])->json();

        $auth_token = $first_step['token'];

        $second_step = Http::acceptJson()->post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" =>  $auth_token,
            "delivery_needed" => "false",
            "amount_cents" => number_format(($order->subtotal_final + $order->delivery_fees) * 100, 0),
            "currency" => "EGP",
            "items" => []
        ])->json();

        $order_id = $second_step['id'];

        $third_step = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            "auth_token" => $auth_token,
            "amount_cents" => number_format(($order->subtotal_final + $order->delivery_fees) * 100, 0),
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
            "integration_id" => env('PAYMOB_CLIENT_ID'),
        ])->json();

        $payment_key = $third_step['token'];

        if ($payment_key) {
            redirect()->away("https://accept.paymobsolutions.com/api/acceptance/iframes/" . env('PAYMOB_IFRAM_ID') . "?payment_token=$payment_key");
        }
    }
}

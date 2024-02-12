<?php

namespace App\Http\Livewire\Front\Order\Payment;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrderPaymentDetails extends Component
{
    public $points, $points_egp;
    public $balance;
    public $payment_method;
    public $iframe;

    protected $listeners = [
        'setOrderFinalPrice',
        'submit'
    ];

    public function rules()
    {
        return [
            'points' => 'numeric|min:0|integer|max:' . auth()->user()->valid_points,
            'balance' => 'numeric|min:0|max:' . auth()->user()->balance,
        ];
    }

    public function mount()
    {
        $this->points = 0;
        $this->points_egp = 0;
        $this->balance = 0;
    }

    public function render()
    {
        return view('livewire.front.order.payment.order-payment-details');
    }

    public function updatedPoints($points)
    {
        $this->validateOnly('points');

        $this->points_egp =  $points * config('settings.points_conversion_rate');
    }

    public function updatedBalance()
    {
        $this->validateOnly('balance');
    }

    public function payBy($payment_method)
    {
        $this->payment_method = $payment_method;
        $this->emitTo('front.order.payment.order-payment-summary', 'updatePaymentMethod', $this->payment_method);
    }

    public function submit()
    {
        $this->emitTo('front.order.payment.order-payment-summary', 'submit', $this->payment_method, $this->balance, $this->points, $this->points_egp);
    }
}

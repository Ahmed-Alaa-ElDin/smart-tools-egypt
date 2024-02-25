<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class NewOrderPaymentPart extends Component
{
    public $customer, $code, $coupon_id, $message, $wallet = 0.00, $points = 0, $payment_method = 1;

    protected $listeners = [
        'customerUpdated',
        'getPaymentData',
    ];

    ############## Render :: Start ##############
    public function render()
    {
        return view('livewire.admin.orders.new-order-payment-part');
    }
    ############## Render :: End ##############

    ############## Get Customer Data :: Start ##############
    public function customerUpdated($customer_id)
    {
        if ($customer_id) {
            $this->customer = User::findOrFail($customer_id);
        } else {
            $this->customer = null;
            $this->coupon_id = null;
            $this->message = null;
            $this->code = null;
            $this->wallet = 0.00;
            $this->points = 0;
            $this->payment_method = 1;
        }
    }
    ############## Get Customer Data :: End ##############

    ############## Apply Coupon :: Start ##############
    public function couponCheck()
    {
        try {
            $coupon = Coupon::where('code', $this->code)
                ->where('expire_at', '>=', Carbon::now())
                ->where(fn ($q) => $q->where('number', '>', 0)->orWhere('number', null))
                ->firstOrFail();

            $this->coupon_id = $coupon->id;

            $this->message = [
                'message' => __('admin/ordersPages.Coupon Applied', ['code' => $this->code]),
                'status' => 1
            ];
        } catch (\Throwable $th) {
            $this->message = [
                'message' => __('admin/ordersPages.Coupon Failed', ['code' => $this->code]),
                'status' => 0
            ];
        }
    }
    ############## Apply Coupon :: End ##############

    ############## Remove Coupon :: Start ##############
    public function clearCoupon()
    {
        $this->coupon_id = null;

        $this->message = null;
    }
    ############## Remove Coupon :: End ##############


    ############## Pay Using Wallet :: Start ##############
    public function updatedWallet($value)
    {
        $this->wallet = $value > 0 ? ($value <= $this->customer->balance ? $value : $this->customer->balance) : 0;
    }
    ############## Pay Using Wallet :: End ##############

    ############## Pay Using Points :: Start ##############
    public function updatedPoints($value)
    {
        $this->points = $value > 0 ? ($value <= $this->customer->points ? $value : $this->customer->points) : 0;
    }
    ############## Pay Using Points :: End ##############

    ############## Send Payment Data To Parent Order Form :: Start ##############
    public function getPaymentData()
    {
        $this->dispatch(
            'setPaymentData',
            data: [
                "coupon_id" => $this->coupon_id,
                "wallet" => $this->wallet,
                "points" => $this->points,
                "payment_method" => $this->payment_method
            ]
        )->to('admin.orders.order-form');
    }
    ############## Send Payment Data To Parent Order Form :: End ##############
}

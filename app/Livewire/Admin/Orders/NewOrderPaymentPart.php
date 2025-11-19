<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class NewOrderPaymentPart extends Component
{
    public $customerId, $customer, $code, $coupon_id, $message, $wallet = 0.00, $points = 0, $payment_method = 1;

    protected $listeners = [
        'setUserData',
        'setPaymentDataToPaymentPart',
    ];

    public function mount()
    {
        if ($this->customerId) {
            $data['customer']['id'] = $this->customerId;
            $this->setUserData($data);
        }
    }

    ############## Render :: Start ##############
    public function render()
    {
        return view('livewire.admin.orders.new-order-payment-part');
    }
    ############## Render :: End ##############

    ############## Get Customer Data :: Start ##############
    public function setUserData($data)
    {
        if ($data['customer']) {
            $this->customer = User::findOrFail($data['customer']['id']);
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
    ############## Update Customer Data :: End ##############

    ############## Change Payment Method :: Start ##############
    public function updatedPaymentMethod()
    {
        $this->setPaymentData();
    }
    ############## Change Payment Method :: End ##############

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

        $this->setPaymentData();
    }
    ############## Apply Coupon :: End ##############

    ############## Remove Coupon :: Start ##############
    public function clearCoupon()
    {
        $this->coupon_id = null;

        $this->setPaymentData();

        $this->message = null;
    }
    ############## Remove Coupon :: End ##############


    ############## Pay Using Wallet :: Start ##############
    public function updatedWallet($value)
    {
        $this->wallet = $value > 0 ? ($value <= $this->customer->balance ? $value : $this->customer->balance) : 0;

        $this->setPaymentData();
    }
    ############## Pay Using Wallet :: End ##############

    ############## Pay Using Points :: Start ##############
    public function updatedPoints($value)
    {
        $this->points = $value > 0 ? ($value <= $this->customer->validPoints ? $value : $this->customer->validPoints) : 0;

        $this->setPaymentData();
    }
    ############## Pay Using Points :: End ##############

    ############## Send Payment Data To Parent Order Form :: Start ##############
    public function setPaymentData()
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

    ############## Set Payment Data From Parent Order Form :: Start ##############
    public function setPaymentDataToPaymentPart($data)
    {
        $this->coupon_id = $data['coupon_id'];
        $this->wallet = $data['wallet'];
        $this->points = $data['points'];
        $this->payment_method = $data['payment_method'];
    }
    ############## Set Payment Data From Parent Order Form :: End ##############
}

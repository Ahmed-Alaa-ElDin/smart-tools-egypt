<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class NewOrderPaymentPart extends Component
{
    public $customer, $code, $coupon_id, $message, $wallet = 0.00, $points = 0, $payment_method = 1;

    protected $listeners = [
        'customerUpdated'
    ];

    public function render()
    {
        return view('livewire.admin.orders.new-order-payment-part');
    }

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

    public function clearCoupon()
    {
        $this->coupon_id = null;

        $this->message = null;
    }
}

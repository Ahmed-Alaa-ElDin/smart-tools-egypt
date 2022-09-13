<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaymentHistory extends Component
{
    public $order_id;

    protected $listeners = [
        'paymentConfirmed',
        'paymentDetails',
        'refundConfirmed'
    ];

    public function render()
    {
        $this->order = Order::with([
            'payments' => fn ($q) => $q->orderBy('updated_at', 'desc'),
            'user' => fn ($q) => $q->with([
                'phones' => fn ($q) => $q->where('default', 1)
            ])->select('id', 'f_name', 'l_name')
        ])->findOrFail($this->order_id);

        $this->order->unpaid = $this->order->payments->where('payment_status', 1)->sum('payment_amount');
        $this->order->paid = $this->order->payments->where('payment_status', 2)->sum('payment_amount');
        $this->order->refund = $this->order->payments->where('payment_status', 5)->sum('payment_amount');
        $this->order->refunded = $this->order->payments->where('payment_status', 4)->sum('payment_amount');

        return view('livewire.admin.orders.payment-history');
    }

    public function paymentConfirm($payment_id, $payment_amount)
    {
        $this->dispatchBrowserEvent('swalConfirm', [
            "text" => __('admin/ordersPages.Are you sure, you want to mark this transaction as done?'),
            'confirmButtonText' => __('admin/ordersPages.Yes'),
            'denyButtonText' => __('admin/ordersPages.No'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'paymentDetails',
            'id' => $payment_id,
            'details' => [
                'payment_amount' => $payment_amount,
            ]
        ]);
    }

    public function paymentDetails($payment_id, $details)
    {
        $this->dispatchBrowserEvent('swalGetPaymentData', [
            "title" => __('admin/ordersPages.Enter the payment details'),
            "html" => '<div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder=' . __("admin/ordersPages.Enter the payment amount") . '
                        dir="ltr" step="0.01" min="0" max="' . $details['payment_amount'] . '" value="' . $details['payment_amount'] . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div>
                        <label class="text-gray-600" for="transaction_id">' . __("admin/ordersPages.Transaction id") . '</label>
                        <input type="text" id="transaction_id" dir="ltr"  placeholder="' . __("admin/ordersPages.Enter the transaction id") . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                </div>
                ',
            'confirmButtonText' => __('admin/ordersPages.Confirm'),
            'denyButtonText' => __('admin/ordersPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'paymentConfirmed',
            'id' => $payment_id,
        ]);
    }

    public function paymentConfirmed($id, $value)
    {
        $payment = Payment::with('order')->findOrFail($id);
        $payment_amount = $value[0] <= $payment->payment_amount ? $value[0] : $payment->payment_amount;
        $transaction_id = $value[1];
        $order = $payment->order;

        DB::beginTransaction();

        try {
            // Check if transaction in pending state and there is a money to be paid
            if ($payment->payment_status == 1 && $payment->payment_amount >= 0) {

                // Check if payment amount equal to the total transaction amount
                if ($payment_amount == $payment->payment_amount) {
                    $payment->update([
                        'payment_status' => 2,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ])
                    ]);

                    $order->update([
                        'should_pay' => $order->should_pay - $payment->payment_amount
                    ]);
                } else {
                    $order->payments()->create([
                        'order_id' => $order->id,
                        'user_id' => $payment->user_id,
                        'payment_amount' => $payment_amount,
                        'payment_method' => $payment->payment_method,
                        'payment_status' => 2,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ]),
                    ]);

                    $payment->update([
                        "payment_amount" => $payment->payment_amount - $payment_amount
                    ]);

                    $order->update([
                        'should_pay' => $order->should_pay - $payment_amount
                    ]);
                }

                // Check if the order is already paid
                if ($order->should_pay <= 0) {
                    if ($order->tracking_number) {
                        if (editBostaOrder($order, $order->id)) {
                            // update order in database
                            $order->update([
                                'status_id' => 3,
                            ]);

                            $order->statuses()->attach(3);
                        }
                    } else {
                        $bosta_order = createBostaOrder($order);

                        if ($bosta_order['status']) {
                            // update order in database
                            $order->update([
                                'tracking_number' => $bosta_order['data']['trackingNumber'],
                                'order_delivery_id' => $bosta_order['data']['_id'],
                                'status_id' => 3,
                            ]);

                            $order->statuses()->attach(3);

                            // update coupon usage
                            if ($order->coupon_id != null) {
                                $coupon = Coupon::find($order->coupon_id);

                                $coupon->update([
                                    'number' => $coupon->number != null && $coupon->number > 0 ? $coupon->number - 1 : $coupon->number,
                                ]);
                            }
                        }
                    }
                }

                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                    'icon' => 'success'
                ]);
            } else {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.This transaction is already completed"),
                    'icon' => 'error'
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                'icon' => 'error'
            ]);

            DB::rollBack();
        }
    }

    public function refundDestination($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);

        $this->dispatchBrowserEvent('swalGetRefundData', [
            "title" => __('admin/ordersPages.Enter the payment details'),
            "html" => '
                <div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder=' . __("admin/ordersPages.Enter the payment amount") . '
                        dir="ltr" step="0.01" min="0" max="' . abs($payment->payment_amount) . '" value="' . abs($payment->payment_amount) . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div>
                        <label class="text-gray-600" for="transaction_id">' . __("admin/ordersPages.Transaction id") . '</label>
                        <input type="text" id="transaction_id" dir="ltr"  placeholder="' . __("admin/ordersPages.Enter the transaction id") . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div class="flex items-center justify-around p-2">
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="wallet">' . __("admin/ordersPages.Customer's Wallet") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="wallet" name="type" value="10" checked/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="other">' . ($payment->payment_method == 1 ? __("admin/ordersPages.COD") : ($payment->payment_method == 2 || $payment->payment_method == 3 ? __("admin/ordersPages.Customer's Card") : ($payment->payment_method == 4 ? __("admin/ordersPages.Customer's Vodafone Wallet") : (__('N/A'))))) . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="other" name="type" value="10"/>
                        </div>
                    </div>
                </div>
                ',
            'confirmButtonText' => __('admin/ordersPages.Confirm'),
            'denyButtonText' => __('admin/ordersPages.Cancel'),
            'denyButtonColor' => 'red',
            'confirmButtonColor' => 'green',
            'focusDeny' => true,
            'icon' => 'warning',
            'method' => 'refundConfirmed',
            'id' => $payment_id,
        ]);
    }

    public function refundConfirmed($id, $value)
    {
        $payment = Payment::with('order', 'user')->findOrFail($id);
        $payment_amount = $value[0] <= abs($payment->payment_amount) ? $value[0] : abs($payment->payment_amount);
        $transaction_id = $value[1];
        $type = $value[2];
        $order = $payment->order;
        $user = $payment->user;

        DB::beginTransaction();

        // return to customer's wallet
        if ($type == 10) {
            // return total amount of payment
            if ($payment_amount == abs($payment->payment_amount)) {
                try {
                    // edit payment
                    $payment->update([
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ])
                    ]);

                    // edit user
                    $user->update([
                        'balance' => $user->balance + $payment_amount
                    ]);

                    if ($order->should_get - $payment_amount  <= 0 && $order->num_of_items == 0) {
                        // edit order
                        $order->update([
                            'should_get' => $order->should_get - $payment_amount,
                            'status_id' => 9 // Canceled
                        ]);

                        // edit order's status
                        $order->statuses()->attach(9);
                    } else {
                        $order->update([
                            'should_get' => $order->should_get - $payment_amount,
                            'status_id' => 12 // Edit Approved
                        ]);

                        // edit order's status
                        $order->statuses()->attach(12);
                    }

                    DB::commit();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                        'icon' => 'success'
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                        'icon' => 'error'
                    ]);
                }
            }
            // return some amount
            else {
                try {
                    // edit payment
                    $payment->update([
                        'payment_amount' => $payment->payment_amount + $payment_amount,
                    ]);

                    // edit order's payments
                    $order->payments()->create([
                        'order_id' => $payment->order_id,
                        'user_id' => $payment->user_id,
                        'payment_amount' => -1 * $payment_amount,
                        'payment_method' => $payment->payment_method,
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment->payment_amount + ($payment_amount * 100),
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ]),
                    ]);

                    // edit user
                    $user->update([
                        'balance' => $user->balance + $payment_amount
                    ]);

                    DB::commit();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                        'icon' => 'success'
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                        'icon' => 'error'
                    ]);
                }
            }
        }
        // return to customer By COD
        elseif ($type == 1) {
            // return total amount of payment
            if ($payment_amount == abs($payment->payment_amount)) {
                try {
                    // edit payment
                    $payment->update([
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ])
                    ]);

                    DB::commit();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                        'icon' => 'success'
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                        'icon' => 'error'
                    ]);
                }
            }
            // return some amount
            else {
                try {
                    // edit payment
                    $payment->update([
                        'payment_amount' => $payment->payment_amount + $payment_amount,
                    ]);

                    // edit order's payments
                    $order->payments()->create([
                        'order_id' => $payment->order_id,
                        'user_id' => $payment->user_id,
                        'payment_amount' => -1 * $payment_amount,
                        'payment_method' => $payment->payment_method,
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment->payment_amount + ($payment_amount * 100),
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ]),
                    ]);

                    DB::commit();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                        'icon' => 'success'
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                        'icon' => 'error'
                    ]);
                }
            }
        }
        // return to customer By Card
        elseif ($type == 2 || $type == 3) {
        }
        // return to customer's vodafone cash wallet
        elseif ($type == 4) {
            // return total amount of payment
            if ($payment_amount == abs($payment->payment_amount)) {
                try {
                    // edit payment
                    $payment->update([
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ])
                    ]);

                    if ($order->should_get <= 0 && $order->num_of_items == 0) {
                        // edit order
                        $order->update([
                            'should_get' => $order->should_get - $payment_amount,
                            'status_id' => 9 // Canceled
                        ]);

                        // edit order's status
                        $order->statuses()->attach(9);
                    } else {
                        $order->update([
                            'should_get' => $order->should_get - $payment_amount,
                            'status_id' => 12 // Edit Approved
                        ]);

                        // edit order's status
                        $order->statuses()->attach(12);
                    }

                    DB::commit();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                        'icon' => 'success'
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                        'icon' => 'error'
                    ]);
                }
            }
            // return some amount
            else {
                try {
                    // edit payment
                    $payment->update([
                        'payment_amount' => $payment->payment_amount + $payment_amount,
                    ]);

                    // edit order's payments
                    $order->payments()->create([
                        'order_id' => $payment->order_id,
                        'user_id' => $payment->user_id,
                        'payment_amount' => -1 * $payment_amount,
                        'payment_method' => $payment->payment_method,
                        'payment_status' => 4,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment->payment_amount + ($payment_amount * 100),
                            "transaction_id" => $transaction_id ?? null,
                            "source_data_sub_type" => "By Admin"
                        ]),
                    ]);

                    DB::commit();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.This transaction has been completed successfully"),
                        'icon' => 'success'
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;
                    DB::rollBack();

                    $this->dispatchBrowserEvent('swalDone', [
                        "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                        'icon' => 'error'
                    ]);
                }
            }
        }
    }

    public function addPayment()
    {
        # code...
    }

    public function addRefund()
    {
        # code...
    }
}

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
        'refundConfirmed',
        'paymentAddingConfirmed',
        'refundAddingConfirmed'
    ];

    ############## Render :: Start ##############
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
    ############## Render :: End ##############

    ############## Pop-up Payment confirm message :: Start ##############
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
    ############## Pop-up Payment confirm message :: End ##############

    ############## Pop-up Payment Details Modal :: Start ##############
    public function paymentDetails($payment_id, $details)
    {
        $this->dispatchBrowserEvent('swalGetPaymentData', [
            "title" => __('admin/ordersPages.Enter the payment details'),
            "html" => '<div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder="' . __("admin/ordersPages.Enter the payment amount") . '"
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
    ############## Pop-up Payment Details Modal :: End ##############

    ############## Pop-up Payment Confirmed and Database updates :: Start ##############
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
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
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
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ]),
                    ]);

                    $payment->update([
                        "payment_amount" => $payment->payment_amount - $payment_amount
                    ]);

                    $order->update([
                        'should_pay' => $order->should_pay - $payment_amount
                    ]);
                }

                if ($payment->payment_method == 10) {
                    if ($payment->user->balance - $payment_amount >= 0) {
                        $payment->user->update([
                            'balance' => $payment->user->balance - $payment_amount,
                        ]);
                    } else {
                        $payment->user->update([
                            'balance' => 0,
                        ]);

                        $order->payments()->create([
                            'order_id' => $order->id,
                            'user_id' => $payment->user_id,
                            'payment_amount' => -1 * ($payment->user->balance - $payment_amount),
                            'payment_method' => $payment->payment_method,
                            'payment_status' => 1,
                            'payment_details' => json_encode([
                                "amount_cents" => ($payment->user->balance - $payment_amount) * 100,
                                "transaction_id" => null,
                                "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                            ]),
                        ]);
                    }
                }

                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.This transaction has been done successfully"),
                    'icon' => 'success'
                ]);
            } else {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.This transaction is already done"),
                    'icon' => 'error'
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                'icon' => 'error'
            ]);

            DB::rollBack();
        }
    }
    ############## Pop-up Payment Confirmed and Database updates :: End ##############

    ############## Pop-up Refund Destination Choices :: Start ##############
    public function refundDestination($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $disabled = in_array($payment->payment_method, [2, 3]) ? 'disabled' : '';
        $transaction_id = in_array($payment->payment_method, [2, 3]) ? json_decode($payment->payment_details)->transaction_id : "";

        $this->dispatchBrowserEvent('swalGetRefundData', [
            "title" => __('admin/ordersPages.Enter the payment details'),
            "html" => '
                <div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder="' . __("admin/ordersPages.Enter the payment amount") . '"
                        dir="ltr" step="0.01" min="0" max="' . abs($payment->payment_amount) . '" value="' . abs($payment->payment_amount) . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div>
                        <label class="text-gray-600" for="transaction_id">' . __("admin/ordersPages.Transaction id") . '</label>
                        <input type="text" id="transaction_id" dir="ltr"  placeholder="' . __("admin/ordersPages.Enter the transaction id") . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300"
                        value =' . $transaction_id . ' ' . $disabled . '>
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
                            type="radio" id="other" name="type" value="' . $payment->payment_method . '"/>
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
    ############## Pop-up Refund Destination Choices :: End ##############

    ############## Refund Confirmed :: Start ##############
    public function refundConfirmed($id, $value)
    {
        $payment = Payment::with('order', 'user')->findOrFail($id);
        $payment_amount = $value[0] <= abs($payment->payment_amount) ? $value[0] : abs($payment->payment_amount);
        $transaction_id = $value[1];
        $type = $value[2];
        $order = $payment->order;
        $user = $payment->user;
        $partial_payment = $payment_amount != abs($payment->payment_amount);
        $return_to_wallet = $type == 10;

        // return to customer by delivery, vodafone cash or customer's wallet
        if (in_array($type, [1, 4, 10])) {
            $this->refundSuccess($payment, $type, $payment_amount, $transaction_id, $order, $user, $partial_payment, $return_to_wallet);
        }

        // return to customer By Card
        elseif (in_array($type, [2, 3])) {
            try {
                $old_payment = Payment::where('payment_details->transaction_id', $transaction_id)
                    ->where('payment_status', 2)
                    ->firstOrFail();
            } catch (\Throwable $th) {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.Wrong transaction id, please try again"),
                    'icon' => 'error'
                ]);
            }

            if (refundRequestPaymob($transaction_id, $payment_amount)) {
                $this->refundSuccess($payment, $type, $payment_amount, $transaction_id, $order, $user, $partial_payment, $return_to_wallet, $old_payment);
            } else {
                $payment->update([
                    'payment_status' => 3,
                ]);

                $order->payments()->create([
                    'order_id' => $payment->order_id,
                    'user_id' => $payment->user_id,
                    'payment_amount' => $payment->payment_amount,
                    'payment_method' => $payment->payment_method,
                    'payment_status' => 5,
                    'payment_details' => $payment->payment_details,
                ]);
            }
        }
    }
    ############## Refund Confirmed :: End ##############

    ############## Refund Steps :: Start ##############
    public function refundSuccess(
        $payment,
        $type,
        $payment_amount,
        $transaction_id,
        $order,
        $user,
        $partial_payment = false,
        $return_to_wallet = false,
        $old_payment = null
    ) {
        DB::beginTransaction();

        try {
            if ($partial_payment) {
                // edit order's payments
                $order->payments()->create([
                    'order_id' => $payment->order_id,
                    'user_id' => $payment->user_id,
                    'payment_amount' => $payment->payment_amount + $payment_amount,
                    'payment_method' => $payment->payment_method,
                    'payment_status' => 5,
                    'payment_details' => null,
                ]);
            }

            // edit payment
            $payment->update([
                'payment_amount' => -1 * $payment_amount,
                'payment_method' => $type,
                'payment_status' => 4,
                'payment_details' => json_encode([
                    "amount_cents" => $payment_amount * 100,
                    "transaction_id" => $transaction_id,
                    "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                ])
            ]);

            // update user wallet
            if ($return_to_wallet) {
                $user->update([
                    'balance' => $user->balance + $payment_amount
                ]);
            }

            // update Order
            $order->update([
                'should_get' => $order->should_get - $payment_amount,
            ]);

            if ($old_payment) {
                $old_payment->update([
                    'payment_amount' => $old_payment->payment_amount - $payment_amount
                ]);
            }

            DB::commit();

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.This transaction has been done successfully"),
                'icon' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $payment->update([
                'payment_status' => 3,
            ]);

            $order->payments()->create([
                'order_id' => $payment->order_id,
                'user_id' => $payment->user_id,
                'payment_amount' => $payment->payment_amount,
                'payment_method' => $payment->payment_method,
                'payment_status' => 5,
                'payment_details' => $payment->payment_details,
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Unexpected error occurred, please try again"),
                'icon' => 'error'
            ]);
        }
    }
    ############## Refund Steps :: End ##############

    public function addPayment()
    {
        $this->dispatchBrowserEvent('swalGetNewPaymentData', [
            "title" => __('admin/ordersPages.Enter the payment details'),
            "html" => '
                <div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder="' . __("admin/ordersPages.Enter the payment amount") . '"
                        dir="ltr" step="0.01" min="0" required
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div class="flex flex-wrap items-center justify-around p-2 gap-3">
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="wallet">' . __("admin/ordersPages.Customer's Wallet") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="wallet" name="type" value="10" checked/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="cod">' . __("admin/ordersPages.COD") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="cod" name="type" value="1"/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="card">' . __("admin/ordersPages.Credit / Debit Card") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="card" name="type" value="2"/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="installment">' . __("admin/ordersPages.Installment") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="installment" name="type" value="3"/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="vodafone">' . __("admin/ordersPages.Vodafone Cash") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="vodafone" name="type" value="4"/>
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
            'method' => 'paymentAddingConfirmed',
        ]);
    }

    public function paymentAddingConfirmed($value)
    {
        $payment_amount = $value[0];
        $type = $value[1];
        $order = $this->order;

        if ($payment_amount > 0) {
            $order->payments()->create([
                'order_id' => $order->order_id,
                'user_id' => $order->user_id,
                'payment_amount' => $payment_amount,
                'payment_method' => $type,
                'payment_status' => 1,
                'payment_details' =>  json_encode([
                    "amount_cents" => $payment_amount * 100,
                    "transaction_id" => null,
                    "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                ])
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.This transaction has been added successfully"),
                'icon' => 'success'
            ]);
        } else {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Please add a valid amount and try again"),
                'icon' => 'error'
            ]);
        }
    }

    public function addRefund()
    {
        $this->dispatchBrowserEvent('swalGetNewRefundData', [
            "title" => __('admin/ordersPages.Enter the payment details'),
            "html" => '
                <div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder="' . __("admin/ordersPages.Enter the payment amount") . '"
                        dir="ltr" step="0.01" min="0" required
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div>
                        <label class="text-gray-600" for="transaction_id">' . __("admin/ordersPages.Transaction id") . '</label>
                        <input type="text" id="transaction_id" dir="ltr"  placeholder="' . __("admin/ordersPages.Enter the transaction id") . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div class="flex flex-wrap items-center justify-around p-2 gap-3">
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="wallet">' . __("admin/ordersPages.Customer's Wallet") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="wallet" name="type" value="10" checked/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="cod">' . __("admin/ordersPages.COD") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="cod" name="type" value="1"/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="card">' . __("admin/ordersPages.Credit / Debit Card") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="card" name="type" value="2"/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="installment">' . __("admin/ordersPages.Installment") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="installment" name="type" value="3"/>
                        </div>
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="vodafone">' . __("admin/ordersPages.Vodafone Cash") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="vodafone" name="type" value="4"/>
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
            'method' => 'refundAddingConfirmed',
        ]);
    }

    public function refundAddingConfirmed($value)
    {
        $payment_amount = $value[0];
        $transaction_id = $value[1];
        $type = $value[2];
        $order = $this->order;

        if ($payment_amount > 0) {
            $order->payments()->create([
                'order_id' => $order->order_id,
                'user_id' => $order->user_id,
                'payment_amount' => -1 * $payment_amount,
                'payment_method' => $type,
                'payment_status' => 5,
                'payment_details' =>  json_encode([
                    "amount_cents" => $payment_amount * 100,
                    "transaction_id" => $transaction_id,
                    "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                ])
            ]);

            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.This transaction has been added successfully"),
                'icon' => 'success'
            ]);
        } else {
            $this->dispatchBrowserEvent('swalDone', [
                "text" => __("admin/ordersPages.Please add a valid amount and try again"),
                'icon' => 'error'
            ]);
        }
    }

    public function createEditDelivery()
    {
        $order = $this->order;

        if ($order->order_delivery_id) {
            if (editBostaOrder($order, $order->id)) {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.The delivery has been edited successfully"),
                    'icon' => 'success'
                ]);
            } else {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.The delivery hasn't been edited"),
                    'icon' => 'error'
                ]);
            }
        } else {
            $bosta_order = createBostaOrder($order);

            if ($bosta_order['status']) {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.The delivery has been created successfully"),
                    'icon' => 'success'
                ]);

                $order->update([
                    'tracking_number' => $bosta_order['data']['trackingNumber'],
                    'order_delivery_id' => $bosta_order['data']['_id'],
                    'status_id' => 3,
                ]);
            } else {
                $this->dispatchBrowserEvent('swalDone', [
                    "text" => __("admin/ordersPages.The delivery hasn't been created"),
                    'icon' => 'error'
                ]);
            }
        }
    }
}

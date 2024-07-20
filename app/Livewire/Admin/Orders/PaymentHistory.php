<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\Transaction;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;
use App\Services\Front\Payments\PaymentService;
use App\Services\Front\Payments\Gateways\CardGateway;
use App\Services\Front\Payments\Gateways\InstallmentGateway;

class PaymentHistory extends Component
{
    public $order_id;
    public $order;

    protected $listeners = [
        'paymentConfirmed',
        'paymentDetails',
        'refundConfirmed',
        'paymentAddingConfirmed',
        'refundAddingConfirmed',
        'removeTransaction',
    ];

    ############## Render :: Start ##############
    public function render()
    {
        $this->order = Order::with([
            'transactions' => fn ($q) => $q->orderBy('updated_at', 'desc')->withTrashed(),
            'invoice',
            'user' => fn ($q) => $q->with([
                'phones' => fn ($q) => $q->where('default', 1)
            ])->select('id', 'f_name', 'l_name')
        ])->findOrFail($this->order_id);

        return view('livewire.admin.orders.payment-history');
    }
    ############## Render :: End ##############

    ############## Pop-up Payment confirm message :: Start ##############
    public function paymentConfirm($payment_id, $payment_amount)
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to mark this transaction as done?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'paymentDetails',
            id: $payment_id,
            details: [
                'payment_amount' => $payment_amount,
            ]
        );
    }
    ############## Pop-up Payment confirm message :: End ##############

    ############## Pop-up Payment Details Modal :: Start ##############
    public function paymentDetails($id, $details)
    {
        $this->dispatch(
            'swalGetPaymentData',
            title: __('admin/ordersPages.Enter the payment details'),
            html: '<div class="flex flex-col p-2 gap-3">
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
            confirmButtonText: __('admin/ordersPages.Confirm'),
            denyButtonText: __('admin/ordersPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'paymentConfirmed',
            id: $id,
        );
    }
    ############## Pop-up Payment Details Modal :: End ##############

    ############## Pop-up Payment Confirmed and Database updates :: Start ##############
    public function paymentConfirmed($id, $payment_amount, $transaction_id)
    {
        $transaction = Transaction::with('order')->findOrFail($id);
        // Check if the paid amount is larger that the transaction payment_amount
        $payment_amount = $payment_amount <= $transaction->payment_amount ? $payment_amount : $transaction->payment_amount;

        $order = $transaction->order;

        DB::beginTransaction();

        try {
            // Check if transaction in pending state and there is a money to be paid
            if ($transaction->payment_status_id == PaymentStatus::Pending->value && $transaction->payment_amount >= 0) {

                if ($transaction->payment_method_id == PaymentMethod::Wallet->value) {
                    // Check if user has enough balance to pay from his wallet
                    if ($transaction->user->balance - $payment_amount >= 0) {
                        $transaction->user->update([
                            'balance' => $transaction->user->balance - $payment_amount,
                        ]);
                    } else {
                        $transaction->user->update([
                            'balance' => 0,
                        ]);

                        $order->transactions()->create([
                            'order_id' => $order->id,
                            'invoice_id' => $order->invoice->id,
                            'user_id' => $transaction->user_id,
                            'payment_amount' => -1 * ($transaction->user->balance - $payment_amount),
                            'payment_method_id' => $transaction->payment_method_id,
                            'payment_status_id' => PaymentStatus::Pending->value,
                            'payment_details' => json_encode([
                                "amount_cents" => ($transaction->user->balance - $payment_amount) * 100,
                                "transaction_id" => null,
                                "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                            ]),
                        ]);

                        // Edit the payment amount
                        $payment_amount = $transaction->user->balance;
                    }
                }

                // Check if payment amount equal to the total transaction amount
                if ($payment_amount == $transaction->payment_amount) {
                    $transaction->update([
                        'payment_status_id' => PaymentStatus::Paid->value,
                        'service_provider_transaction_id' => $transaction_id ?? null,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ])
                    ]);
                } else {
                    $order->transactions()->create([
                        'order_id' => $order->id,
                        'invoice_id' => $order->invoice->id,
                        'user_id' => $transaction->user_id,
                        'payment_amount' => $payment_amount,
                        'payment_method_id' => $transaction->payment_method_id,
                        'payment_status_id' => PaymentStatus::Paid->value,
                        'service_provider_transaction_id' => $transaction_id ?? null,
                        'payment_details' => json_encode([
                            "amount_cents" => $payment_amount * 100,
                            "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                        ]),
                    ]);

                    $transaction->update([
                        "payment_amount" => $transaction->payment_amount - $payment_amount
                    ]);
                }

                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.This transaction has been done successfully"),
                    icon: 'success'
                );
            } else {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.This transaction is already done"),
                    icon: 'error'
                );
            }

            DB::commit();
        } catch (\Throwable $th) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Unexpected error occurred, please try again"),
                icon: 'error'
            );

            DB::rollBack();
        }
    }
    ############## Pop-up Payment Confirmed and Database updates :: End ##############

    ############## Pop-up Refund Destination Choices :: Start ##############
    public function refundConfirm($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $disabled = in_array($transaction->payment_method_id, [PaymentMethod::Card->value, PaymentMethod::Installments->value]) ? 'disabled' : '';
        $serviceProviderTransactionId = json_decode($transaction->payment_details)->transaction_id ?? "";

        $this->dispatch(
            'swalGetRefundData',
            title: __('admin/ordersPages.Enter the payment details'),
            html: '
                <div class="flex flex-col p-2 gap-3">
                    <div>
                        <label class="text-gray-600" for="amount">' . __("admin/ordersPages.Payment amount") . '</label>
                        <input type="number" id="amount" placeholder="' . __("admin/ordersPages.Enter the payment amount") . '"
                        dir="ltr" step="0.01" min="0" max="' . abs($transaction->payment_amount) . '" value="' . abs($transaction->payment_amount) . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300">
                    </div>
                    <div>
                        <label class="text-gray-600" for="transaction_id">' . __("admin/ordersPages.Transaction id") . '</label>
                        <input type="text" id="transaction_id" dir="ltr"  placeholder="' . __("admin/ordersPages.Enter the transaction id") . '"
                        class="text-center focus:ring-primary focus:border-primary flex-1 block w-full rounded-md sm:text-sm border-gray-300"
                        value ="' . $serviceProviderTransactionId . '" ' . $disabled . '>
                    </div>
                    <div class="flex items-center justify-around p-2">
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="wallet">' . __("admin/ordersPages.Customer's Wallet") . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="wallet" name="type" value="' . PaymentMethod::Wallet->value . '" checked/>
                        </div>' .
                ($transaction->payment_method_id == PaymentMethod::Wallet->value ? "" : '
                        <div class="flex items-center Justify-center gap-2">
                            <label class="text-gray-600 text-sm font-bold select-none cursor-pointer m-0" for="other">' . (__("admin/ordersPages." . PaymentMethod::getKeyFromValue($transaction->payment_method_id)) ?? __('N/A')) . '</label>
                            <input
                            class="appearance-none checked:bg-secondary checked:border-white outline-none ring-0 cursor-pointer"
                            type="radio" id="other" name="type" value="' . $transaction->payment_method_id . '"/>
                        </div>
                        ') .
                '</div>
                </div>
                ',
            confirmButtonText: __('admin/ordersPages.Confirm'),
            denyButtonText: __('admin/ordersPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'refundConfirmed',
            id: $transaction_id,
        );
    }
    ############## Pop-up Refund Destination Choices :: End ##############

    ############## Refund Confirmed :: Start ##############
    public function refundConfirmed($id, $payment_amount, $transaction_id, $type)
    {
        $transaction = Transaction::with('order', 'user')->findOrFail($id);
        $order = $transaction->order;
        $user = $transaction->user;
        $partial_payment = $payment_amount != abs($transaction->payment_amount);
        $return_to_wallet = $type == PaymentMethod::Wallet->value;

        // Check if transaction $payment_amount is not greater than the total transaction amount
        if ($payment_amount > abs($transaction->payment_amount)) {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.The refund amount is greater than the total transaction amount"),
                icon: 'error'
            );
            return;
        }

        // return to customer by delivery, vodafone cash or customer's wallet
        if (in_array($type, [PaymentMethod::Cash->value, PaymentMethod::VodafoneCash->value, PaymentMethod::Wallet->value])) {
            $this->refundSuccess($transaction, $type, $payment_amount, $transaction_id, $order, $user, $partial_payment, $return_to_wallet);
        }

        // return to customer By Card
        elseif (in_array($type, [PaymentMethod::Card->value, PaymentMethod::Installments->value])) {
            $old_transaction_id = json_decode($transaction->payment_details)->transaction_id ?? null;

            $old_transaction = Transaction::where('service_provider_transaction_id', $old_transaction_id)->first();

            $paymentGateway = $type == PaymentMethod::Card->value ? new CardGateway() : new InstallmentGateway();

            $paymentService = new PaymentService($paymentGateway);

            $new_transaction_id = $paymentService->refundOrVoid($old_transaction, $payment_amount);

            if ($new_transaction_id) {
                $this->refundSuccess($transaction, $type, $new_transaction_id, $new_transaction_id, $order, $user, $partial_payment, $return_to_wallet);
            } else {
                $transaction->update([
                    'payment_status_id' => PaymentStatus::RefundFailed->value,
                ]);

                $order->transactions()->create([
                    'order_id' => $transaction->order_id,
                    'invoice_id' => $transaction->invoice_id,
                    'user_id' => $transaction->user_id,
                    'payment_amount' => $transaction->payment_amount,
                    'payment_method_id' => $transaction->payment_method_id,
                    'payment_status_id' => PaymentStatus::Refundable->value,
                    'payment_details' => $transaction->payment_details,
                ]);
            }
        }
    }
    ############## Refund Confirmed :: End ##############

    ############## Refund Steps :: Start ##############
    public function refundSuccess(
        $transaction,
        $type,
        $payment_amount,
        $transaction_id,
        $order,
        $user,
        $partial_payment = false,
        $return_to_wallet = false,
    ) {
        DB::beginTransaction();

        try {
            if ($partial_payment) {
                // edit order's payments
                $order->transactions()->create([
                    'order_id' => $transaction->order_id,
                    'invoice_id' => $transaction->invoice_id,
                    'user_id' => $transaction->user_id,
                    'payment_amount' => $transaction->payment_amount + $payment_amount,
                    'payment_method_id' => $transaction->payment_method_id,
                    'payment_status_id' => PaymentStatus::Refundable->value,
                    'service_provider_transaction_id' => $transaction->service_provider_transaction_id,
                    'payment_details' => $transaction->payment_details,
                ]);
            }

            // edit transaction
            $transaction->update([
                'payment_amount' => -1 * $payment_amount,
                'payment_method_id' => $type,
                'payment_status_id' => PaymentStatus::Refunded->value,
                'service_provider_transaction_id' => $transaction_id ?? null,
                'payment_details' => json_encode([
                    "amount_cents" => $payment_amount * 100,
                    "transaction_id" => json_decode($transaction->payment_details)->transaction_id ?? null,
                    "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                ])
            ]);

            // update user wallet
            if ($return_to_wallet) {
                $user->update([
                    'balance' => $user->balance + $payment_amount
                ]);
            }

            DB::commit();

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.This transaction has been done successfully"),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $transaction->update([
                'payment_status_id' => PaymentStatus::RefundFailed->value,
            ]);

            $order->transactions()->create([
                'order_id' => $transaction->order_id,
                'invoice_id' => $transaction->invoice_id,
                'user_id' => $transaction->user_id,
                'payment_amount' => $transaction->payment_amount,
                'payment_method_id' => $transaction->payment_method_id,
                'payment_status_id' => PaymentStatus::Refundable->value,
                'payment_details' => $transaction->payment_details,
            ]);

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Unexpected error occurred, please try again"),
                icon: 'error'
            );
        }
    }
    ############## Refund Steps :: End ##############

    ############## pop-up remove transaction confirm :: Start ##############
    public function removeTransactionConfirm($transaction_id)
    {
        $this->dispatch(
            'swalConfirm',
            text: __('admin/ordersPages.Are you sure, you want to remove this transaction?'),
            confirmButtonText: __('admin/ordersPages.Yes'),
            denyButtonText: __('admin/ordersPages.No'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'removeTransaction',
            id: $transaction_id,
        );
    }
    ############## pop-up remove transaction confirm :: End ##############

    ############## Remove Transaction :: Start ##############
    public function removeTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        DB::beginTransaction();

        try {
            $transaction->delete();

            DB::commit();

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.This transaction has been removed successfully"),
                icon: 'success'
            );
        } catch (\Throwable $th) {
            DB::rollBack();

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Unexpected error occurred, please try again"),
                icon: 'error'
            );
        }
    }


    public function addPayment()
    {
        $this->dispatch(
            'swalGetNewPaymentData',
            title: __('admin/ordersPages.Enter the payment details'),
            html: '
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
            confirmButtonText: __('admin/ordersPages.Confirm'),
            denyButtonText: __('admin/ordersPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'paymentAddingConfirmed',
        );
    }

    public function paymentAddingConfirmed(
        $payment_amount,
        $type
    ) {
        $order = $this->order;

        if ($payment_amount > 0) {
            $order->transactions()->create([
                'invoice_id' => $order->invoice->id,
                'order_id' => $order->order_id,
                'user_id' => $order->user_id,
                'payment_amount' => $payment_amount,
                'payment_method_id' => $type,
                'payment_status_id' => PaymentStatus::Pending->value,
                'payment_details' =>  json_encode([
                    "amount_cents" => $payment_amount * 100,
                    "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                ])
            ]);

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.This transaction has been added successfully"),
                icon: 'success'
            );
        } else {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Please add a valid amount and try again"),
                icon: 'error'
            );
        }
    }

    public function addRefund()
    {
        $this->dispatch(
            'swalGetNewRefundData',
            title: __('admin/ordersPages.Enter the payment details'),
            html: '
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
            confirmButtonText: __('admin/ordersPages.Confirm'),
            denyButtonText: __('admin/ordersPages.Cancel'),
            denyButtonColor: 'red',
            confirmButtonColor: 'green',
            focusDeny: true,
            icon: 'warning',
            method: 'refundAddingConfirmed',
        );
    }

    public function refundAddingConfirmed($payment_amount, $transaction_id, $type)
    {
        $order = $this->order;

        if ($payment_amount > 0 && $payment_amount <= $order->invoice->paid) {
            if (in_array($type, [PaymentMethod::Card->value, PaymentMethod::Installments->value])) {
                $orderTransactionsIds = $order->transactions()->where([
                    'payment_status_id' => PaymentStatus::Paid->value, 'payment_method_id' => $type
                ])->pluck('service_provider_transaction_id')->toArray();

                if (!in_array($transaction_id, $orderTransactionsIds)) {
                    $this->dispatch(
                        'swalDone',
                        text: __("admin/ordersPages.Wrong transaction id, please try again"),
                        icon: 'error'
                    );
                    return;
                }
            }

            $order->transactions()->create([
                'invoice_id' => $order->invoice->id,
                'order_id' => $order->order_id,
                'user_id' => $order->user_id,
                'payment_amount' => -1 * $payment_amount,
                'payment_method_id' => $type,
                'payment_status_id' => PaymentStatus::Refundable->value,
                'payment_details' =>  json_encode([
                    "amount_cents" => -1 * $payment_amount * 100,
                    "transaction_id" => $transaction_id,
                    "source_data_sub_type" => auth()->user()->f_name . " " . auth()->user()->l_name
                ])
            ]);

            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.This transaction has been added successfully"),
                icon: 'success'
            );
        } else {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.Please add a valid amount and try again"),
                icon: 'error'
            );
        }
    }

    public function createDelivery()
    {
        $order = $this->order;
        
        if (!$order->order_delivery_id) {
            $bosta_order = createBostaOrder($order);

            if ($bosta_order['status']) {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.The delivery has been created successfully"),
                    icon: 'success'
                );

                $order->update([
                    'tracking_number' => $bosta_order['data']['trackingNumber'],
                    'order_delivery_id' => $bosta_order['data']['_id'],
                ]);
            } else {
                $this->dispatch(
                    'swalDone',
                    text: __("admin/ordersPages.The delivery hasn't been created"),
                    icon: 'error'
                );
            }
        } else {
            $this->dispatch(
                'swalDone',
                text: __("admin/ordersPages.The delivery has been created before"),
                icon: 'error'
            );
        }
    }
}

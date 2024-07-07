<?php

namespace App\Services\Front\Payments\Gateways;

use App\Models\Order;
use App\Models\Transaction;
use App\Enums\PaymentMethod;
use App\Traits\Front\Payments\HasPaymobHmac;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Front\Payments\PaymentGateway;
use App\Interfaces\Front\Payments\PaymobGateway;

class CardGateway implements PaymentGateway, PaymobGateway
{
    use HasPaymobHmac;

    public function __construct(private int $paymentMethodId = PaymentMethod::Card->value)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return 'CardGateway: $' . $amount;
    }

    public function getClientSecret(Order $order, Transaction $transaction, string $orderType): string
    {
        $data = [
            "amount" => number_format(($transaction->payment_amount) * 100, 0, '', ''),
            "currency" => "EGP",
            "payment_methods" => [
                intval(env("PAYMOB_CLIENT_ID_CARD"))
            ],
            "billing_data" => [
                "first_name" => $order->user->f_name,
                "last_name" => $order->user->l_name ?? $order->user->f_name,
                "phone_number" => $order->phone1,
                "email" => $order->user->email ?? 'test@smarttoolsegypt.com',
                "apartment" => $order->id, // order_id
                "street" => $transaction->id, // transaction_id
                "building" => $orderType, // type
                "country" => "NA",
                "floor" => "NA",
                "state" => "NA",
            ],
            "customer" => [
                "first_name" => $order->user->f_name,
                "last_name" => $order->user->l_name ?? $order->user->f_name,
                "email" => $order->user->email ?? 'test@smarttoolsegypt.com',
            ]
        ];

        $intentionRequest = Http::acceptJson()->withHeaders([
            'Authorization' => 'Token ' . env('PAYMOB_SECRET_KEY')
        ])->post('https://accept.paymob.com/v1/intention/', $data)->json();

        return $intentionRequest['client_secret'] ?? "";
    }

    public function refundOrVoid(Transaction $transaction, float $amount): string
    {
        // Check if the transaction made at the same day
        if ($transaction->created_at->isToday() && $transaction->payment_amount == $amount) {
            $newTransactionId = $this->void($transaction);

            // Return the new transaction ID if void was successful
            if ($newTransactionId) {
                return $newTransactionId;
            }
        }

        // Refund the transaction if it wasn't made today or voiding failed
        return $this->refund($transaction, $amount);
    }

    public function refund(Transaction $transaction, float $amount): string
    {
        $refundRequest = Http::acceptJson()->withHeaders([
            'Authorization' => 'Token ' . env('PAYMOB_SECRET_KEY')
        ])->post('https://accept.paymob.com/api/acceptance/void_refund/refund', [
            'transaction_id' => $transaction->service_provider_transaction_id,
            'amount_cents' => number_format($amount * 100, 0, '', ''),
        ])->json();

        if (isset($refundRequest['success']) && $refundRequest['success'] && $refundRequest['parent_transaction_id'] == $transaction->id) {
            return $refundRequest['id'];
        } else {
            return "";
        }
    }

    public function void(Transaction $transaction): string
    {
        $voidRequest = Http::acceptJson()->withHeaders([
            'Authorization' => 'Token ' . env('PAYMOB_SECRET_KEY')
        ])->post('https://accept.paymob.com/api/acceptance/void_refund/void', [
            'transaction_id' => $transaction->service_provider_transaction_id,
        ])->json();

        if (isset($refundRequest['success']) && $voidRequest['success'] && $voidRequest['parent_transaction_id'] == $transaction->id) {
            return $voidRequest['id'];
        } else {
            return "";
        }
    }
}

<?php

namespace App\Services\Front\Payments\Gateways;

use App\Models\Order;
use App\Models\Transaction;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Front\Payments\PaymobGateway;
use App\Interfaces\Front\Payments\PaymentGateway;

class InstallmentGateway implements PaymentGateway, PaymobGateway
{
    public function __construct(private int $paymentMethodId = PaymentMethod::Installments->value)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return 'InstallmentGateway: $' . $amount;
    }

    public function getClientSecret(Order $order, Transaction $transaction, string $orderType): string
    {
        $data = [
            "amount" => number_format(($transaction->payment_amount) * 100, 0, '', ''),
            "currency" => "EGP",
            "payment_methods" => [
                intval(env("PAYMOB_CLIENT_ID_INSTALLMENTS"))
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

    /**
     * Redirect to Paymob
     * @param string $clientSecret
     */
    public function redirectToPaymob(string $clientSecret)
    {
        redirect()->away("https://accept.paymob.com/unifiedcheckout/?publicKey=" . env("PAYMOB_PUBLIC_KEY") . "&clientSecret={$clientSecret}");
    }
}

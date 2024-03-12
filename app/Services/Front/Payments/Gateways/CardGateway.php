<?php

namespace App\Services\Front\Payments\Gateways;

use App\Models\Order;
use App\Models\Transaction;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Front\Payments\PaymentGateway;
use App\Interfaces\Front\Payments\ThirdPartyGateway;

class CardGateway implements PaymentGateway, ThirdPartyGateway
{
    public function __construct(private int $paymentMethodId = PaymentMethod::Card->value)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return 'CardGateway: $' . $amount;
    }

    public function prepare(Order $order, Transaction $transaction): string
    {
        $data = [
            "amount" => number_format(($transaction->payment_amount) * 100, 0, '', ''),
            "currency" => "EGP",
            "payment_methods" => [
                intval(env("PAYMOB_CLIENT_ID_CARD_TEST_FLASH"))
            ],
            "billing_data" => [
                "apartment" => "NA",
                "first_name" => $order->user->f_name,
                "last_name" => $order->user->l_name ?? $order->user->f_name,
                "street" => "NA",
                "building" => "NA",
                "phone_number" => $order->phone1,
                "country" => "NA",
                "email" => $order->user->email ?? 'test@smarttoolsegypt.com',
                "floor" => "NA",
                "state" => "NA",
            ],
            "customer" => [
                "first_name" => $order->user->f_name,
                "last_name" => $order->user->l_name ?? $order->user->f_name,
                "email" => $order->user->email ?? 'test@smarttoolsegypt.com',
            ],
            "extras" => [
                "order_id" => $order->id,
            ]
        ];

        $intentionRequest = Http::acceptJson()->withHeaders([
            'Authorization' => 'Token ' . env('PAYMOB_SECRET_KEY_TEST')
        ])->post('https://accept.paymob.com/v1/intention/', $data)->json();

        return $intentionRequest['client_secret'] ?? "";
    }
}

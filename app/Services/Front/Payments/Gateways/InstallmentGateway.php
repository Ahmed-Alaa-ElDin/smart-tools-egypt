<?php

namespace App\Services\Front\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\Transaction;
use App\Interfaces\Front\Payments\PaymentGateway;
use App\Interfaces\Front\Payments\ThirdPartyGateway;

class InstallmentGateway implements PaymentGateway, ThirdPartyGateway
{
    public function __construct(private int $paymentMethodId = PaymentMethod::Installments->value)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return 'InstallmentGateway: $' . $amount;
    }

    public function prepare(Order $order, Transaction $transaction): string
    {
        return 'InstallmentGateway: Redirecting to payment page';
    }
}
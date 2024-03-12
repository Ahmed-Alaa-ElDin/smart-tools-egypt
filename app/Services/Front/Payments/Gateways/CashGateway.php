<?php

namespace App\Services\Front\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Interfaces\Front\Payments\PaymentGateway;

class CashGateway implements PaymentGateway
{
    public function __construct(private int $paymentMethodId = PaymentMethod::Cash->value)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return 'InstallmentGateway: $' . $amount;
    }
}
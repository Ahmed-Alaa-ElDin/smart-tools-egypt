<?php

namespace App\Services\Front\Payments\Gateways;

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Models\Transaction;
use App\Interfaces\Front\Payments\PaymentGateway;
use App\Interfaces\Front\Payments\PaymobGateway;

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
        return 'InstallmentGateway: Redirecting to payment page';
    }
}

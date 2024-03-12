<?php

namespace App\Services\Front\Payments;

use App\Models\Order;
use App\Models\Transaction;
use App\Interfaces\Front\Payments\PaymentGateway;
use App\Interfaces\Front\Payments\ThirdPartyGateway;

class PaymentService
{
    public function __construct(private PaymentGateway|ThirdPartyGateway $paymentGateway)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return $this->paymentGateway->processPayment($amount);
    }

    public function prepare(Order $order, Transaction $transaction): ?string
    {
        if (method_exists($this->paymentGateway, 'prepare')) {
            return $this->paymentGateway->prepare($order, $transaction);
        }
    }
}

<?php

namespace App\Services\Front\Payments;

use App\Models\Order;
use App\Models\Transaction;
use App\Interfaces\Front\Payments\PaymentGateway;
use App\Interfaces\Front\Payments\PaymobGateway;

class PaymentService
{
    public function __construct(private PaymentGateway|PaymobGateway $paymentGateway)
    {
    }

    public function processPayment(float $amount = 0)
    {
        return $this->paymentGateway->processPayment($amount);
    }

    public function getClientSecret(Order $order, Transaction $transaction): ?string
    {
        if (method_exists($this->paymentGateway, 'getClientSecret')) {
            return $this->paymentGateway->getClientSecret($order, $transaction);
        }
    }

    public function validateHmacProcessed(array $data): bool
    {
        if (method_exists($this->paymentGateway, 'validateHmacProcessed')) {
            return $this->paymentGateway->validateHmacProcessed($data);
        }

        return false;
    }

    public function validateHmacResponse(array $data): bool
    {
        if (method_exists($this->paymentGateway, 'validateHmacResponse')) {
            return $this->paymentGateway->validateHmacResponse($data);
        }

        return false;
    }
}

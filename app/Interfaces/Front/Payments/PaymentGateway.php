<?php 

namespace App\Interfaces\Front\Payments;    

interface PaymentGateway
{
    public function processPayment(float $amount = 0);
}
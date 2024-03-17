<?php 

namespace App\Interfaces\Front\Payments;

use App\Models\Order;
use App\Models\Transaction;

interface PaymobGateway
{
    /** Prepare Request (return client_secret)
     * @param Order $order
     * @param Transaction $transaction
     * @param string $orderType
     */ 
    public function getClientSecret(Order $order, Transaction $transaction, string $orderType): string;
}
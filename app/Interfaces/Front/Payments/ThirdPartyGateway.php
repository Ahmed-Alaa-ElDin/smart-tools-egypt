<?php 

namespace App\Interfaces\Front\Payments;

use App\Models\Order;
use App\Models\Transaction;

interface ThirdPartyGateway
{
    public function prepare(Order $order, Transaction $transaction): string;
}
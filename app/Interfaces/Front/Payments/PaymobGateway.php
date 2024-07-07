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

    /** Refund Or Void Request
     * @param Transaction $transaction
     * @param float $amount
     */
    public function refundOrVoid(Transaction $transaction, float $amount): string;

    /** Refund Request
     * @param Transaction $transaction
     * @param float $amount
     */
    public function refund(Transaction $transaction, float $amount): string;

    /** void Request
     * @param Transaction $transaction
     */
    public function void(Transaction $transaction): string;
}
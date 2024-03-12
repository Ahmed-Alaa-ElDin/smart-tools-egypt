<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Pending = 1;
    case Paid = 2;
    case PaymentFailed = 3;
    case RefundPending = 4;
    case Refunded = 5;
    case RefundFailed = 6;
}

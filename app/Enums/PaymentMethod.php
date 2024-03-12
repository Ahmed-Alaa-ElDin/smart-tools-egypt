<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case Cash = 1;
    case Card = 2;
    case Installments = 3;
    case VodafoneCash = 4;
    case Wallet = 10;
    case Points = 11;
}

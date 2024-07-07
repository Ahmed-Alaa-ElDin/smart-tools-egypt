<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case Pending = 1;
    case Paid = 2;
    case PaymentFailed = 3;
    case Refundable = 4;
    case Refunded = 5;
    case RefundFailed = 6;

    /**
     * Get the name of the enum case from its value.
     *
     * @param int $value
     * @return string|null
     */
    public static function getKeyFromValue(int $value): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return preg_replace('/(?<!^)([A-Z])/', ' $1', $case->name); // Convert camel case to words
            }
        }
        return null; // Return null if no matching value is found
    }
}

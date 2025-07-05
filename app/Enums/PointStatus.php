<?php

namespace App\Enums;

enum PointStatus: int
{
    case Pending = 0;
    case Approved = 1;

    /**
     * Get the name of the enum case from its value.
     *
     * @param int $value
     * @return string|null
     */
    public static function getKeyFromValue(?int $value): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return preg_replace('/(?<!^)([A-Z])/', ' $1', $case->name); // Convert camel case to words
            }
        }
        return null; // Return null if no matching value is found
    }
}

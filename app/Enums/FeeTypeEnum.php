<?php

namespace App\Enums;

enum FeeTypeEnum: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case CUSTOM = 'custom';

    // Helper method to get human-readable labels for your frontend dropdowns
    public function label(): string
    {
        return match($this) {
            self::MONTHLY => 'Monthly',
            self::YEARLY => 'Yearly',
            self::CUSTOM => 'Custom Range',
        };
    }
}

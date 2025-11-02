<?php

namespace Modules\Expenses\app\Enums;

enum CategoryEnum: string
{
    case FOOD = 'food';
    case TRANSPORT = 'transport';
    case ENTERTAINMENT = 'entertainment';
    case UTILITIES = 'utilities';
    case HEALTHCARE = 'healthcare';
    case EDUCATION = 'education';
    case SHOPPING = 'shopping';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::FOOD => 'Food & Dining',
            self::TRANSPORT => 'Transportation',
            self::ENTERTAINMENT => 'Entertainment',
            self::UTILITIES => 'Utilities',
            self::HEALTHCARE => 'Healthcare',
            self::EDUCATION => 'Education',
            self::SHOPPING => 'Shopping',
            self::OTHER => 'Other',
        };
    }
}

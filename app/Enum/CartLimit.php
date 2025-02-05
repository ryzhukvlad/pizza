<?php

namespace App\Enum;

enum CartLimit: int
{
    case PIZZA_LIMIT = 5;
    case DRINK_LIMIT = 10;

    public static function typeLimit(string $type): int
    {
        return match ($type) {
            ProductType::PIZZA->value => self::PIZZA_LIMIT->value,
            ProductType::DRINK->value => self::DRINK_LIMIT->value,
        };
    }
}

<?php

namespace App\Enum;

enum CartLimit: int
{
    case PIZZA_LIMIT = 10;
    case DRINK_LIMIT = 20;

    public static function typeLimit(string $type): int
    {
        return match ($type) {
            ProductType::PIZZA->value => self::PIZZA_LIMIT->value,
            ProductType::DRINK->value => self::DRINK_LIMIT->value,
        };
    }
}

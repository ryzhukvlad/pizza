<?php

namespace App\Rules;

use App\Enum\CartLimit;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CartProductLimit implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1];
        $ids = request()->input("products.{$index}.id");
        $quantities = request()->input("products.{$index}.quantity");

        $products = Product::findMany($ids);
        $countTypes = [];
        foreach ($products as $product) {
            $countTypes[$product->type] += $product->quantity;
        }
        foreach ($countTypes as $type => $quantity) {
            $typeLimit = CartLimit::typeLimit($type);
            if ($quantity > $typeLimit) {
                $fail("Limit $typeLimit of type $type is exceeded.");
            }
        }
    }
}

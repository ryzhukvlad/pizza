<?php

namespace App\Http\Requests;

use App\Enum\CartLimit;
use App\Rules\CartProductLimit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1|max:20',
            'products' => ['required', 'array', new CartProductLimit],
        ];
    }
}

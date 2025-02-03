<?php

namespace App\Http\Requests;

use App\Enum\ProductType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
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
            'title' => 'required|unique:products|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::enum(ProductType::class)],
            'price' => 'required|numeric|min:0',
            'image' => 'required|image',
        ];
    }
}

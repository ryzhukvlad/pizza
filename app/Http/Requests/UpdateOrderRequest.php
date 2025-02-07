<?php

namespace App\Http\Requests;

use App\Enum\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
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
            'user_id' => 'sometimes|required|exists:users,id',
            'phone' => 'sometimes|required|string',
            'email' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'time' => 'sometimes|required|date',
            'total' => 'sometimes|required|numeric',
            'status' => ['sometimes|required', Rule::enum(OrderStatus::class)],
        ];
    }
}

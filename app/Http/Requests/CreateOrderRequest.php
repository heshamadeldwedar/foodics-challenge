<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set this to true if you don't need specific authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orders' => 'required|array|min:1',
            'orders.*.product_id' => 'required|integer|exists:products,id',
            'orders.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'orders.required' => 'At least one order is required.',
            'orders.array' => 'Orders must be provided as an array.',
            'orders.min' => 'At least one order is required.',
            'orders.*.product_id.required' => 'Each order must have a product ID.',
            'orders.*.product_id.integer' => 'Product ID must be an integer.',
            'orders.*.product_id.exists' => 'The specified product does not exist.',
            'orders.*.quantity.required' => 'Each order must have a quantity.',
            'orders.*.quantity.integer' => 'Quantity must be an integer.',
            'orders.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}

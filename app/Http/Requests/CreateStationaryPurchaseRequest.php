<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateStationaryPurchaseRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.stationary_supplier_id' => ['required', 'integer', 'exists:stationary_suppliers,id'],
            'items.*.date_supplied' => ['required', 'date'],
            'items.*.stationary_id' => ['required', 'integer', 'exists:stationaries,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one item must be provided.',
            'items.*.stationary_supplier_id.required' => 'Supplier ID is required for each item.',
            'items.*.stationary_supplier_id.exists' => 'One or more suppliers do not exist.',
            'items.*.date_supplied.required' => 'Supply date is required for each item.',
            'items.*.date_supplied.date' => 'Supply date must be a valid date for each item.',
            'items.*.stationary_id.required' => 'Stationary ID is required for each item.',
            'items.*.stationary_id.exists' => 'One or more stationary items do not exist.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.integer' => 'Quantity must be a whole number for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1 for each item.',
            'items.*.price.required' => 'Price is required for each item.',
            'items.*.price.numeric' => 'Price must be a number for each item.',
            'items.*.price.min' => 'Price cannot be negative for each item.',
        ];
    }
}

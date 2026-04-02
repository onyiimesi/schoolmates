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
            'stationary_supplier_id' => ['required', 'integer', 'exists:stationary_suppliers,id'],
            'date_supplied' => ['required', 'date'],
            'stationary_id' => ['required', 'integer', 'exists:stationaries,id'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}

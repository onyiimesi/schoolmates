<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateStationarySaleRequest extends FormRequest
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
            'sales' => ['required', 'array', 'min:1'],
            'sales.*.stationary_id' => ['required', 'integer', 'exists:stationaries,id'],
            'sales.*.class_id' => ['required', 'integer', 'exists:class_models,id'],
            'sales.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'sales.*.date' => ['required', 'date'],
            'sales.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}

<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class CommunicationBookRequest extends FormRequest
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
            'period' => 'required|string',
            'term' => 'required|string',
            'session' => 'required|string',
            'class_id' => 'required|integer|exists:class_models,id',
            'staff_id' => 'required|integer|exists:staff,id',
            'student_id' => 'required|integer|exists:students,id',
            'admission_number' => 'required|string|exists:students,admission_number',
            'subject' => 'required|string',
            'message' => 'required|string',
            'attachment' => 'nullable|string'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetResultRequest extends FormRequest
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
            'student_id' => ['nullable', 'exists:students,id'],
            'period' => ['required', 'string'],
            'term' => ['required', 'string'],
            'session' => ['required', 'string'],
            'class' => ['required', 'string'],
            'result_type' => ['required', 'string'],
            'status' => ['nullable', 'in:released,withheld,not-released'],
        ];
    }

    public function messages(): array
    {
        return [
            'result_type.in' => 'result type must be either midterm, endterm, first_assessment, second_assessment or third_assessment',
            'status.in' => 'status must be either released, withheld or not-released',
        ];
    }
}

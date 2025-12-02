<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateObjectiveRequest extends FormRequest
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
            '*.period' => 'required|string',
            '*.term' => 'required|string',
            '*.session' => 'required|string',
            '*.question_type' => 'required|string',
            '*.question' => 'required|string',
            '*.question_number' => 'required|integer',
            '*.answer' => 'required|string',
            '*.subject_id' => 'required|integer',
            '*.option1' => 'required|string',
            '*.option2' => 'required|string',
            '*.option3' => 'required|string',
            '*.option4' => 'required|string',
            '*.total_question' => 'required|integer',
            '*.question_mark' => 'required|integer',
            '*.total_mark' => 'required|integer',
            '*.week' => 'required|string'
        ];
    }
}

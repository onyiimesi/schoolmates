<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class CbtAddAnswerRequest extends FormRequest
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
            '*.period' => ['required'],
            '*.term' => ['required'],
            '*.session' => ['required'],
            '*.cbt_question_id' => ['required', 'exists:cbt_questions,id'],
            '*.student_id' => ['required'],
            '*.subject_id' => ['required'],
            '*.question' => ['required', 'string'],
            '*.question_number' => ['required', 'string'],
            '*.question_type' => ['required', 'string'],
            '*.answer' => ['required', 'string'],
            '*.correct_answer' => ['required', 'string'],
            '*.submitted' => ['required', 'in:0,1']
        ];
    }
}

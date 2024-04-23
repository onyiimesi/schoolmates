<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class CbtAddQuestionRequest extends FormRequest
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
            'subject_id' => ['required'],
            'question_type' => ['required', 'string'],
            'option1' => ['required', 'string'],
            'option2' => ['required', 'string'],
            'option3' => ['required', 'string'],
            'option4' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'cbt_setting_id' => ['required', 'exists:cbt_settings,id'],
            'question_mark' => ['required', 'string']
        ];
    }
}

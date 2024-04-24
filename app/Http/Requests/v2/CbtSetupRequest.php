<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class CbtSetupRequest extends FormRequest
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
            'period' => ['required'],
            'term' => ['required'],
            'session' => ['required'],
            'subject_id' => ['required'],
            'question_type' => ['required', 'string'],
            'instruction' => ['required'],
            'duration' => ['required'],
            'mark' => ['required'],
        ];
    }
}

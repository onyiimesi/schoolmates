<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class CbtPublishRequest extends FormRequest
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
            'period' => ['required', 'string'],
            'term' => ['required', 'string'],
            'session' => ['required', 'string'],
            'question_type' => ['required', 'string'],
            'subject_id' => ['required', 'string'],
            'is_publish' => ['required', 'numeric', 'in:0,1']
        ];
    }
}

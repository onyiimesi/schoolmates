<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentPublishRequest extends FormRequest
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
            'week' => ['required', 'string'],
            'is_publish' => ['required', 'boolean', 'in:0,1']
        ];
    }
}

<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class FlipClassAssessmentRequest extends FormRequest
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
            'flip_class_id' => 'required|integer|exists:flip_classes,id',
            'question_type' => 'required|string|in:objective',
            'question' => 'required|string',
            'answer' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'option1' => 'required|string',
            'option2' => 'required|string',
            'option3' => 'required|string',
            'option4' => 'required|string'
        ];
    }
}

<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class LessonNoteRequest extends FormRequest
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
            'staff_id' => ['required', 'exists:staff,id'],
            'week' => ['required', 'numeric'],
            'subject_id' => ['required', 'numeric'],
            'class_id' => ['required', 'numeric', 'exists:class_models,id'],
            'topic' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'file' => ['required'],
            'file_name' => ['required'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
        ];
    }
}

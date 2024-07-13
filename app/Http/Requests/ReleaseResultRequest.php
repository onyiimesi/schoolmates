<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReleaseResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'period' => ['required', 'string'],
            'term' => ['required', 'string'],
            'session' => ['required', 'string'],
            'students' => ['required', 'array'],
            'students.*.student_id' => ['required', 'integer', 'exists:students,id']
        ];
    }

    public function messages()
    {
        return [
            'students.*.student_id' => "The selected student id is invalid"
        ];
    }
}

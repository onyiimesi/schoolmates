<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentMarkRequest extends FormRequest
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
            'period' => 'required',
            'term' => 'required',
            'session' => 'required',
            'student_id' => 'required',
            'subject_id' => 'required',
            'question_id' => 'required',
            'question' => 'required',
            'question_number' => 'required',
            'question_type' => 'required',
            'answer' => 'required',
            'correct_answer' => 'required',
            'submitted' => 'required',
            'teacher_mark' => 'required'
        ];
    }
}

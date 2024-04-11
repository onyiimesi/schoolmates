<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentResultRequest extends FormRequest
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
            'result*.period' => 'required',
            'result*.term' => 'required',
            'result*.session' => 'required',
            'result*.assignment_id' => 'required',
            'result*.student_id' => 'required',
            'result*.subject_id' => 'required',
            'result*.question_type' => 'required',
            'result*.mark' => 'required',
            'result*.total_mark' => 'required',
            'result*.score' => 'required',
            'result*.week' => 'required',
            'performance.period' => 'required',
            'performance.term' => 'required',
            'performance.session' => 'required',
            'performance.assignment_id' => 'required',
            'performance.student_id' => 'required',
            'performance.subject_id' => 'required',
            'performance.question_type' => 'required',
            'performance.total_mark' => 'required',
            'performance.percentage_score' => 'required',
            'performance.week' => 'required',
        ];
    }

    public function messages()
    {
        return [
            '*.mark' => 'student mark is required',
            'performance.percentage_score' => 'percentage score is required'
        ];
    }
}

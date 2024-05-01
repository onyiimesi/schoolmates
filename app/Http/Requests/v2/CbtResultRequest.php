<?php

namespace App\Http\Requests\v2;

use Illuminate\Foundation\Http\FormRequest;

class CbtResultRequest extends FormRequest
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
            'result.period' => ['required'],
            'result.term' => ['required'],
            'result.session' => ['required'],
            'result.cbt_answer_id' => ['required', 'exists:cbt_answers,id'],
            'result.student_id' => ['required', 'exists:students,id'],
            'result.subject_id' => ['required'],
            'result.question_type' => ['required'],
            'result.answer_score.*.question_number' => ['required'],
            'result.answer_score.*.question' => ['required'],
            'result.answer_score.*.question_mark' => ['required'],
            'result.answer_score.*.student_score' => ['required'],
            'result.student_total_mark' => ['required'],
            'result.test_total_mark' => ['required'],
            'result.student_duration' => ['required'],
            'result.test_duration' => ['required'],
            'performance.period' => ['required'],
            'performance.term' => ['required'],
            'performance.session' => ['required'],
            'performance.cbt_result_id' => ['required'],
            'performance.student_id' => ['required'],
            'performance.subject_id' => ['required'],
            'performance.question_type' => ['required'],
            'performance.student_total_mark' => ['required'],
            'performance.test_total_mark' => ['required'],
            'performance.student_duration' => ['required'],
            'performance.test_duration' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'result.answer_score.*.question_number' => "Question number is required",
            'result.answer_score.*.question' => "Question is required",
            'result.answer_score.*.question_mark' => "Question mark is required",
            'result.answer_score.*.student_score' => "Student score is required",
        ];
    }
}

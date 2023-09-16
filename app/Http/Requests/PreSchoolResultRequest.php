<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreSchoolResultRequest extends FormRequest
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
            'student_id' => ['required', 'string',],
            'student_fullname' => ['required', 'string',],
            'admission_number' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'term' => ['required', 'string', 'max:255'],
            'session' => ['required', 'string', 'max:255'],
            'school_opened' => ['string', 'max:255'],
            'times_present' => ['string', 'max:255'],
            'times_absent' => ['string', 'max:255'],
            'evaluation_report' => ['required', 'array'],
            'evaluation_report.*.subject' => ['required', 'string'],
            'evaluation_report.*.topic' => ['required', 'array'],
            'cognitive_development' => ['array'],
            'teacher_comment' => ['string', 'max:255'],
            'hos_comment' => ['string', 'max:255'],

        ];
    }
}

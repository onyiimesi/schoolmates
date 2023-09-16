<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentAttendanceRequest extends FormRequest
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
            // 'admission_number' => ['required', 'string',],
            // 'student_fullname' => ['required', 'string', 'max:255'],
            // 'student_id' => ['required', 'string', 'max:255'],
            // 'attendance_date' => ['required', 'string', 'max:255'],

            'data' => ['required', 'array'],
            'data.*.student_id' => ['required', 'string'],
            'data.*.student_fullname' => ['required', 'string'],
            'data.*.admission_number' => ['required', 'string'],
            'data.*.status' => ['required', 'string'],
        ];
    }
}

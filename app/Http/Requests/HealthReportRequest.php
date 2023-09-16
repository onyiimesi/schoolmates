<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthReportRequest extends FormRequest
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
            'admission_number' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255'],
            'student_fullname' => ['required', 'string', 'max:255'],
            'date_of_incident' => ['required', 'string', 'max:255'],
            'time_of_incident' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'report_details' => ['required', 'string', 'max:255'],
            'action_taken' => ['required', 'string', 'max:255'],
            'recommendation' => ['required', 'string', 'max:255']
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffAttendanceRequest extends FormRequest
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
            'staff_id' => ['required', 'string',],
            'time_in' => ['required', 'string', 'max:255'],
            'date_in' => ['required', 'string', 'max:255'],
            'time_out' => ['required', 'string', 'max:255'],
            'date_out' => ['required', 'string', 'max:255'],
        ];
    }
}

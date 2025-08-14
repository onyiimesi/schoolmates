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
            'staff_id' => ['nullable', 'string',],
            'time_in' => ['nullable', 'string', 'max:255'],
            'date_in' => ['nullable', 'string', 'max:255'],
            'time_out' => ['nullable', 'string', 'max:255'],
            'date_out' => ['nullable', 'string', 'max:255'],
        ];
    }
}

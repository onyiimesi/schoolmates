<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleLogRequest extends FormRequest
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
            'vehicle_number' => ['required', 'string',],
            'driver_name' => ['required', 'string', 'max:255'],
            'route' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
            'mechanic_condition' => ['required', 'string', 'max:255'],
            'add_info' => ['required', 'string', 'max:255'],
            'date_out' => ['required', 'string', 'max:255'],
            'time_out' => ['required', 'string', 'max:255'],
        ];
    }
}

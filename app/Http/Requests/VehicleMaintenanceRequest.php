<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleMaintenanceRequest extends FormRequest
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
            'vehicle_type' => ['required', 'string'],
            'vehicle_make' => ['required', 'string'],
            'vehicle_number' => ['required', 'string'],
            'driver_name' => ['required', 'string'],
            'detected_fault' => ['required', 'string'],
            'mechanic_name' => ['required', 'string'],
            'mechanic_phone' => ['required', 'string'],
            'cost_of_maintenance' => ['required', 'string'],
            'initial_payment' => ['required', 'string'],
        ];
    }
}

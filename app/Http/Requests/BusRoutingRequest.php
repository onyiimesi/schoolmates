<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusRoutingRequest extends FormRequest
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
            'bus_type' => ['required', 'string', 'max:255'],
            'bus_number' => ['required', 'string', 'max:255'],
            'driver_name' => ['required', 'string', 'max:255'],
            'driver_phonenumber' => ['required', 'string', 'max:255'],
            'driver_image' => ['required', 'string'],
            'conductor_name' => ['required', 'string', 'max:255'],
            'conductor_phonenumber' => ['required', 'string'],
            'conductor_image' => ['required', 'string'],
            'route' => ['required', 'string', 'max:255'],
            'ways' => ['required', 'string', 'max:255'],
            'pickup_time' => ['required', 'string', 'max:255'],
            'dropoff_time' => ['required', 'string', 'max:255'],
        ];
    }
}

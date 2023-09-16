<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunicationBookRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'urgency' => ['required', 'string'],
            'student_id' => ['required', 'string'],
            'admission_number' => ['required', 'string'],
            'message' => ['required', 'string'],
        ];
    }
}

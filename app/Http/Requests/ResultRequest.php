<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
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
            'abacus.name' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'abacus.name' => "abacus field is required"
        ];
    }
}

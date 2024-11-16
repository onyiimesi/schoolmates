<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StudentRequest extends FormRequest
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
            'surname' => ['required', 'string',],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],
            'pass_word' => ['string', 'max:255'],
            'dob' => ['required', 'string', 'max:255'],
            'session_admitted' => ['required', 'string', 'max:255'],
            'class' => ['required', 'string', 'max:255'],
            'present_class' => ['required', 'string', 'max:255'],
            'status' => ['string', 'max:20']
        ];
    }
}

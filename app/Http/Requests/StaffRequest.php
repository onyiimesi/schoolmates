<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StaffRequest extends FormRequest
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
            'designation_id' => ['required', 'string',],
            'department' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:staff'],
            'campus' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'max:255', 'unique:staff'],
            'password' => ['string', Rules\Password::defaults()],
            'pass_word' => ['string', 'max:255'],
            'status' => ['string', 'max:20']
        ];
    }
}

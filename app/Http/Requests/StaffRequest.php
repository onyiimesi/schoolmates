<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
        $user = Auth::user();
        $schoolId = $user ? $user->sch_id : null;

        return [
            'designation_id' => ['required', 'string',],
            'department' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('staff')->where(function ($query) use ($schoolId) {
                    return $query->where('sch_id', $schoolId);
                })
            ],
            'campus' => ['required', 'string', 'max:255'],
            'password' => ['string', Rules\Password::defaults()],
            'pass_word' => ['string', 'max:255'],
            'status' => ['string', 'max:20']
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeConductRequest extends FormRequest
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
            'rule' => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'apply_to' => ['required', 'string', 'max:255'],
        ];
    }
}

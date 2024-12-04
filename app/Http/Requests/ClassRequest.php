<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
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
            'campus' => ['required'],
            'class_name' => [
                'required',
                'string',
                // Rule::unique('class_models')->where(function ($query) {
                //     return $query->where('sch_id', $this->user()->sch_id)
                //                  ->where('campus', $this->campus);
                // })
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'term' => ['required', 'string',],
            'session' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'student_fullname' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'string', 'max:255'],
            'amount_paid' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'string', 'max:255'],
            'remark' => ['required', 'string', 'max:255'],
        ];
    }
}

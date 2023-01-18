<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
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
            'expense_category' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'string', 'max:255'],
            'beneficiary' => ['required', 'string', 'max:255'],
            'transaction_id' => ['max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255'],
        ];
    }
}

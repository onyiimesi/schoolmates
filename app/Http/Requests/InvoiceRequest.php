<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'sch_id' => ['string',],
            'campus' => ['string', 'max:255'],
            'admission_number' => ['required', 'string', 'max:255'],
            'student_id' => ['required'],
            'fullname' => ['required', 'string', 'max:255'],
            'class' => ['required', 'string', 'max:255'],
            'feetype' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string', 'max:255'],
            'notation' => ['string', 'max:255'],
            'discount' => ['string', 'max:255'],
            'discount_amount' => ['string', 'max:255'],
            'term' => ['required', 'string', 'max:255'],
            'session' => ['required', 'string', 'max:255'],
            'invoice_no' => ['string', 'max:100']
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentPointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'payment_purpose' => 'required|string|max:255',
            'merchant_id' => 'required|string|max:255',
            'account_id' => 'required|string|max:255',
            'customer_code' => 'required|string|max:255',
            'yandex_token' => 'nullable|string|max:255',
        ];
    }
}

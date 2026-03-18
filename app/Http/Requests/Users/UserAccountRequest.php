<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserAccountRequest extends FormRequest
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
            'id' => 'nullable',
            'details' => 'nullable|string',
            'bic' => 'nullable|string|size:9',
            'payment_purpose' => 'nullable|string',
            'counterparty_name' => 'nullable|string',
            'account_number' => 'nullable|string|size:20',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'details' => 'детали',
            'bic' => 'БИК получателя',
            'payment_purpose' => 'назначение платежа',
            'counterparty_name' => 'получатель платежа',
            'account_number' => 'счёт',
        ];
    }

    public function messages(): array
    {
        return [
            'bic.size' => 'Поле ":attribute" должно содержать ровно :size символов.',
            'account_number.size' => 'Поле ":attribute" должно содержать ровно :size символов.',
            'string' => 'Поле ":attribute" должно быть строкой.',
        ];
    }
}

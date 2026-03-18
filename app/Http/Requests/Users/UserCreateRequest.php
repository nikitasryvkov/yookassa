<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => 'required|string|min:8',
            'telegram_id' => 'nullable|int',
            'role_id' => 'required|int',
            'type_id' => 'required|int',
            'payment_point_id' => 'required|int',
            'qr_commission_rate' => 'numeric',
            'card_commission_rate' => 'numeric',
            'yookassa_commission_rate' => 'numeric',
            'yandex_commission_rate' => 'numeric',
            'agent_commission_rate' => 'numeric',
            'bic' => 'nullable|string',
            'payment_purpose' => 'nullable|string',
            'counterparty_name' =>  'nullable|string',
            'account_number' => 'nullable|string|size:20',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Пользователь с таким email уже существует.',
            'password.required' => 'Пароль обязателен.',
            'password.min' => 'Пароль должен быть не короче :min символов.',
        ];
    }
}

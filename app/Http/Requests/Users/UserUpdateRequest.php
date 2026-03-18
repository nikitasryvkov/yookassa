<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email',
            'users.*.password' => 'nullable|min:8',
            'users.*.telegram_id' => 'nullable|int',
            'users.*.role_id' => 'required|int',
            'users.*.type_id' => 'required|int',
            'users.*.payment_point_id' => 'required|int',
            'users.*.qr_commission_rate' => 'numeric',
            'users.*.card_commission_rate' => 'numeric',
            'users.*.yandex_commission_rate' => 'numeric',
            'users.*.agent_commission_rate' => 'numeric',
            'users.*.bic' => 'nullable|string',
            'users.*.payment_purpose' => 'nullable|string',
            'users.*.counterparty_name' => 'nullable|string',
            'users.*.account_number' => 'nullable|string|size:20',
        ];
    }
}

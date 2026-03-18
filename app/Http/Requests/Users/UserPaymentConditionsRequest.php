<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserPaymentConditionsRequest extends FormRequest
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
            'user_id' => 'required|numeric',
            'rate-' . $this->post('user_id') => 'nullable|numeric',
            'commission-' . $this->post('user_id') => 'nullable|numeric',
            'limit-' . $this->post('user_id') => 'nullable|numeric',
        ];
    }
}

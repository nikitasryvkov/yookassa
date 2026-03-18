<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUrlCreateRequest extends FormRequest
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
            'payment_method_id' => ['required', 'integer', 'in:1,2,3,4'],
            'sum' => ['required', 'numeric', 'min:0.01'],
            'total' => ['required', 'numeric', 'min:0.01'],
            'commission' => ['required', 'numeric', 'min:0'],
            'user_id' => ['required', 'integer'],
            'user_type_id' => ['required', 'integer'],
            'payment_point_id' => ['required', 'integer'],
        ];
    }
}

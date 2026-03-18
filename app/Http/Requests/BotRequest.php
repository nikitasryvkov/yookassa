<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotRequest extends FormRequest
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
            'name' => 'required',
            'start_text' => 'required',
            'x_telegram_bot_api_secret_token' => 'nullable',
            'token' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'start_text' => 'Текст команды старт',
            'name' => 'Название бота',
        ];
    }
}

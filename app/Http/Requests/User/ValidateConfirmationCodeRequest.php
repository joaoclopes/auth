<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ValidateConfirmationCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hash' => 'required|string|max:255',
            'code' => 'required|string|max:6',
        ];
    }

    public function messages()
    {
        return [
            'hash.required' => 'O campo hash é obrigatório.',
            'hash.max' => 'O campo hash não pode ter mais que 255 caracteres.',
            'code.required' => 'O campo code é obrigatório.',
            'code.max' => 'O campo code não pode ter mais que 6 caracteres.',
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hash' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ];
    }

    public function messages()
    {
        return [
            'hash.required' => 'O campo hash é obrigatório.',
            'hash.max' => 'O campo hash não pode ter mais que 255 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Forneça um endereço de e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais que 255 caracteres.',
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Defina como true para permitir o uso sem autenticação
    }

    public function rules()
    {
        return [
            'login' => 'required|string|email|max:255',
            'ref' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'O campo e-mail é obrigatório.',
            'login.email' => 'Forneça um endereço de e-mail válido.',
            'login.max' => 'O e-mail não pode ter mais que 255 caracteres.',
            'ref.required' => 'O campo ref é obrigatório.',
            'ref.max' => 'A ref não pode ter mais que 255 caracteres.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
        ];
    }
}

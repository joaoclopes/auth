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
            'login' => 'required|string|max:255',
            'ref' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'O campo login é obrigatório.',
            'login.max' => 'O e-mail não pode ter mais que 255 caracteres.',
            'ref.required' => 'O campo ref é obrigatório.',
            'ref.max' => 'A ref não pode ter mais que 255 caracteres.',
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SelectDuplicatedUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:pessoa,id',
        ];
    }

    /**
     * Customize the error messages.
     */
    public function messages()
    {
        return [
            'user_id.required' => 'O campo ID de usuário é obrigatório.',
            'user_id.exists' => 'O usuário informado não existe.',
        ];
    }
}

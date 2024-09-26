<?php

namespace App\Http\Requests\Enterprise;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'can_activate_user' => 'boolean',
            'personalized_messages' => 'nullable|json',
            'multi_enterprise' => 'boolean|required',
            'parent_id' => 'nullable|uuid|exists:enterprises,uuid',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome da empresa é obrigatório.',
            'name.max' => 'O nome da empresa não pode ter mais que 255 caracteres.',
            'can_activate_user.boolean' => 'O campo de ativação de usuários deve ser verdadeiro ou falso.',
            'personalized_messages.json' => 'O campo de mensagens personalizadas deve ser um JSON válido.',
            'multi_enterprise.boolean' => 'O campo de múltiplas empresas deve ser verdadeiro ou falso.',
            'multi_enterprise.required' => 'O campo de múltiplas empresas e obrigatorio.',
            'parent_id.uuid' => 'O ID da empresa matriz deve ser um UUID válido.',
            'parent_id.exists' => 'A empresa matriz especificada não foi encontrada.',
        ];
    }
}


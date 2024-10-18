<?php

namespace App\Repositories;

use App\Models\Form;

class FormRepository
{
    public function getForm()
    {
        return Form::join('cadastro_form_card as card', function($join) {
            $join->on('card.idCadastroForm', '=', 'cadastro_form.id')
                 ->where('card.removido', 0);
        })
        ->join('cadastro_form_card_campo as ccampo', function($join) {
            $join->on('ccampo.idCadastroFormCard', '=', 'card.id')
                 ->where('ccampo.removido', 0);
        })
        ->join('cadastro_campo as campo', 'campo.id', '=', 'ccampo.idCadastroCampo')
        ->where('cadastro_form.idInstituicao', 185)
        ->where('cadastro_form.tipo', 'SIS_CAD_MEMBRO')
        ->select(
            'campo.campo_form_type as type',
            'card.label as form_label', 
            'card.ordem as card_order',
            'ccampo.label as label', 
            'ccampo.obrigatorio as required', 
            'ccampo.ordem as order', 
            'campo.id as campo_id', 
            'campo.tabela as table', 
            'campo.campo as column',
            'campo.config as config',
            'ccampo.tamanho as size'
        )
        ->orderBy('order')
        ->orderBy('card_order')
        ->get();
    }
}
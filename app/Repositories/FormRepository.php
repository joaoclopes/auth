<?php

namespace App\Repositories;

use App\Enums\Deleted;
use App\Models\Form;
use Illuminate\Support\Facades\DB;

class FormRepository
{
    public function getForm()
    {
        return Form::join('cadastro_form_card as card', function($join) {
            $join->on('card.idCadastroForm', '=', 'cadastro_form.id')
                 ->where('card.removido', Deleted::NOT_DELETED->value);
        })
        ->join('cadastro_form_card_campo as ccampo', function($join) {
            $join->on('ccampo.idCadastroFormCard', '=', 'card.id')
                 ->where('ccampo.removido', Deleted::NOT_DELETED->value);
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

    public function getRegisterTypes()
    {
        return DB::table('pessoatipo')
            ->select('id as value', 'texto as label')
            ->get();
    }

    public function getGenderTypes()
    {
        return [
            [
                'value' => '1', 'label' => 'Masculino'
            ],
            [
                'value' => '2', 'label' => 'Feminino'
            ]
        ];
    }

    public function getRelationStatus($idOrganization = null)
    {
        return DB::table('estadocivil')
            ->select('id as value', 'nome as label')
            ->where('removido', Deleted::NOT_DELETED->value)
            ->where('idInstituicao', 0)
            ->whereNotIn('id', function($query) use($idOrganization) {
                $query->select('idEstadoCivil')
                    ->from('estadocivilremovido')
                    ->where('idInstituicao', [0, $idOrganization])
                    ->where('removido', Deleted::NOT_DELETED->value);
            })
            ->get();
    }

    public function getReligiousStates()
    {
        return DB::table('estado_religioso')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getBloodTypes()
    {
        $bloodType = [];
        $bloodType[] = ['id' => '1', 'label' => 'A +'];
        $bloodType[] = ['id' => '2', 'label' => 'A -'];
        $bloodType[] = ['id' => '3', 'label' => 'B +'];
        $bloodType[] = ['id' => '4', 'label' => 'B -'];
        $bloodType[] = ['id' => '5', 'label' => 'AB +'];
        $bloodType[] = ['id' => '6', 'label' => 'AB -'];
        $bloodType[] = ['id' => '7', 'label' => 'O +'];
        $bloodType[] = ['id' => '8', 'label' => 'O -'];

        return $bloodType;
    }

    public function getSchoolingTypes()
    {
        return DB::table('escolaridade')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getLanguages()
    {
        return DB::table('idioma')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getProfessions($idOrganization = 0)
    {
        return DB::table('profissao')
            ->select('id as value', 'nome as label')
            ->where('idInstituicao', $idOrganization)
            ->get();
    }

    public function getSugestedTypes()
    {
        return DB::table('tipo_sugerido')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getDocumentTypes()
    {
        return DB::table('tipodocumento')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getStates()
    {
        return DB::table('estado')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getDependentPersonTypes()
    {
        // Estava com esses id's setados no ambiente de prod
        return DB::table('pessoaparentesco')
            ->select('id as value', 'nome as label')
            ->whereIn('id', [3, 7, 8, 9, 13, 16, 17, 19])
            ->get();
    }

    public function getOperatorContacts()
    {
        return DB::table('contatooperadora')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getPersonStatuses()
    {
        return DB::table('status_pessoa')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getHowDoYouKnowTypes()
    {
        return DB::table('como_conheceu')
            ->select('id as value', 'nome as label')
            ->get();
    }
    
    public function getGifts()
    {
        return DB::table('dom')
            ->select('id as value', 'nome as label')
            ->get();
    }

    public function getCode2Statuses()
    {
        return [
            ['id' => '6', 'label' => 'QUER O APLICATIVO'],
            ['id' => '1', 'label' => 'NÃO QUER O APLICATIVO'],
            ['id' => '2', 'label' => 'FALECIDO'],
            ['id' => '4', 'label' => 'NÃO LOCALIZADO'],
            ['id' => '3', 'label' => 'AFASTADO'],
            ['id' => '5', 'label' => 'RECUSOU-SE A ATUALIZAR'],
        ];
    }
}
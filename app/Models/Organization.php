<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'instituicao';
    
    protected $fillable = [
        'cnpj',
        'nome',
        'razaoSocial',
        'fundacao',
        'idInstituicaoStatus',
        'statusFinanceiro',
        'idEndereco',
        'idEnderecoCorresp',
        'endCorresp',
        'idContato',
        'informacao',
        'idTipo',
        'logo',
        'codigoAplicativo',
        'idRepresentante',
        'idFuncionario',
        'idPrecadastro',
        'idPessoaPosVenda',
        'idInstituicaoPai',
        'idDenominacao',
        'isImportante',
        'nivelDificuldade',
        'vlrLeft',
        'vlrRight',
        'codigo',
        'idInstituicaoHierarquia',
        'created_by',
        'updated_by',
        'idInstituicaoSede',
        'apiToken',
        'mapsApiKey',
        'inscricaoEstadual',
        'sigla'
    ];

    public $timestamps = true;

    protected $casts = [
        'idInstituicaoStatus' => 'integer',
        'statusFinanceiro' => 'integer',
        'idEndereco' => 'integer',
        'idEnderecoCorresp' => 'integer',
        'idContato' => 'integer',
        'idTipo' => 'integer',
        'idRepresentante' => 'integer',
        'idFuncionario' => 'integer',
        'idPrecadastro' => 'integer',
        'idPessoaPosVenda' => 'integer',
        'idInstituicaoPai' => 'integer',
        'idDenominacao' => 'integer',
        'isImportante' => 'integer',
        'idInstituicaoHierarquia' => 'integer',
        'idInstituicaoSede' => 'integer',
    ];

}

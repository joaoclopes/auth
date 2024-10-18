<?php

namespace App\Models;

use App\Models\Scopes\EnterpriseScope;
use Illuminate\Database\Eloquent\Model;

class UserP extends Model
{
    protected $table = 'pessoa';

    protected $fillable = [
        'idInstituicao',
        'cpf',
        'nome',
        'mae',
        'pai',
        'dataCasamento',
        'nascimento',
        'idDocumento',
        'idEndereco',
        'idContato',
        'idSexo',
        'idPessoaTipo',
        'idEscolaridade',
        'idEstadoCivil',
        'idEstadoReligioso',
        'profissao',
        'titular',
        'empresa',
        'anotacoes',
        'indicadoPor',
        'receberSms',
        'tipomembro',
        'idTipoSugerido',
        'removido',
        'foto',
        'tipoSanguineo',
        'acessoApp',
        'naturalidade',
        'nacionalidade',
        'apelido',
    ];

    public $timestamps = true;

    protected $casts = [
        'idInstituicao' => 'integer',
        'idDocumento' => 'integer',
        'idEndereco' => 'integer',
        'idContato' => 'integer',
        'idSexo' => 'integer',
        'idPessoaTipo' => 'integer',
        'idEscolaridade' => 'integer',
        'idEstadoCivil' => 'integer',
        'idEstadoReligioso' => 'integer',
        'receberSms' => 'boolean',
        'tipomembro' => 'integer',
        'idTipoSugerido' => 'integer',
        'acessoApp' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new EnterpriseScope(app('organizationIds')));
    }
}

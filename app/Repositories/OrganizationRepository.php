<?php

namespace App\Repositories;

use App\Models\Organization;

class OrganizationRepository
{
    public function getByToken($token)
    {
        return Organization::where('apiToken', $token)->first();
    }

    public function getMultiConfig($organizationId)
    {
        return Organization::join('instituicaofuncionalidade as i2', 'instituicao.id', '=', 'i2.idInstituicao')
        ->where('i2.idFuncionalidade', 18)
        ->where('instituicao.id', $organizationId)
        ->select('instituicao.id as instituicao', 'i2.idFuncionalidade', 'i2.removido')
        ->first();
    }

    public function getAllTenants($mainTenantId)
    {
        return Organization::where('idInstituicaoSede', $mainTenantId)
        ->pluck('id')
        ->toArray();
    }
}
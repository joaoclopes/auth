<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EnterpriseScope implements Scope
{
    protected $idEnterprise;

    public function __construct($idEnterprise)
    {
        $this->idEnterprise = $idEnterprise;
    }

    public function apply(Builder $builder, Model $model)
    {
        $tenantsIds = $this->idEnterprise;

        $builder->whereIn('enterprise_id', $tenantsIds);
    }
}


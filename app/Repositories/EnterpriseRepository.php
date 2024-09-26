<?php

namespace App\Repositories;

use App\Models\Enterprise;

class EnterpriseRepository
{
    public function store($userData)
    {
        return Enterprise::create($userData);
    }
}
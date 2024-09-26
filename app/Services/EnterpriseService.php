<?php

namespace App\Services;

use App\Repositories\EnterpriseRepository;

class EnterpriseService
{
    public function __construct(protected EnterpriseRepository $enterpriseRepository)
    {

    }

    public function store($enterpriseData)
    {
        return $this->enterpriseRepository->store($enterpriseData);
    }
}
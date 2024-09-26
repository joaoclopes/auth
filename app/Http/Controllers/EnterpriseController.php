<?php

namespace App\Http\Controllers;

use App\Http\Requests\Enterprise\StoreRequest;
use App\Services\EnterpriseService;
use Exception;

class EnterpriseController extends Controller
{
    public function __construct(protected EnterpriseService $enterpriseService)
    {

    }

    public function store(StoreRequest $request)
    {
        try {
            if (!$request->validated()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro criar a enterprise, preencha os dados corretamente!'
                ], 400);
            }

            $this->enterpriseService->store($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Enterprise criada com sucesso!'
            ], 201);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao criar a enterprise: ' . $e->getMessage()
            ], 500);
        }
    }
}

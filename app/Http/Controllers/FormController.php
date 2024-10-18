<?php

namespace App\Http\Controllers;

use App\Services\FormService;

class FormController extends Controller
{
    public function __construct(protected FormService $formService)
    {
    }

    public function getForm()
    {
        try {
            $form = $this->formService->getForm();
            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro ao recuperar o formulario, tente novamente!'
                ],404);
            }

            return response()->json([
                'success' => true,
                'form' => array_values($form)
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao recuperar o formulario: ' . $e->getMessage()
            ], 500);
        }
    }
}
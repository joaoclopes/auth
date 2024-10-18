<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\User\ConfirmEmailRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\SelectDuplicatedUserRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\ValidateConfirmationCodeRequest;
use App\Services\AuthService;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService, protected AuthService $authService)
    {

    }

    public function store(StoreRequest $request)
    {
        try {
            if (!$request->validated()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro criar o usuario, preencha os dados corretamente!'
                ], 400);
            }

            $this->userService->store($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Usuario criado com sucesso!'
            ], 201);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao criar o usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao logar no sistema preencha os dados corretamente!'
            ], 400);
        }
        
        $login = $this->userService->login($data);
        return response()->json([
            'success' => true,
            'hash' => $login->hash,
            'email' => $login->confirmEmail,
            'message' => 'Confirme o e-mail!',
        ], 201);
    }

    public function validateConfirmationCode(ValidateConfirmationCodeRequest $request)
    {
        try {
            if (!$request->validated()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro ao confirmar o codigo, preencha os dados corretamente!'
                ], 400);
            }

            $validateCode = $this->userService->validateConfirmationCode($request->input('hash'), $request->input('code'));
            if (!$validateCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro ao confirmar o seu codigo!'
                ], 403);
            }
 
            return response()->json([
                'success' => true,
                'message' => 'Codigo confirmado com sucesso!',
                'token' => $validateCode
            ], 201);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao criar o usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function validateToken(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token nao informado.'
            ], 400);
        }

        $validateToken = $this->authService->validateToken($token);
        if (!$validateToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalido ou foi informado errado.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token valido.'
        ], 201);
    }

    public function confirmEmail(ConfirmEmailRequest $request)
    {
        try {
            $data = $request->validated();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Informe os dados corretamente.'
                ], 400);
            }

            $emailValidate = $this->userService->confirmEmail($data['hash'], $data['email']);
            if (!$emailValidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'E-mail informado nao bate com os dados do cadastro.'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'E-mail valido.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao confirmar o email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function selectDuplicatedUser(SelectDuplicatedUserRequest $request)
    {
        try {
            $data = $request->validated();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Informe os dados corretamente.'
                ], 400);
            }

            $duplicatedUser = $this->userService->selectDuplicatedUser($data['user_id']);
            if (!$duplicatedUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados informados nao batem.'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'hash' => $duplicatedUser->hash,
                'email' => $duplicatedUser->confirmEmail,
                'message' => 'Confirme o e-mail.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao confirmar o usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}

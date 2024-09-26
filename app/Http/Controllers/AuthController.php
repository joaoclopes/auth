<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService)
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
        try {
            $data = $request->validated();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocorreu um erro ao logar no sistema preencha os dados corretamente!'
                ], 400);
            }
    
            $login = $this->userService->login($data);
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro interno ao logar o usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function test(LoginRequest $request)
    {
        $data = $request->validated();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao logar no sistema preencha os dados corretamente!'
            ], 400);
        }

        $login = $this->userService->login($data);
    }
}

<?php

namespace App\Services;

use App\Repositories\OrganizationRepository;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService
{
    public function __construct(protected OrganizationRepository $organizationRepository)
    {
    }

    public function getTenantsId($token)
    {
        $organization = $this->organizationRepository->getByToken($token);
        if (!$organization->idInstituicaoSede) return $organization->id;

        $getMultiConfig = $this->organizationRepository->getMultiConfig($organization->idInstituicaoSede);
        if (!$getMultiConfig || $getMultiConfig->removido) return $organization->id;
        
        return $this->organizationRepository->getAllTenants($organization->idInstituicaoSede);
    }

    public function generateToken($user)
    {
        $payload = [
            'iss' => 'auth',
            'iat' => time(),
            'exp' => time() + 3600,
            'userId' => $user->id,
            'orgId' => $user->idInstituicao,
            'orgs' => $user->organization_ids
        ];

        return JWT::encode($payload, env('TOKEN_SECRET_KEY'), 'HS256');
    }

    public function validateToken($token)
    {
        try {
            return JWT::decode($token, new Key(env('TOKEN_SECRET_KEY'), 'HS256'));
        } catch (ExpiredException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
<?php

namespace App\Repositories;

use App\Models\Scopes\EnterpriseScope;
use App\Models\User;
use App\Models\UserP;
use App\Utils\MaskUtils;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserRepository
{
    public function store($userData)
    {
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        return User::create($userData);
    }

    public function getUserByEmail($login)
    {
        return UserP::select(
            'pessoa.id',
            'pessoa.nome as name',
            'contato.telResidencial',
            'contato.telContato',
            'contato.email',
            'pessoa.idInstituicao',
            'instituicao.nome as org_name'
        )
        ->join('instituicao', 'pessoa.idInstituicao', '=', 'instituicao.id')
        ->join('contato', 'pessoa.idContato', '=', 'contato.id')
        ->where('contato.email', $login)
        ->get();
    }

    public function getUserByPhone($login)
    {
        return UserP::select(
            'pessoa.id',
            'pessoa.nome as name',
            'pessoa.idInstituicao',
            'instituicao.nome as org_name',
            'contato.email',
            'contato.telResidencial',
            'contato.telContato'
        )
        ->join('instituicao', 'pessoa.idInstituicao', '=', 'instituicao.id')
        ->join('contato', 'pessoa.idContato', '=', 'contato.id')
        ->where(function($query) use ($login) {
            $query->where('contato.telContato', $login)
                ->orWhere('contato.telResidencial', $login);
        })
        ->get();
    }

    public function getUserByAppLogin($login)
    {
        return UserP::select(
            'pessoa.id',
            'pessoa.nome as name',
            'pessoa.idInstituicao',
            'instituicao.nome as org_name',
            'contato.email',
            'contato.telResidencial',
            'contato.telContato'
        )
        ->join('pessoa', 'portalusuario.idPessoaFuncionario', '=', 'pessoa.id')
        ->join('instituicao', 'pessoa.idInstituicao', '=', 'instituicao.id')
        ->join('contato', 'pessoa.idContato', '=', 'contato.id')
        ->where('portalusuario.usuario', $login)
        ->get();
    }

    public function getUserByCpf($login)
    {
        return UserP::join('instituicao as i', 'pessoa.idInstituicao', '=', 'i.id')
        ->join('contato as c', 'pessoa.idContato', '=', 'c.id')
        ->select('pessoa.id', 'pessoa.nome', 'pessoa.idInstituicao', 'c.telResidencial', 'c.telContato', 'c.email', 'i.nome as org_name')
        ->where('pessoa.cpf', $login)
        ->get();
    }

    public function getUserById($id)
    {
        return UserP::select(
            'pessoa.id',
            'pessoa.nome',
            'pessoa.idInstituicao',
            'c.telResidencial',
            'c.telContato',
            'c.email',
            'i.nome as org_name'
        )
        ->join('instituicao as i', 'pessoa.idInstituicao', '=', 'i.id')
        ->join('contato as c', 'pessoa.idContato', '=', 'c.id')
        ->where('pessoa.id', $id)
        ->get();
    }

    public function saveUserDataInCache($redisKey, $user)
    {
        return Redis::setex($redisKey, 900, json_encode($user));
    }

    public function getUserDataInCache($redisKey)
    {
        return Redis::get($redisKey);
    }

    public function deleteUserFromCache($redisKey)
    {
        return Redis::del($redisKey);
    }
}
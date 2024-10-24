<?php

namespace App\Services;

use App\Enums\LoginRef;
use App\Exceptions\InactiveUserException;
use App\Exceptions\MultipleUsersFoundException;
use App\Exceptions\OutdatedUserException;
use App\Exceptions\UserNotFoundException;
use App\Mail\SendCode;
use App\Repositories\OrganizationRepository;
use App\Repositories\UserRepository;
use App\Utils\MaskUtils;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(
        protected AuthService $authService,
        protected UserRepository $userRepository,
        protected OrganizationRepository $orgRepository
    )
    {

    }

    public function store($userData)
    {
        return $this->userRepository->store($userData);
    }

    public function login($userData)
    {
        $user = $this->getUserByRef($userData['login'], $userData['ref']);
        if ($user->isEmpty()) {
            throw new UserNotFoundException();
        }

        if ($user->count() > 1) {
            $user = $this->checkDuplicatedUser($user);
        }

        $user = $user->first();
        if ($user->inactive) {
            $org = $this->orgRepository->getById($user->org_id);
            if (!$org->can_active) throw new InactiveUserException();

            $this->userRepository->activeUserById($user->id);
        }

        if (!isset($user->email)) {
            throw new OutdatedUserException();
        }

        return $this->generateHashAndSaveInCache($user);
    }

    public function getUserByRef($login, $ref)
    {
        switch ($ref) {
            case (LoginRef::USER_EMAIL->value):
                return $this->userRepository->getUserByEmail($login, $ref);
            case (LoginRef::USER_CPF->value):
                return $this->userRepository->getUserByCpf($login, $ref);
            case(LoginRef::USER_LOGIN_APP->value):
                return $this->userRepository->getUserByAppLogin($login, $ref);
            case(LoginRef::USER_PHONE->value):
                return $this->userRepository->getUserByPhone($login, $ref);
            default:
                throw new UserNotFoundException();
        }
    }

    public function checkDuplicatedUser($user)
    {
        $user = $user->filter(function ($u) {
            return $u->email !== null && !$u->inactive;
        });

        if ($user->count() < 2) return $user;
        $formattedData = $this->formatDataToReturn($user);
        throw new MultipleUsersFoundException($formattedData->toArray());
    }

    public function validateConfirmationCode($hash, $confirmationCode)
    {
        $redisKey = 'user_hash:' . $hash;
        $user = json_decode($this->userRepository->getUserDataInCache($redisKey));
        if (!$user || $confirmationCode != $user->code) return false;

        $this->userRepository->deleteUserFromCache($redisKey);
        return $this->authService->generateToken($user);
    }

    public function confirmEmail($hash, $email)
    {
        $redisKey = 'user_hash:' . $hash;
        $user = json_decode($this->userRepository->getUserDataInCache($redisKey));
        if (!$user || $user->email != $email) return false;

        $user->code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Mail::to($user->email)->send(new SendCode($user->code));
        $this->userRepository->saveUserDataInCache($redisKey, $user);
        return true;
    }

    public function selectDuplicatedUser($userId)
    {
        $user = $this->userRepository->getUserById($userId);
        if (!$user) return false;

        return $this->generateHashAndSaveInCache($user->first());
    }

    private function generateHashAndSaveInCache($user)
    {
        $hash = Str::random(40);
        $user->organization_ids = app('organizationIds');
        $this->userRepository->saveUserDataInCache('user_hash:' . $hash, $user);
        return (object) [
            'hash' => $hash,
            'confirmEmail' => $this->maskOrReturnEmail($user->email)
        ];
    }

    private function formatDataToReturn($users)
    {
        return $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->nome,
                'telResidencial' => $this->maskOrReturn($user->telResidencial),
                'telContato' => $this->maskOrReturn($user->telContato),
                'email' => $this->maskOrReturnEmail($user->email),
                'org_id' => $user->org_id,
                'org_name' => $user->org_name
            ];
        });
    }

    private function maskOrReturn($value)
    {
        return $value ? MaskUtils::maskPhone($value) : $value;
    }

    private function maskOrReturnEmail($email)
    {
        return $email ? MaskUtils::maskEmail($email) : $email;
    }
}
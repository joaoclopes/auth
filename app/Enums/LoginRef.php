<?php

namespace App\Enums;

enum LoginRef: string
{
    case USER_EMAIL = 'email';
    case USER_LOGIN_APP = 'login_app';
    case USER_CPF = 'cpf';
    case USER_PHONE = 'phone';
}

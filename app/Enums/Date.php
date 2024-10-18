<?php

namespace App\Enums;

enum Date: string
{
    case BIRTH_DATE = 'nascimento';
    case DISPATCH_DATE = 'dataExpedicao';
    case MARRIAGE_DATE = 'dataCasamento';
    case VISIT_DATE = 'data_visita';
    case HIRE_DATE = 'dataContratacao';
    case DISMISS_DATE = 'dataDispensa';
}
<?php

namespace App\Enums;

enum Telephone: string
{
    case CELLPHONE = 'telCelular';
    case COMERCIAL_PHONE = 'telComercial';
    case RESIDENTIAL_PHONE = 'telResidencial';
    case CONTACT_PHONE = 'telContato';
}

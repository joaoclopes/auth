<?php

namespace App\Enums;

enum Deleted: int
{
    case DELETED = 1;
    case NOT_DELETED = 0;
}
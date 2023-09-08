<?php

namespace App\Enums;

enum SystemStatus: string
{
    case ACTIVE = 'ACTIVE';
    case UNDER_PUNISHMENT = 'UNDER_PUNISHMENT';
}

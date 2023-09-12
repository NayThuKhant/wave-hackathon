<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ORDERED = 'ORDERED';
    case OFFERED = 'OFFERED';
    case ACCEPTED = 'ACCEPTED';
    case PAID = 'PAID';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case CANCELLED_BY_SYSTEM = 'CANCELLED_BY_SYSTEM'; // Set by system in a given tries of offering employees - 5
    case CANCELLED_BY_EMPLOYER = 'CANCELLED_BY_EMPLOYER';
}

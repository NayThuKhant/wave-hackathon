<?php

namespace App\Enums;

enum PricingModel: string
{
    case PER_HOUR = 'PER_HOUR';
    case PER_ITEM = 'PER_ITEM';
}

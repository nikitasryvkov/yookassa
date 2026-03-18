<?php

namespace App\Enums;

enum KassaDocTypes: string
{
    case SALE = 'SALE';
    case RETURN = 'RETURN';
    case BUY = 'BUY';
    case BUY_RETURN = 'BUY_RETURN';
    case SALE_CORRECTION = 'SALE_CORRECTION';
    case SALE_RETURN_CORRECTION = 'SALE_RETURN_CORRECTION';
}

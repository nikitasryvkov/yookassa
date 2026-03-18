<?php

namespace App\Enums;

enum PaymentSource: int
{
    case TelegramBot = 1;
    case Url = 2;
}

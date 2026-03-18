<?php

namespace App\Enums;

enum TochkaBankPaymentTypes: int
{
    case IncomingSbpPayment = 2;
    case AcquiringInternetPayment = 3;
}

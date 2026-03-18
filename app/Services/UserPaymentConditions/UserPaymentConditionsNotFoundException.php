<?php

namespace App\Services\UserPaymentConditions;

class UserPaymentConditionsNotFoundException extends \Exception
{
    protected $message = 'User Payment Conditions not found';
}

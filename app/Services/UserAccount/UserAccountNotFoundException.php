<?php

namespace App\Services\UserAccount;

class UserAccountNotFoundException extends \Exception
{
    protected $message = 'User Account Not Found';
}

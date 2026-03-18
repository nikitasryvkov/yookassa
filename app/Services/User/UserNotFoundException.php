<?php

namespace App\Services\User;

class UserNotFoundException extends \Exception
{
    protected $message = 'User not found';
}

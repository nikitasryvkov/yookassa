<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    protected $fillable = [
        'start_text',
        'name',
        'token',
        'x_telegram_bot_api_secret_token',
    ];
}

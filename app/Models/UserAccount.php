<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $details
 * @property string $bic
 * @property string $payment_purpose
 * @property string $counterparty_name
 * @property string $account_number
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class UserAccount extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'details',
        'bic',
        'payment_purpose',
        'counterparty_name',
        'account_number',
    ];
}

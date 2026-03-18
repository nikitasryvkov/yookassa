<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property float $rate
 * @property float $commission
 * @property float $limit
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class UserPaymentCondition extends Model
{
    protected $fillable = [
        'user_id',
        'rate',
        'commission',
        'limit',
    ];
}

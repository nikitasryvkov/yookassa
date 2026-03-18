<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'type_id',
        'telegram_id',
        'payment_point_id',
        'qr_commission_rate',
        'card_commission_rate',
        'yandex_commission_rate',
        'agent_commission_rate',
        'bic',
        'payment_purpose',
        'counterparty_name',
        'account_number',
        'freelancer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function userPaymentMethods(): HasMany
    {
        return $this->hasMany(UserPaymentMethod::class);
    }

    public function paymentPoint(): HasOne
    {
        return $this->hasOne(PaymentPoint::class, 'id', 'payment_point_id');
    }

    public function accountDetails(): HasOne
    {
        return $this->hasOne(UserAccount::class);
    }

    public function paymentConditions(): HasOne
    {
        return $this->hasOne(UserPaymentCondition::class);
    }
}

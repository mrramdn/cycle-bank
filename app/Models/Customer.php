<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Customer extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'guid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_guid',
        'first_name',
        'last_name',
        'address',
        'profile_photo',
        'total_waste_sold',
        'points',
        'current_progress',
        'signature_url',
        'preferred_payment_method',
    ];

    /**
     * Get the user that owns the customer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_guid', 'guid');
    }

    /**
     * Get the customer's balance.
     */
    public function balance()
    {
        return $this->hasOne(CustomerBalance::class, 'customer_guid', 'guid');
    }

    /**
     * Get all rewards claimed by the customer.
     */
    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'customer_rewards', 'customer_guid', 'reward_guid')
                    ->withPivot('redeemed_at', 'status', 'notes', 'points_used')
                    ->withTimestamps();
    }
} 
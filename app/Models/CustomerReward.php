<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CustomerReward extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'guid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_guid',
        'reward_guid',
        'redeemed_at',
        'status',
        'notes',
        'points_used',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    /**
     * Get the customer who claimed the reward.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_guid', 'guid');
    }

    /**
     * Get the reward that was claimed.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_guid', 'guid');
    }
} 
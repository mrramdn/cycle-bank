<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Reward extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'guid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'stock',
        'image_url',
        'is_active',
        'expiry_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expiry_date' => 'datetime',
    ];

    /**
     * Get all customers who have claimed this reward.
     */
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_rewards', 'reward_guid', 'customer_guid')
                    ->withPivot('redeemed_at', 'status', 'notes', 'points_used')
                    ->withTimestamps();
    }
} 
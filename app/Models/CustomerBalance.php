<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CustomerBalance extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'guid';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'customer_guid',
        'current_balance',
        'total_earned',
        'total_withdrawn',
        'minimum_withdrawal',
        'withdrawal_eligibility',
        'last_transaction_at',
        'updated_at',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'minimum_withdrawal' => 'decimal:2',
        'withdrawal_eligibility' => 'boolean',
        'last_transaction_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the balance.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_guid', 'guid');
    }
} 
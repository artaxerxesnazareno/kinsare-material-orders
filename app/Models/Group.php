<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'allowed_balance',
        'approver_id'
    ];

    protected $casts = [
        'allowed_balance' => 'decimal:2'
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function requesters(): HasMany
    {
        return $this->hasMany(Requester::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasAvailableBalance(float $amount): bool
    {
        $usedBalance = $this->orders()
            ->where('status', 'approved')
            ->sum('total');

        return ($this->allowed_balance - $usedBalance) >= $amount;
    }
}

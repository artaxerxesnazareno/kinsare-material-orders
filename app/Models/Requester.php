<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requester extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function canCreateOrder(float $amount): bool
    {
        return $this->group->hasAvailableBalance($amount);
    }
}

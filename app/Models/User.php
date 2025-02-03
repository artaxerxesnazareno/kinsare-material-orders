<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
        'role',
        'group_id',
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

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'requester_id');
    }

    public function requester(): HasOne
    {
        return $this->hasOne(Requester::class);
    }

    public function approverGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'approver_id');
    }

    public function isRequester(): bool
    {
        return $this->profile === 'requester';
    }

    public function isApprover(): bool
    {
        return $this->profile === 'approver';
    }

    public function isAdmin(): bool
    {
        return $this->profile === 'admin';
    }
}

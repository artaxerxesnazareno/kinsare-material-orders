<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'status',
        'created_date',
        'updated_date',
        'requester_id',
        'group_id'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'created_date' => 'datetime',
        'updated_date' => 'datetime'
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Requester::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'order_material')
            ->withPivot('quantity', 'subtotal')
            ->withTimestamps();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}

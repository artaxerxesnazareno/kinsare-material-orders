<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_material')
            ->withPivot('quantity', 'subtotal')
            ->withTimestamps();
    }
}

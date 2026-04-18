<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'long_description',
        'base_price',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}

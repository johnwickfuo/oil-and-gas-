<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'client_name',
        'quote',
        'photo',
        'rating',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];
}

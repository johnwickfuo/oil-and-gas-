<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'file',
        'cover_image',
        'download_count',
        'is_active',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'is_active' => 'boolean',
    ];
}

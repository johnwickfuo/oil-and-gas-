<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'author_id',
        'category',
        'tags',
        'published_at',
        'meta_title',
        'meta_description',
        'views',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'ingredients',
        'instructions',
        'cover_image',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'meal_type',
        'published_at',
        'views',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    public const DIFFICULTIES = [
        'easy' => 'Easy',
        'medium' => 'Medium',
        'hard' => 'Hard',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'photo',
        'category',
        'week_of',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'week_of' => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const CATEGORIES = [
        'protein' => 'Protein',
        'side' => 'Side',
        'swallow' => 'Swallow',
        'dessert' => 'Dessert',
        'drink' => 'Drink',
        'small_chops' => 'Small Chops',
    ];
}

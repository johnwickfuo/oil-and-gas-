<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademyWaitlist extends Model
{
    protected $table = 'academy_waitlist';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'interest_level',
        'notes',
    ];

    public const INTEREST_LEVELS = [
        'curious' => 'Curious',
        'serious' => 'Serious',
        'ready' => 'Ready',
    ];
}

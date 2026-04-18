<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference',
        'name',
        'email',
        'phone',
        'service_id',
        'event_date',
        'event_time',
        'guests',
        'location',
        'dietary_notes',
        'special_requests',
        'estimated_total',
        'deposit_amount',
        'status',
        'payment_status',
        'admin_notes',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guests' => 'integer',
        'estimated_total' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public const STATUSES = [
        'pending_payment' => 'Pending Payment',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    public const PAYMENT_STATUSES = [
        'unpaid' => 'Unpaid',
        'partial' => 'Partial',
        'paid' => 'Paid',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->reference)) {
                $booking->reference = self::generateReference();
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $reference = sprintf(
                'BD-%s-%s',
                now()->format('Ymd'),
                strtoupper(Str::random(4)),
            );
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

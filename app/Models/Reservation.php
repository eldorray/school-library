<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'reservation_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the member who made this reservation.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the reserved book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if reservation is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->lt(Carbon::today()) && $this->status === 'pending';
    }

    /**
     * Mark as fulfilled.
     */
    public function fulfill(): void
    {
        $this->update(['status' => 'fulfilled']);
    }

    /**
     * Cancel reservation.
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Expire reservation.
     */
    public function expire(): void
    {
        $this->update(['status' => 'expired']);
    }
}

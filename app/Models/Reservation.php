<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
    public const STATUS_PENDING = 'pending';       // Menunggu ketersediaan
    public const STATUS_FULFILLED = 'fulfilled';   // Sudah diambil
    public const STATUS_CANCELLED = 'cancelled';   // Dibatalkan
    public const STATUS_EXPIRED = 'expired';       // Kedaluwarsa

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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Member yang melakukan reservasi.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Buku yang direservasi.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checkers & Actions
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah reservasi sudah kedaluwarsa.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->lt(Carbon::today()) 
            && $this->status === self::STATUS_PENDING;
    }

    /**
     * Tandai reservasi sebagai sudah diambil.
     */
    public function fulfill(): void
    {
        $this->update(['status' => self::STATUS_FULFILLED]);
    }

    /**
     * Batalkan reservasi.
     */
    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    /**
     * Tandai reservasi sebagai kedaluwarsa.
     */
    public function expire(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }
}

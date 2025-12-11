<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Config Constants
    |--------------------------------------------------------------------------
    */
    public const MAX_BORROW_LIMIT = 3;  // Maksimal buku yang bisa dipinjam

    protected $fillable = [
        'user_id',
        'member_id',
        'type',
        'class',
        'department',
        'phone',
        'address',
        'join_date',
        'is_active',
    ];

    protected $casts = [
        'join_date' => 'date',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Akun user dari member ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Semua peminjaman member ini.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Semua reservasi member ini.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Hitung jumlah peminjaman aktif.
     */
    public function activeBorrowingsCount(): int
    {
        return $this->borrowings()
            ->where('status', Borrowing::STATUS_BORROWED)
            ->count();
    }

    /**
     * Cek apakah member bisa meminjam buku lagi.
     * Member harus aktif dan belum mencapai batas pinjam.
     */
    public function canBorrow(): bool
    {
        return $this->is_active 
            && $this->activeBorrowingsCount() < self::MAX_BORROW_LIMIT;
    }
}

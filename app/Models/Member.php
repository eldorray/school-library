<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

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

    /**
     * Get the user account of this member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all borrowings of this member.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get all reservations of this member.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get active borrowings count.
     */
    public function activeBorrowingsCount(): int
    {
        return $this->borrowings()->where('status', 'borrowed')->count();
    }

    /**
     * Check if member can borrow more books.
     */
    public function canBorrow(int $maxBooks = 3): bool
    {
        return $this->is_active && $this->activeBorrowingsCount() < $maxBooks;
    }
}

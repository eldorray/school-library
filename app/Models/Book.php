<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'publisher',
        'publish_year',
        'category_id',
        'description',
        'cover_image',
        'pdf_file',
        'total_copies',
        'available_copies',
    ];

    protected $casts = [
        'publish_year' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
    ];

    /**
     * Get the category of this book.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all borrowings of this book.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get all reservations of this book.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Check if book is available for borrowing.
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    /**
     * Decrease available copies when book is borrowed.
     */
    public function decrementAvailable(): void
    {
        if ($this->available_copies > 0) {
            $this->decrement('available_copies');
        }
    }

    /**
     * Increase available copies when book is returned.
     */
    public function incrementAvailable(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }

    /**
     * Check if book has a PDF file.
     */
    public function hasPdf(): bool
    {
        return !empty($this->pdf_file);
    }

    /**
     * Check if user can read this book online.
     * Admin, Librarian, Teacher can read directly.
     * Students must have an active borrowing.
     */
    public function canRead(User $user): bool
    {
        // Admin, librarian, and teacher can read any book
        if ($user->isAdmin() || $user->isLibrarian() || $user->isTeacher()) {
            return true;
        }

        // Students must have an active borrowing
        if ($user->isStudent() && $user->member) {
            return $this->borrowings()
                ->where('member_id', $user->member->id)
                ->where('status', 'borrowed')
                ->exists();
        }

        return false;
    }
}


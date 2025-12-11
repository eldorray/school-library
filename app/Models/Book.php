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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Kategori buku ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Semua peminjaman buku ini.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Semua reservasi buku ini.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Availability Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah buku tersedia untuk dipinjam.
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    /**
     * Kurangi jumlah eksemplar tersedia ketika dipinjam.
     */
    public function decrementAvailable(): void
    {
        if ($this->available_copies > 0) {
            $this->decrement('available_copies');
        }
    }

    /**
     * Tambah jumlah eksemplar tersedia ketika dikembalikan.
     */
    public function incrementAvailable(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PDF & Read Access
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah buku memiliki file PDF.
     */
    public function hasPdf(): bool
    {
        return !empty($this->pdf_file);
    }

    /**
     * Cek apakah user boleh membaca buku ini online.
     * 
     * - Admin, Pustakawan, Guru: bisa langsung baca
     * - Siswa: harus punya peminjaman aktif
     */
    public function canRead(User $user): bool
    {
        // Admin, pustakawan, dan guru bisa baca semua buku
        if ($user->isAdmin() || $user->isLibrarian() || $user->isTeacher()) {
            return true;
        }

        // Siswa harus punya peminjaman aktif
        if ($user->isStudent() && $user->member) {
            return $this->borrowings()
                ->where('member_id', $user->member->id)
                ->where('status', Borrowing::STATUS_BORROWED)
                ->exists();
        }

        return false;
    }
}


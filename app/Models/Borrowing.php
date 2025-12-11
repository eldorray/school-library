<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    | Status peminjaman buku.
    */
    public const STATUS_PENDING = 'pending';     // Menunggu persetujuan
    public const STATUS_BORROWED = 'borrowed';   // Sedang dipinjam
    public const STATUS_RETURNED = 'returned';   // Sudah dikembalikan
    public const STATUS_REJECTED = 'rejected';   // Ditolak

    /*
    |--------------------------------------------------------------------------
    | Config Constants
    |--------------------------------------------------------------------------
    | Konfigurasi peminjaman.
    */
    public const FINE_PER_DAY = 1000;        // Denda Rp 1.000 per hari
    public const DEFAULT_LOAN_DAYS = 14;     // Lama pinjam default 14 hari

    protected $fillable = [
        'member_id',
        'book_id',
        'issued_by',
        'borrow_date',
        'due_date',
        'return_date',
        'returned_to',
        'status',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Member yang meminjam buku.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Buku yang dipinjam.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Pustakawan yang menyetujui peminjaman.
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Pustakawan yang memproses pengembalian.
     */
    public function returnedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    /**
     * Denda dari peminjaman ini (jika ada).
     */
    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Checkers
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah peminjaman masih menunggu persetujuan.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Cek apakah peminjaman sudah disetujui dan aktif.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_BORROWED;
    }

    /**
     * Cek apakah peminjaman ditolak.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Cek apakah peminjaman sudah melewati batas waktu.
     */
    public function isOverdue(): bool
    {
        if ($this->status !== self::STATUS_BORROWED) {
            return false;
        }
        return $this->due_date && $this->due_date->lt(Carbon::today());
    }

    /**
     * Hitung jumlah hari keterlambatan.
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return $this->due_date->diffInDays(Carbon::today());
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    /**
     * Setujui permintaan peminjaman.
     *
     * @param int $librarianId ID pustakawan yang menyetujui
     * @param int $loanDays Lama pinjam dalam hari (default: 14)
     */
    public function approve(int $librarianId, int $loanDays = self::DEFAULT_LOAN_DAYS): void
    {
        $this->update([
            'issued_by' => $librarianId,
            'borrow_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays($loanDays),
            'status' => self::STATUS_BORROWED,
        ]);

        $this->book->decrementAvailable();
    }

    /**
     * Tolak permintaan peminjaman.
     *
     * @param string|null $reason Alasan penolakan
     */
    public function reject(?string $reason = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Proses pengembalian buku.
     * Akan membuat denda otomatis jika terlambat.
     *
     * @param int $librarianId ID pustakawan yang memproses
     */
    public function processReturn(int $librarianId): void
    {
        $this->update([
            'return_date' => Carbon::today(),
            'returned_to' => $librarianId,
            'status' => self::STATUS_RETURNED,
        ]);

        $this->book->incrementAvailable();

        // Buat denda jika terlambat
        if ($this->due_date->lt($this->return_date)) {
            $daysOverdue = $this->due_date->diffInDays($this->return_date);

            Fine::create([
                'borrowing_id' => $this->id,
                'days_overdue' => $daysOverdue,
                'amount' => $daysOverdue * self::FINE_PER_DAY,
            ]);
        }
    }
}



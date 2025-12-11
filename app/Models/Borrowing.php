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

    /**
     * Get the member who borrowed.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the borrowed book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the librarian who issued the book.
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the librarian who processed the return.
     */
    public function returnedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    /**
     * Get the fine for this borrowing if any.
     */
    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }

    /**
     * Check if borrowing is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if borrowing is approved/active.
     */
    public function isApproved(): bool
    {
        return $this->status === 'borrowed';
    }

    /**
     * Check if borrowing was rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if borrowing is overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'borrowed') {
            return false;
        }
        return $this->due_date && $this->due_date->lt(Carbon::today());
    }

    /**
     * Get days overdue.
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return $this->due_date->diffInDays(Carbon::today());
    }

    /**
     * Approve the borrowing request.
     */
    public function approve(int $librarianId, int $loanDays = 14): void
    {
        $this->update([
            'issued_by' => $librarianId,
            'borrow_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays($loanDays),
            'status' => 'borrowed',
        ]);

        $this->book->decrementAvailable();
    }

    /**
     * Reject the borrowing request.
     */
    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Process return.
     */
    public function processReturn(int $librarianId): void
    {
        $this->update([
            'return_date' => Carbon::today(),
            'returned_to' => $librarianId,
            'status' => 'returned',
        ]);

        $this->book->incrementAvailable();

        // Create fine if overdue
        if ($this->due_date->lt($this->return_date)) {
            $daysOverdue = $this->due_date->diffInDays($this->return_date);
            $finePerDay = 1000; // Rp 1.000 per hari

            Fine::create([
                'borrowing_id' => $this->id,
                'days_overdue' => $daysOverdue,
                'amount' => $daysOverdue * $finePerDay,
            ]);
        }
    }
}


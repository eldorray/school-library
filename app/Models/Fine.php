<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'days_overdue',
        'amount',
        'is_paid',
        'paid_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_date' => 'date',
    ];

    /**
     * Get the borrowing associated with this fine.
     */
    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    /**
     * Mark fine as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            'is_paid' => true,
            'paid_date' => now(),
        ]);
    }

    /**
     * Format amount as currency.
     */
    public function formattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}

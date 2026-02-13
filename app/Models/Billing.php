<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Billing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consumer_id',
        'billing_month',
        'previous_reading',
        'current_reading',
        'consumption',
        'amount',
        'previous_balance',
        'penalty',
        'due_date',
        'status',
        'created_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'billing_month' => 'date:Y-m',
        'previous_reading' => 'decimal:2',
        'current_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * Get the consumer that owns the billing.
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class);
    }

    /**
     * Get the user who created the billing.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the payments for the billing.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the total amount paid for this billing.
     *
     * @return float
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    /**
     * Get the remaining balance for this billing.
     *
     * @return float
     */
    public function getBalanceAttribute(): float
    {
        return (float) ($this->amount - $this->total_paid);
    }

    /**
     * Check if the billing is fully paid.
     *
     * @return bool
     */
    public function getIsPaidAttribute(): bool
    {
        return $this->balance <= 0;
    }
}

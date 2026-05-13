<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'consumer_id',
        'meter_number',
        'previous_reading',
        'current_reading',
        'consumption',
        'reading_date',
        'notes',
        'recorded_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'previous_reading' => 'decimal:2',
        'current_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'reading_date' => 'date',
    ];

    /**
     * Get the consumer that owns the meter reading.
     */
    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class);
    }

    /**
     * Get the user who recorded the reading.
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get the dynamically accurate previous reading based on chronological order.
     */
    public function getAccuratePreviousReadingAttribute(): float
    {
        if (!$this->exists || !$this->reading_date) {
            return (float) $this->previous_reading;
        }

        $prev = self::where('consumer_id', $this->consumer_id)
            ->where('reading_date', '<', $this->reading_date->format('Y-m-d'))
            ->orderBy('reading_date', 'desc')
            ->first();

        return $prev ? (float) $prev->current_reading : 0.0;
    }

    /**
     * Get the dynamically accurate consumption based on chronological order.
     */
    public function getAccurateConsumptionAttribute(): float
    {
        return max(0.0, (float) $this->current_reading - $this->accurate_previous_reading);
    }
}

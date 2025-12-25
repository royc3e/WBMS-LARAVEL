<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_number',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'connection_type',
        'connection_status',
        'meter_number',
        'connection_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'connection_date' => 'date',
    ];

    /**
     * Get the consumer's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} " . ($this->middle_name ? "{$this->middle_name} " : '') . $this->last_name;
    }

    /**
     * Get the consumer's full address.
     */
    public function getFullAddressAttribute(): string
    {
        $address = $this->address_line_1;
        if ($this->address_line_2) {
            $address .= ", {$this->address_line_2}";
        }
        $address .= ", {$this->city}, {$this->state} {$this->postal_code}";
        
        return $address;
    }

    /**
     * Scope a query to only include active consumers.
     */
    public function scopeActive($query)
    {
        return $query->where('connection_status', 'active');
    }

    /**
     * Get the billings for the consumer.
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    /**
     * Get the active billings for the consumer.
     */
    public function activeBillings()
    {
        return $this->billings()
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date');
    }

    /**
     * Get the payment history for the consumer.
     */
    public function paymentHistory()
    {
        return $this->hasManyThrough(
            Payment::class,
            Billing::class,
            'consumer_id',
            'billing_id'
        )->latest('payment_date');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'appointment_id',
        'subtotal',
        'insurance_estimate',
        'patient_responsibility',
        'amount_paid',
        'status',
        'due_date',
        'line_items',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'insurance_estimate' => 'decimal:2',
            'patient_responsibility' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'due_date' => 'date',
            'line_items' => 'array',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function balanceDue(): float
    {
        return max(0, (float) $this->patient_responsibility - (float) $this->amount_paid);
    }
}

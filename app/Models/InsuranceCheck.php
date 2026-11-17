<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'insurance_policy_id',
        'requested_by',
        'status',
        'eligibility',
        'benefits_summary',
        'checked_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'benefits_summary' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(InsurancePolicy::class, 'insurance_policy_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}

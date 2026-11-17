<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsurancePolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'payer_name',
        'member_id',
        'group_number',
        'plan_type',
        'annual_maximum',
        'remaining_benefits',
        'effective_date',
        'expiration_date',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'annual_maximum' => 'decimal:2',
            'remaining_benefits' => 'decimal:2',
            'effective_date' => 'date',
            'expiration_date' => 'date',
            'is_primary' => 'boolean',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(InsuranceCheck::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'caller_name',
        'caller_phone',
        'patient_id',
        'intent',
        'status',
        'message_count',
        'transcript',
        'appointment_id',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'transcript' => 'array',
            'handled_at' => 'datetime',
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
}

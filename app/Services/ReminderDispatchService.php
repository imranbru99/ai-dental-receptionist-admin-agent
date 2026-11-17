<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Reminder;
use InvalidArgumentException;

class ReminderDispatchService
{
    public function __construct(protected ModuleManager $modules) {}

    public function queueForAppointment(Appointment $appointment): Reminder
    {
        if (! $this->modules->enabled('reminders')) {
            throw new InvalidArgumentException('Reminders module is disabled.');
        }

        $appointment->loadMissing('patient');

        return Reminder::query()->create([
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'channel' => 'sms',
            'template' => 'appointment_reminder',
            'status' => 'queued',
            'scheduled_for' => $appointment->starts_at->copy()->subDay(),
            'message' => sprintf(
                'Hi %s, reminder: %s on %s at our clinic. Reply C to confirm.',
                $appointment->patient->first_name,
                $appointment->procedure,
                $appointment->starts_at->format('M j, g:i A')
            ),
        ]);
    }

    public function markSent(Reminder $reminder): Reminder
    {
        $reminder->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return $reminder->fresh();
    }
}

<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class AppointmentBookingService
{
    public function __construct(
        protected ModuleManager $modules,
        protected ReminderDispatchService $reminders,
    ) {}

    public function book(array $payload): Appointment
    {
        if (! $this->modules->enabled('appointments')) {
            throw new InvalidArgumentException('Appointments module is disabled.');
        }

        $startsAt = Carbon::parse($payload['starts_at']);
        $endsAt = isset($payload['ends_at'])
            ? Carbon::parse($payload['ends_at'])
            : $startsAt->copy()->addMinutes((int) ($payload['duration_minutes'] ?? 45));

        $appointment = Appointment::query()->create([
            'patient_id' => $payload['patient_id'],
            'provider_id' => $payload['provider_id'] ?? null,
            'procedure' => $payload['procedure'] ?? 'Cleaning',
            'chair' => $payload['chair'] ?? $this->suggestChair($startsAt),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $payload['status'] ?? 'scheduled',
            'source' => $payload['source'] ?? 'front_desk',
            'booked_by_ai' => (bool) ($payload['booked_by_ai'] ?? false),
            'notes' => $payload['notes'] ?? null,
        ]);

        if ($this->modules->enabled('reminders')) {
            $this->reminders->queueForAppointment($appointment);
        }

        return $appointment->load(['patient', 'provider']);
    }

    public function todayBoard(): Collection
    {
        return Appointment::query()
            ->with(['patient', 'provider'])
            ->whereDate('starts_at', today())
            ->orderBy('starts_at')
            ->get();
    }

    public function suggestChair(Carbon $startsAt): string
    {
        $used = Appointment::query()
            ->whereDate('starts_at', $startsAt->toDateString())
            ->where('status', '!=', 'cancelled')
            ->pluck('chair')
            ->filter()
            ->all();

        foreach (['Chair 1', 'Chair 2', 'Chair 3', 'Chair 4', 'Hygiene'] as $chair) {
            if (! in_array($chair, $used, true)) {
                return $chair;
            }
        }

        return 'Overflow';
    }

    public function reschedule(Appointment $appointment, Carbon $startsAt, ?Carbon $endsAt = null): Appointment
    {
        $appointment->update([
            'starts_at' => $startsAt,
            'ends_at' => $endsAt ?? $startsAt->copy()->addMinutes(45),
            'status' => 'rescheduled',
        ]);

        if ($this->modules->enabled('reminders')) {
            $this->reminders->queueForAppointment($appointment->fresh());
        }

        return $appointment->fresh(['patient', 'provider']);
    }

    public function assignProvider(Appointment $appointment, User $provider): Appointment
    {
        $appointment->update(['provider_id' => $provider->id]);

        return $appointment->fresh(['patient', 'provider']);
    }

    public function forPatient(Patient $patient): Collection
    {
        return $patient->appointments()->with('provider')->orderByDesc('starts_at')->get();
    }
}

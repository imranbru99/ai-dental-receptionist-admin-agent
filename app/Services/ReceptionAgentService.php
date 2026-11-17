<?php

namespace App\Services;

use App\Models\AgentConversation;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ReceptionAgentService
{
    public function __construct(
        protected ModuleManager $modules,
        protected AppointmentBookingService $appointments,
    ) {}

    public function handleInbound(array $payload): AgentConversation
    {
        if (! $this->modules->enabled('reception_ai')) {
            throw new InvalidArgumentException('AI Receptionist module is disabled.');
        }

        $intent = $payload['intent'] ?? $this->detectIntent($payload['message'] ?? '');
        $patient = null;

        if (! empty($payload['patient_id'])) {
            $patient = Patient::query()->find($payload['patient_id']);
        } elseif (! empty($payload['caller_phone'])) {
            $patient = Patient::query()->where('phone', $payload['caller_phone'])->first();
        }

        $appointment = null;
        $assistantReply = $this->replyForIntent($intent, $patient);

        if ($intent === 'book_appointment' && $patient && $this->modules->enabled('appointments')) {
            $appointment = $this->appointments->book([
                'patient_id' => $patient->id,
                'procedure' => $payload['procedure'] ?? 'Cleaning',
                'starts_at' => $payload['starts_at'] ?? now()->addDay()->setTime(10, 0)->toDateTimeString(),
                'source' => 'ai_receptionist',
                'booked_by_ai' => true,
                'notes' => 'Booked by AI dental receptionist agent.',
            ]);
            $assistantReply = "Booked {$appointment->procedure} for {$appointment->starts_at->format('M j, g:i A')} in {$appointment->chair}.";
        }

        $transcript = [
            ['role' => 'caller', 'text' => $payload['message'] ?? 'Hello, I need help with my appointment.'],
            ['role' => 'assistant', 'text' => $assistantReply],
        ];

        return AgentConversation::query()->create([
            'channel' => $payload['channel'] ?? 'chat',
            'caller_name' => $payload['caller_name'] ?? $patient?->full_name,
            'caller_phone' => $payload['caller_phone'] ?? $patient?->phone,
            'patient_id' => $patient?->id,
            'intent' => $intent,
            'status' => 'resolved',
            'message_count' => count($transcript),
            'transcript' => $transcript,
            'appointment_id' => $appointment?->id,
            'handled_at' => now(),
        ]);
    }

    public function detectIntent(string $message): string
    {
        $message = Str::lower($message);

        return match (true) {
            Str::contains($message, ['book', 'schedule', 'appointment', 'cleaning']) => 'book_appointment',
            Str::contains($message, ['insurance', 'coverage', 'eligible']) => 'insurance_question',
            Str::contains($message, ['cancel', 'reschedule']) => 'reschedule',
            Str::contains($message, ['bill', 'invoice', 'payment']) => 'billing',
            default => 'general_faq',
        };
    }

    protected function replyForIntent(string $intent, ?Patient $patient): string
    {
        $name = $patient?->first_name ?? 'there';

        return match ($intent) {
            'book_appointment' => "Hi {$name}, I can book a chair-time for you. What day works best?",
            'insurance_question' => "I can verify eligibility before your visit. Do you have your member ID handy?",
            'reschedule' => "Sure — I can move your visit. Which appointment should I change?",
            'billing' => 'I can email your latest statement or take a card payment at the front desk.',
            default => 'Thanks for calling our dental clinic. I can help with booking, insurance, or billing.',
        };
    }

    public function metrics(): array
    {
        return [
            'handled_today' => AgentConversation::query()->whereDate('handled_at', today())->count(),
            'bookings_by_ai' => Appointment::query()->where('booked_by_ai', true)->whereDate('created_at', today())->count(),
            'open_threads' => AgentConversation::query()->where('status', 'open')->count(),
        ];
    }
}

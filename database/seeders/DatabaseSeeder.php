<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AgentConversation;
use App\Models\Appointment;
use App\Models\InsuranceCheck;
use App\Models\InsurancePolicy;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Reminder;
use App\Models\User;
use App\Services\ModuleManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncFromConfig();

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@dental-ai.test', 'role' => UserRole::SuperAdmin],
            ['name' => 'Clinic Admin', 'email' => 'admin@dental-ai.test', 'role' => UserRole::Admin],
            ['name' => 'Office Manager', 'email' => 'manager@dental-ai.test', 'role' => UserRole::Manager],
            ['name' => 'Front Desk Staff', 'email' => 'staff@dental-ai.test', 'role' => UserRole::Staff],
            ['name' => 'Patient Client', 'email' => 'client@dental-ai.test', 'role' => UserRole::Client],
            ['name' => 'Demo User', 'email' => 'demo@dental-ai.test', 'role' => UserRole::Demo],
        ];

        $created = [];
        foreach ($users as $data) {
            $created[$data['role']->value] = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => $data['role']->value,
                    'phone' => '+1-555-0100',
                    'clinic_name' => 'Skyline Family Dental',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $provider = $created[UserRole::Staff->value];

        $patients = collect([
            ['chart_number' => 'CH-1001', 'first_name' => 'Ava', 'last_name' => 'Nguyen', 'email' => 'ava.nguyen@example.com', 'phone' => '+1-555-2011', 'intake_status' => 'complete'],
            ['chart_number' => 'CH-1002', 'first_name' => 'Noah', 'last_name' => 'Patel', 'email' => 'noah.patel@example.com', 'phone' => '+1-555-2012', 'intake_status' => 'in_progress'],
            ['chart_number' => 'CH-1003', 'first_name' => 'Mia', 'last_name' => 'Lopez', 'email' => 'mia.lopez@example.com', 'phone' => '+1-555-2013', 'intake_status' => 'pending'],
            ['chart_number' => 'CH-1004', 'first_name' => 'Liam', 'last_name' => 'Brooks', 'email' => 'liam.brooks@example.com', 'phone' => '+1-555-2014', 'intake_status' => 'complete'],
            ['chart_number' => 'CH-1005', 'first_name' => 'Sofia', 'last_name' => 'Rahman', 'email' => 'sofia.rahman@example.com', 'phone' => '+1-555-2015', 'intake_status' => 'complete'],
        ])->map(function (array $data) use ($provider) {
            return Patient::query()->updateOrCreate(
                ['chart_number' => $data['chart_number']],
                array_merge($data, [
                    'date_of_birth' => now()->subYears(rand(18, 65))->toDateString(),
                    'gender' => collect(['female', 'male', 'other'])->random(),
                    'preferred_language' => 'en',
                    'medical_alerts' => ['latex sensitivity'],
                    'assigned_staff_id' => $provider->id,
                    'notes' => 'Seeded demo patient for Skyline Family Dental.',
                ])
            );
        });

        foreach ($patients as $index => $patient) {
            $starts = today()->setTime(9 + $index, 0);
            $appointment = Appointment::query()->updateOrCreate(
                [
                    'patient_id' => $patient->id,
                    'starts_at' => $starts,
                ],
                [
                    'provider_id' => $provider->id,
                    'procedure' => collect(['Cleaning', 'Exam', 'Whitening consult', 'Filling', 'New patient exam'])->get($index),
                    'chair' => 'Chair '.(($index % 4) + 1),
                    'ends_at' => $starts->copy()->addMinutes(45),
                    'status' => collect(['scheduled', 'confirmed', 'checked_in'])->get($index % 3),
                    'source' => $index === 0 ? 'ai_receptionist' : 'front_desk',
                    'booked_by_ai' => $index === 0,
                    'notes' => 'Demo appointment',
                ]
            );

            $policy = InsurancePolicy::query()->updateOrCreate(
                [
                    'patient_id' => $patient->id,
                    'member_id' => 'MEM-'.$patient->chart_number,
                ],
                [
                    'payer_name' => collect(['Delta Dental', 'Cigna DPPO', 'MetLife', 'Aetna'])->get($index % 4),
                    'group_number' => 'GRP-'.(1000 + $index),
                    'plan_type' => 'PPO',
                    'annual_maximum' => 1500,
                    'remaining_benefits' => 900 - ($index * 50),
                    'effective_date' => now()->startOfYear(),
                    'expiration_date' => now()->endOfYear(),
                    'is_primary' => true,
                ]
            );

            InsuranceCheck::query()->updateOrCreate(
                [
                    'patient_id' => $patient->id,
                    'insurance_policy_id' => $policy->id,
                ],
                [
                    'requested_by' => $created[UserRole::Demo->value]->id,
                    'status' => $index === 2 ? 'pending' : 'completed',
                    'eligibility' => $index === 2 ? null : 'active',
                    'benefits_summary' => [
                        'preventive_coverage' => '100%',
                        'basic_coverage' => '80%',
                        'major_coverage' => '50%',
                    ],
                    'checked_at' => $index === 2 ? null : now()->subHours(2),
                ]
            );

            Reminder::query()->updateOrCreate(
                [
                    'patient_id' => $patient->id,
                    'appointment_id' => $appointment->id,
                    'template' => 'appointment_reminder',
                ],
                [
                    'channel' => 'sms',
                    'status' => 'queued',
                    'scheduled_for' => $starts->copy()->subDay(),
                    'message' => "Reminder for {$patient->first_name}: {$appointment->procedure} tomorrow.",
                ]
            );

            Invoice::query()->updateOrCreate(
                [
                    'invoice_number' => 'INV-DEMO-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                ],
                [
                    'patient_id' => $patient->id,
                    'appointment_id' => $appointment->id,
                    'subtotal' => 220,
                    'insurance_estimate' => 132,
                    'patient_responsibility' => 88,
                    'amount_paid' => $index % 2 === 0 ? 88 : 0,
                    'status' => $index % 2 === 0 ? 'paid' : 'estimate',
                    'due_date' => now()->addDays(14),
                    'line_items' => [
                        ['description' => $appointment->procedure, 'amount' => 220],
                    ],
                ]
            );
        }

        AgentConversation::query()->updateOrCreate(
            ['caller_phone' => '+1-555-2011', 'intent' => 'book_appointment'],
            [
                'channel' => 'chat',
                'caller_name' => 'Ava Nguyen',
                'patient_id' => $patients[0]->id,
                'status' => 'resolved',
                'message_count' => 4,
                'transcript' => [
                    ['role' => 'caller', 'text' => 'Hi, I need a cleaning next week.'],
                    ['role' => 'assistant', 'text' => 'I can help with that. Tuesday at 10 AM is open in Chair 1.'],
                    ['role' => 'caller', 'text' => 'Perfect, book it.'],
                    ['role' => 'assistant', 'text' => 'Booked Cleaning for tomorrow morning.'],
                ],
                'appointment_id' => Appointment::query()->where('booked_by_ai', true)->value('id'),
                'handled_at' => now()->subMinutes(35),
            ]
        );
    }
}

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toggleable clinic modules
    |--------------------------------------------------------------------------
    | Each key maps to a feature domain. Clinics enable only what they need.
    */
    'appointments' => [
        'enabled' => (bool) env('MODULE_APPOINTMENTS', true),
        'name' => 'Appointments',
        'description' => 'Chair-time scheduling, chair assignment, and waitlist automation.',
    ],
    'patients' => [
        'enabled' => (bool) env('MODULE_PATIENTS', true),
        'name' => 'Patients & Intake',
        'description' => 'Patient records, digital intake forms, medical history, and consents.',
    ],
    'insurance' => [
        'enabled' => (bool) env('MODULE_INSURANCE', true),
        'name' => 'Insurance Checks',
        'description' => 'Eligibility verification, benefits summary, and claim readiness.',
    ],
    'reception_ai' => [
        'enabled' => (bool) env('MODULE_RECEPTION_AI', true),
        'name' => 'AI Receptionist',
        'description' => 'Voice/chat front-desk agent for booking, FAQs, and after-hours coverage.',
    ],
    'billing' => [
        'enabled' => (bool) env('MODULE_BILLING', true),
        'name' => 'Billing',
        'description' => 'Treatment estimates, invoices, co-pay collection, and patient statements.',
    ],
    'reminders' => [
        'enabled' => (bool) env('MODULE_REMINDERS', true),
        'name' => 'Reminders',
        'description' => 'SMS/email appointment reminders, recall campaigns, and no-show recovery.',
    ],
    'attendance' => [
        'enabled' => (bool) env('MODULE_ATTENDANCE', false),
        'name' => 'Staff Attendance',
        'description' => 'Optional biometric/time-clock integration for front-desk and clinical staff.',
    ],
];

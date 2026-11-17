<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BillingService
{
    public function __construct(protected ModuleManager $modules) {}

    public function createEstimate(Patient $patient, array $lineItems, ?Appointment $appointment = null): Invoice
    {
        if (! $this->modules->enabled('billing')) {
            throw new InvalidArgumentException('Billing module is disabled.');
        }

        $subtotal = collect($lineItems)->sum(fn (array $item) => (float) ($item['amount'] ?? 0));
        $insuranceEstimate = round($subtotal * 0.6, 2);
        $patientResponsibility = round($subtotal - $insuranceEstimate, 2);

        return Invoice::query()->create([
            'invoice_number' => 'INV-'.now()->format('ymd').'-'.Str::upper(Str::random(4)),
            'patient_id' => $patient->id,
            'appointment_id' => $appointment?->id,
            'subtotal' => $subtotal,
            'insurance_estimate' => $insuranceEstimate,
            'patient_responsibility' => $patientResponsibility,
            'amount_paid' => 0,
            'status' => 'estimate',
            'due_date' => now()->addDays(14)->toDateString(),
            'line_items' => $lineItems,
        ]);
    }

    public function recordPayment(Invoice $invoice, float $amount): Invoice
    {
        $paid = min((float) $invoice->patient_responsibility, (float) $invoice->amount_paid + $amount);
        $invoice->update([
            'amount_paid' => $paid,
            'status' => $paid >= (float) $invoice->patient_responsibility ? 'paid' : 'partial',
        ]);

        return $invoice->fresh();
    }
}

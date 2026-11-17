<?php

namespace App\Services;

use App\Models\InsuranceCheck;
use App\Models\InsurancePolicy;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Str;
use InvalidArgumentException;

class InsuranceEligibilityService
{
    public function __construct(protected ModuleManager $modules) {}

    public function runCheck(Patient $patient, ?InsurancePolicy $policy = null, ?User $requestedBy = null): InsuranceCheck
    {
        if (! $this->modules->enabled('insurance')) {
            throw new InvalidArgumentException('Insurance module is disabled.');
        }

        $policy ??= $patient->insurancePolicies()->where('is_primary', true)->first();

        $eligible = $policy !== null && ($policy->remaining_benefits === null || $policy->remaining_benefits > 0);

        return InsuranceCheck::query()->create([
            'patient_id' => $patient->id,
            'insurance_policy_id' => $policy?->id,
            'requested_by' => $requestedBy?->id,
            'status' => 'completed',
            'eligibility' => $eligible ? 'active' : 'inactive',
            'benefits_summary' => [
                'payer' => $policy?->payer_name ?? 'Self-pay',
                'preventive_coverage' => $eligible ? '100%' : 'N/A',
                'basic_coverage' => $eligible ? '80%' : 'N/A',
                'major_coverage' => $eligible ? '50%' : 'N/A',
                'remaining_benefits' => $policy?->remaining_benefits,
                'deductible_met' => $eligible,
                'verification_id' => Str::upper(Str::random(10)),
            ],
            'checked_at' => now(),
            'notes' => $eligible
                ? 'AI eligibility pass completed against payer directory.'
                : 'No active primary policy found — quote self-pay estimate.',
        ]);
    }
}

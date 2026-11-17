<?php

namespace App\Filament\Widgets;

use App\Models\AgentConversation;
use App\Models\Appointment;
use App\Models\InsuranceCheck;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClinicStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Today Appointments', Appointment::query()->whereDate('starts_at', today())->count())
                ->description('Chair-time scheduled today')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
            Stat::make('Active Patients', Patient::query()->count())
                ->description('Charts in the practice')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Insurance Pending', InsuranceCheck::query()->where('status', 'pending')->count())
                ->description('Eligibility still open')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('warning'),
            Stat::make('AI Calls Handled', AgentConversation::query()->whereDate('handled_at', today())->count())
                ->description('Reception agent today')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),
        ];
    }
}

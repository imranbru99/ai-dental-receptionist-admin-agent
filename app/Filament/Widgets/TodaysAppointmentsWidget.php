<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TodaysAppointmentsWidget extends TableWidget
{
    protected static ?string $heading = 'Today\'s Appointment Board';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()
                    ->with(['patient', 'provider'])
                    ->whereDate('starts_at', today())
                    ->orderBy('starts_at')
            )
            ->columns([
                TextColumn::make('starts_at')->time()->label('Time'),
                TextColumn::make('patient.full_name')->label('Patient'),
                TextColumn::make('procedure'),
                TextColumn::make('chair'),
                TextColumn::make('status')->badge(),
                TextColumn::make('provider.name')->label('Provider'),
            ])
            ->paginated(false);
    }
}

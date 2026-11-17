<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Resources\Appointments\Pages\ManageAppointments;
use App\Models\Appointment;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Clinic Ops';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('appointments');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')->relationship('patient', 'first_name')->searchable()->required(),
            Select::make('provider_id')->relationship('provider', 'name')->searchable(),
            TextInput::make('procedure')->required()->default('Cleaning'),
            TextInput::make('chair')->maxLength(50),
            DateTimePicker::make('starts_at')->required(),
            DateTimePicker::make('ends_at')->required(),
            Select::make('status')->options([
                'scheduled' => 'Scheduled',
                'confirmed' => 'Confirmed',
                'checked_in' => 'Checked in',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
                'rescheduled' => 'Rescheduled',
                'no_show' => 'No-show',
            ])->required()->default('scheduled'),
            Select::make('source')->options([
                'front_desk' => 'Front desk',
                'online' => 'Online',
                'ai_receptionist' => 'AI receptionist',
                'phone' => 'Phone',
            ])->default('front_desk'),
            Toggle::make('booked_by_ai'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('starts_at')->dateTime()->sortable(),
                TextColumn::make('patient.full_name')->label('Patient')->searchable(['patients.first_name', 'patients.last_name']),
                TextColumn::make('procedure')->searchable(),
                TextColumn::make('chair'),
                TextColumn::make('status')->badge(),
                TextColumn::make('provider.name')->label('Provider'),
                IconColumn::make('booked_by_ai')->boolean()->label('AI'),
            ])
            ->defaultSort('starts_at')
            ->filters([
                SelectFilter::make('status')->options([
                    'scheduled' => 'Scheduled',
                    'confirmed' => 'Confirmed',
                    'checked_in' => 'Checked in',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'no_show' => 'No-show',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAppointments::route('/'),
        ];
    }
}

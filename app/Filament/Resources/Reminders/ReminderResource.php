<?php

namespace App\Filament\Resources\Reminders;

use App\Filament\Resources\Reminders\Pages\ManageReminders;
use App\Models\Reminder;
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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    protected static string|UnitEnum|null $navigationGroup = 'Clinic Ops';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('reminders');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')->relationship('patient', 'first_name')->searchable()->required(),
            Select::make('appointment_id')->relationship('appointment', 'id')->searchable(),
            Select::make('channel')->options([
                'sms' => 'SMS',
                'email' => 'Email',
                'voice' => 'Voice',
            ])->required()->default('sms'),
            TextInput::make('template')->default('appointment_reminder'),
            Select::make('status')->options([
                'queued' => 'Queued',
                'sent' => 'Sent',
                'failed' => 'Failed',
                'cancelled' => 'Cancelled',
            ])->default('queued'),
            DateTimePicker::make('scheduled_for'),
            DateTimePicker::make('sent_at'),
            Textarea::make('message')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scheduled_for')->dateTime()->sortable(),
                TextColumn::make('patient.full_name')->label('Patient'),
                TextColumn::make('channel')->badge(),
                TextColumn::make('template'),
                TextColumn::make('status')->badge(),
                TextColumn::make('sent_at')->dateTime(),
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
            'index' => ManageReminders::route('/'),
        ];
    }
}

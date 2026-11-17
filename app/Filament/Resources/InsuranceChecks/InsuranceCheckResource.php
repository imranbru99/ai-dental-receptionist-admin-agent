<?php

namespace App\Filament\Resources\InsuranceChecks;

use App\Filament\Resources\InsuranceChecks\Pages\ManageInsuranceChecks;
use App\Models\InsuranceCheck;
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

class InsuranceCheckResource extends Resource
{
    protected static ?string $model = InsuranceCheck::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Revenue Cycle';

    protected static ?string $navigationLabel = 'Insurance Checks';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('insurance');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')->relationship('patient', 'first_name')->searchable()->required(),
            Select::make('insurance_policy_id')->relationship('policy', 'payer_name')->searchable(),
            Select::make('status')->options([
                'pending' => 'Pending',
                'completed' => 'Completed',
                'failed' => 'Failed',
            ])->required()->default('pending'),
            TextInput::make('eligibility')->maxLength(50),
            DateTimePicker::make('checked_at'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')->label('Patient')->searchable(['patients.first_name', 'patients.last_name']),
                TextColumn::make('policy.payer_name')->label('Payer'),
                TextColumn::make('status')->badge(),
                TextColumn::make('eligibility')->badge(),
                TextColumn::make('checked_at')->dateTime()->sortable(),
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
            'index' => ManageInsuranceChecks::route('/'),
        ];
    }
}

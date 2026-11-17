<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Resources\Patients\Pages\ManagePatients;
use App\Models\Patient;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Clinic Ops';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('patients');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('chart_number')->required()->unique(ignoreRecord: true)->maxLength(50),
                TextInput::make('first_name')->required()->maxLength(100),
                TextInput::make('last_name')->required()->maxLength(100),
                TextInput::make('email')->email()->maxLength(150),
                TextInput::make('phone')->tel()->maxLength(40),
                DatePicker::make('date_of_birth'),
                Select::make('gender')->options([
                    'female' => 'Female',
                    'male' => 'Male',
                    'other' => 'Other',
                    'unspecified' => 'Prefer not to say',
                ]),
                Select::make('intake_status')->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In progress',
                    'complete' => 'Complete',
                ])->required()->default('pending'),
                TextInput::make('preferred_language')->default('en')->maxLength(10),
                Textarea::make('address')->columnSpanFull(),
                Textarea::make('notes')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('chart_number')->searchable()->sortable(),
                TextColumn::make('full_name')->label('Patient')->searchable(['first_name', 'last_name']),
                TextColumn::make('phone')->toggleable(),
                TextColumn::make('email')->toggleable(),
                TextColumn::make('intake_status')->badge(),
                TextColumn::make('assignedStaff.name')->label('Coordinator')->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('intake_status')->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In progress',
                    'complete' => 'Complete',
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
            'index' => ManagePatients::route('/'),
        ];
    }
}

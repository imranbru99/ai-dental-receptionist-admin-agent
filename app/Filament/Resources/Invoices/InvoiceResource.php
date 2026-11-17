<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\ManageInvoices;
use App\Models\Invoice;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Revenue Cycle';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('billing');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('invoice_number')->required()->unique(ignoreRecord: true),
            Select::make('patient_id')->relationship('patient', 'first_name')->searchable()->required(),
            Select::make('appointment_id')->relationship('appointment', 'id')->searchable(),
            TextInput::make('subtotal')->numeric()->required(),
            TextInput::make('insurance_estimate')->numeric()->default(0),
            TextInput::make('patient_responsibility')->numeric()->required(),
            TextInput::make('amount_paid')->numeric()->default(0),
            Select::make('status')->options([
                'draft' => 'Draft',
                'estimate' => 'Estimate',
                'sent' => 'Sent',
                'partial' => 'Partial',
                'paid' => 'Paid',
                'void' => 'Void',
            ])->required()->default('draft'),
            DatePicker::make('due_date'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->searchable(),
                TextColumn::make('patient.full_name')->label('Patient'),
                TextColumn::make('patient_responsibility')->money('USD'),
                TextColumn::make('amount_paid')->money('USD'),
                TextColumn::make('status')->badge(),
                TextColumn::make('due_date')->date(),
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
            'index' => ManageInvoices::route('/'),
        ];
    }
}

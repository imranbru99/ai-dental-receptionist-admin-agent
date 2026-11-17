<?php

namespace App\Filament\Resources\AgentConversations;

use App\Filament\Resources\AgentConversations\Pages\ManageAgentConversations;
use App\Models\AgentConversation;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AgentConversationResource extends Resource
{
    protected static ?string $model = AgentConversation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'AI Front Desk';

    protected static ?string $navigationLabel = 'AI Receptionist';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('reception_ai');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('channel')->options([
                'chat' => 'Chat',
                'voice' => 'Voice',
                'sms' => 'SMS',
            ])->required()->default('chat'),
            TextInput::make('caller_name'),
            TextInput::make('caller_phone'),
            Select::make('patient_id')->relationship('patient', 'first_name')->searchable(),
            TextInput::make('intent'),
            Select::make('status')->options([
                'open' => 'Open',
                'resolved' => 'Resolved',
                'escalated' => 'Escalated',
            ])->default('open'),
            TextInput::make('message_count')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('handled_at')->dateTime()->sortable()->label('Handled'),
                TextColumn::make('caller_name')->searchable(),
                TextColumn::make('channel')->badge(),
                TextColumn::make('intent')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('message_count')->label('Msgs'),
                TextColumn::make('patient.full_name')->label('Patient'),
            ])
            ->defaultSort('handled_at', 'desc')
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
            'index' => ManageAgentConversations::route('/'),
        ];
    }
}

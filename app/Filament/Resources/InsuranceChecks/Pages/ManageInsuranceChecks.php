<?php

namespace App\Filament\Resources\InsuranceChecks\Pages;

use App\Filament\Resources\InsuranceChecks\InsuranceCheckResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInsuranceChecks extends ManageRecords
{
    protected static string $resource = InsuranceCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

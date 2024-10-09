<?php

namespace App\Filament\Resources\ConsultationsResource\Pages;

use App\Filament\Resources\ConsultationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewConsultations extends ViewRecord
{
    protected static string $resource = ConsultationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

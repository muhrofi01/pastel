<?php

namespace App\Filament\Resources\PenilaianInfografisResource\Pages;

use App\Filament\Resources\PenilaianInfografisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenilaianInfografis extends EditRecord
{
    protected static string $resource = PenilaianInfografisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

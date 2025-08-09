<?php

namespace App\Filament\Resources\NilaiInfografisResource\Pages;

use App\Filament\Resources\NilaiInfografisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNilaiInfografis extends EditRecord
{
    protected static string $resource = NilaiInfografisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

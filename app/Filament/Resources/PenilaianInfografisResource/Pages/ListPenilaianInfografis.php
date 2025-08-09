<?php

namespace App\Filament\Resources\PenilaianInfografisResource\Pages;

use App\Filament\Resources\PenilaianInfografisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianInfografis extends ListRecords
{
    protected static string $resource = PenilaianInfografisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

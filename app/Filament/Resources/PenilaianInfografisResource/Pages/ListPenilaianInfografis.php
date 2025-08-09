<?php

namespace App\Filament\Resources\PenilaianInfografisResource\Pages;

use App\Filament\Resources\PenilaianInfografisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenilaianInfografis extends ListRecords
{
    protected static string $resource = PenilaianInfografisResource::class;

    protected static ?string $title = "Penilaian IGA BAKAR";

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Mulai Penilaian'),
        ];
    }
}
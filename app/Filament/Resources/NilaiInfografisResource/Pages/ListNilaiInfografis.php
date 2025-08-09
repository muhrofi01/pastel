<?php

namespace App\Filament\Resources\NilaiInfografisResource\Pages;

use App\Filament\Resources\NilaiInfografisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilaiInfografis extends ListRecords
{
    protected static string $resource = NilaiInfografisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

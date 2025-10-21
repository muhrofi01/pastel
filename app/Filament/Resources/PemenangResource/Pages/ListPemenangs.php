<?php

namespace App\Filament\Resources\PemenangResource\Pages;

use App\Filament\Resources\PemenangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemenangs extends ListRecords
{
    protected static string $resource = PemenangResource::class;

    protected static ?string $title = "Pemenang";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
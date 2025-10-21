<?php

namespace App\Filament\Resources\PemenangResource\Pages;

use App\Filament\Resources\PemenangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemenang extends EditRecord
{
    protected static string $resource = PemenangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\PenilaianInfografisResource\Pages;

use App\Filament\Resources\PenilaianInfografisResource;
use App\Models\PenilaianInfografis;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePenilaianInfografis extends CreateRecord
{
    protected static string $resource = PenilaianInfografisResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Nilai Infografis')
                ->modalDescription('Apakah Anda yakin untuk memilih 3 infografis tersebut?')
                ->modalSubmitActionLabel('Ya, Simpan')
                ->action(function () {
                    $this->create(); // jalankan proses simpan bawaan
                }),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach ($data['infografis_id'] as $infografisId) {
            PenilaianInfografis::create([
                'user_id' => $data['user_id'],
                'infografis_id' => $infografisId,
                'periode_penilaian_id' => $data['periode_penilaian_id']
            ]);
        }

        // Kosongkan supaya Filament nggak nyari kolom 'infografis' di tabel utama
        $data['infografis_id'] = null;

        return $data;
    }

    protected function afterCreate(): void
    {
        PenilaianInfografis::where('user_id', $this->record->user_id)
                    ->where('infografis_id', null)
                    ->delete();
        
    }
}
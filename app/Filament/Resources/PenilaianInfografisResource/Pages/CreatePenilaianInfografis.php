<?php

namespace App\Filament\Resources\PenilaianInfografisResource\Pages;

use App\Filament\Resources\PenilaianInfografisResource;
use App\Models\NilaiInfografis;
use App\Models\PenilaianInfografis;
use App\Models\PeriodePenilaian;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePenilaianInfografis extends CreateRecord
{
    protected static string $resource = PenilaianInfografisResource::class;
    
    public function getTitle(): string
    {
        $periode = PeriodePenilaian::whereDate('mulai', '<=', now())->whereDate('berakhir', '>=', now())->first()?->triwulan ?? 'Tanpa Periode';

        return "Penilaian IGA BAKAR - {$periode}";
    }
    
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
            $nilaiInfografis = NilaiInfografis::where('user_id', $data['user_id'])
                                            ->where('infografis_id', $infografisId)
                                            ->first();
            if (!$nilaiInfografis) {
                NilaiInfografis::create([
                    'user_id' => $data['user_id'],
                    'infografis_id' => $infografisId,
                    'periode_penilaian_id' => $data['periode_penilaian_id'],
                    'nilai' => 1
                ]);
            } else {
                NilaiInfografis::where('user_id', $data['user_id'])
                            ->where('infografis_id', $infografisId)
                            ->where('periode_penilaian_id', $data['periode_penilaian_id'])
                            ->increment('nilai');
            }
            
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
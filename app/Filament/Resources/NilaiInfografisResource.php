<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiInfografisResource\Pages;
use App\Filament\Resources\NilaiInfografisResource\RelationManagers;
use App\Models\NilaiInfografis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NilaiInfografisResource extends Resource
{
    protected static ?string $model = NilaiInfografis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Manajemen Penilaian';

    protected static ?string $navigationLabel = 'Nilai';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {   
        return parent::getEloquentQuery()->orderBy('nilai', 'DESC');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode_penilaian.triwulan')->label('Triwulan'),
                TextColumn::make('periode_penilaian.tahun')->label('Tahun'),
                TextColumn::make('infografis.user.name')->label('Nama'),
                TextColumn::make('infografis.user.jenjang')->label('Jenjang'),
                TextColumn::make('infografis.judul')->label('Judul')->wrap(),
                TextColumn::make('nilai')
            ])
            ->filters([
                SelectFilter::make('triwulan')
                    ->label('Triwulan')
                    ->options([
                        'Triwulan I' => 'Triwulan I',
                        'Triwulan II' => 'Triwulan II',
                        'Triwulan III' => 'Triwulan III',
                        'Triwulan IV' => 'Triwulan IV',
                    ])
                    ->query(function ($query, $state) {
                        if ($state) {
                            $query->whereHas('periode_penilaian', function ($q) use ($state) {
                                $q->where('triwulan', $state);
                            });
                        } else {
                            $query->whereHas('periode_penilaian', function ($q) use ($state) {
                                $q->whereIn('triwulan', ['Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV']);
                            });
                        }
                    }),
                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options([
                        '2025' => '2025',
                        '2026' => '2026',
                    ])
                    ->query(function ($query, $state) {
                        if ($state) {
                            $query->whereHas('periode_penilaian', function ($q) use ($state) {
                                $q->where('tahun', $state);
                            });
                        } else {
                            $query->whereHas('periode_penilaian', function ($q) use ($state) {
                                $q->whereIn('tahun', ['2025', '2026']);
                            });
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilaiInfografis::route('/'),
            'create' => Pages\CreateNilaiInfografis::route('/create'),
            'edit' => Pages\EditNilaiInfografis::route('/{record}/edit'),
        ];
    }
}
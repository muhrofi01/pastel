<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianInfografisResource\Pages;
use App\Filament\Resources\PenilaianInfografisResource\RelationManagers;
use App\Models\Infografis;
use App\Models\PenilaianInfografis;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class PenilaianInfografisResource extends Resource
{
    protected static ?string $model = PenilaianInfografis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(fn () => auth()->user()?->id),
                Select::make('periode_penilaian_id')
                    ->relationship('periode_penilaian', 'judul')
                    ->preload()
                    ->columnSpan([
                        'default' => '1',
                        'sm' => '2'
                    ])
                    ->required(),
                CheckboxList::make('infografis_id')
                    ->options(
                        Infografis::whereNot('user_id', auth()->user()?->id)->get()->mapWithKeys(function ($infografis) {
                            return [
                                $infografis->id => "<img src='".Storage::disk('public')->url($infografis->gambar_1). "' title='{$infografis->judul}'>"
                            ];
                        })->toArray()
                    )
                    ->columns(3)
                    ->columnSpan([
                        'default' => '1',
                        'sm' => '3'
                    ])
                    ->gridDirection('row')
                    ->allowHtml()
                    ->rules([
                        'array',
                        'size:3', // Harus tepat 3 pilihan
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pegawai')
                    ->wrap(),
                TextColumn::make('periode_penilaian.judul')
                    ->label('Periode')
                    ->wrap(),
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
            'index' => Pages\ListPenilaianInfografis::route('/'),
            'create' => Pages\CreatePenilaianInfografis::route('/create'),
            'edit' => Pages\EditPenilaianInfografis::route('/{record}/edit'),
        ];
    }
}
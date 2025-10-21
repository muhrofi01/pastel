<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemenangResource\Pages;
use App\Models\Pemenang;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PemenangResource extends Resource
{
    protected static ?string $model = Pemenang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pemenang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('periode_penilaian_id')->relationship(
                        name: 'periode_penilaian',
                        titleAttribute: 'triwulan',
                        modifyQueryUsing: fn ($query) => $query->orderBy('tahun', 'desc')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->triwulan} - Tahun {$record->tahun}")
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pegawai'),
                TextColumn::make('periode_penilaian.triwulan')
                    ->label('Triwulan')  ,
                TextColumn::make('periode_penilaian.tahun')
                    ->label('Tahun'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPemenangs::route('/'),
            'create' => Pages\CreatePemenang::route('/create'),
            'edit' => Pages\EditPemenang::route('/{record}/edit'),
        ];
    }
}
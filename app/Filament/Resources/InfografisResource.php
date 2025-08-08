<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfografisResource\Pages;
use App\Filament\Resources\InfografisResource\RelationManagers;
use App\Models\Infografis;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class InfografisResource extends Resource
{
    protected static ?string $model = Infografis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('judul')
                    ->required(),
                Select::make('triwulan')
                        ->options([
                            'Triwulan I' => 'Triwulan I',
                            'Triwulan II' => 'Triwulan II',
                            'Triwulan III' => 'Triwulan III',
                            'Triwulan IV' => 'Triwulan IV',
                        ])
                        ->required(),
                Select::make('tahun')
                        ->options([
                            '2025' => '2025',
                        ])
                        ->required(),
                FileUpload::make('gambar_1')
                    ->directory('image/infografis')
                    ->acceptedFileTypes([
                        'image/jpeg', // .jpg, .jpeg
                        'image/png',  // .png
                    ]),
                FileUpload::make('gambar_2')
                    ->directory('image/infografis')
                    ->acceptedFileTypes([
                        'image/jpeg', // .jpg, .jpeg
                        'image/png',  // .png
                    ]),
                FileUpload::make('gambar_3')
                    ->directory('image/infografis')
                    ->acceptedFileTypes([
                        'image/jpeg', // .jpg, .jpeg
                        'image/png',  // .png
                    ]),
                FileUpload::make('gambar_4')
                    ->directory('image/infografis')
                    ->acceptedFileTypes([
                        'image/jpeg', // .jpg, .jpeg
                        'image/png',  // .png
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul'),
                TextColumn::make('triwulan'),
                TextColumn::make('tahun'),
                TextColumn::make('gambar_1')
                    ->label('Gambar 1')
                    ->formatStateUsing(fn ($state) => "Gambar 1")
                    ->url(fn ($record) => Storage::disk('public')->url($record->gambar_1), true)
                    ->openUrlInNewTab(),
                TextColumn::make('gambar_2')
                    ->label('Gambar 2')
                    ->formatStateUsing(fn ($state) => "Gambar 2")
                    ->url(fn ($record) => Storage::disk('public')->url($record->gambar_2), true)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInfografis::route('/'),
            'create' => Pages\CreateInfografis::route('/create'),
            'edit' => Pages\EditInfografis::route('/{record}/edit'),
        ];
    }
}
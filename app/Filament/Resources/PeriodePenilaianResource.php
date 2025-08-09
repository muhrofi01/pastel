<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodePenilaianResource\Pages;
use App\Filament\Resources\PeriodePenilaianResource\RelationManagers;
use App\Models\PeriodePenilaian;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodePenilaianResource extends Resource
{
    protected static ?string $model = PeriodePenilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Manajemen Penilaian';

    protected static ?string $navigationLabel = 'Periode';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'default' => 1,
                    'sm' => 3,
                ])
                    ->schema([
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
                        Select::make('jenis')
                                ->options([
                                    'IGA BAKAR' => 'IGA BAKAR',
                                    'PEMPEG' => 'PEMPEG',
                                ])
                                ->required(),
                    ]),
                
                DateTimePicker::make('mulai')
                    ->required(),
                DateTimePicker::make('berakhir')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('triwulan'),
                TextColumn::make('tahun'),
                TextColumn::make('jenis'),
                TextColumn::make('mulai'),
                TextColumn::make('berakhir'),
            ])
            ->actions([
                EditAction::make(),
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
            'index' => Pages\ListPeriodePenilaians::route('/'),
            'create' => Pages\CreatePeriodePenilaian::route('/create'),
            'edit' => Pages\EditPeriodePenilaian::route('/{record}/edit'),
        ];
    }
}
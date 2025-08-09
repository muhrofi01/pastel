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
    
    protected static ?string $navigationGroup = 'Manajemen Penilaian';

    protected static ?string $navigationLabel = 'Infografis';

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
                FileUpload::make('video')
                    ->directory('infografis')
                    ->acceptedFileTypes([
                        'video/mp4',   // .mp4
                        'video/avi',   // .avi
                        'video/mpeg',  // .mpeg
                        'video/quicktime', // .mov
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')->wrap(),
                TextColumn::make('triwulan'),
                TextColumn::make('tahun'),
                TextColumn::make('video')
                    ->label('Infografis')
                    ->formatStateUsing(fn ($state) => "Link")
                    ->url(fn ($record) => Storage::disk('public')->url($record->video), true)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
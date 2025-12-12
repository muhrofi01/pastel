<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pengguna';

    public function getTitle(): string
    {
        return 'Pengguna';
    }

    protected static function getCreateFormSchema(): array
    {
        return [
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->required(),
                Select::make('jenjang')
                    ->options([
                        'Ahli' => 'Ahli',
                        'Terampil/Pelaksana' => 'Terampil/Pelaksana',
                        'Fungsional Umum' => 'Fungsional Umum',
                    ])
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
            ];
    }

    protected static function getEditFormSchema(): array
    {
        return [
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->required(),
                Select::make('jenjang')
                    ->options([
                        'Ahli' => 'Ahli',
                        'Terampil/Pelaksana' => 'Terampil/Pelaksana',
                        'Fungsional Umum' => 'Fungsional Umum',
                    ])
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
            ];
    }

    protected static function getFormSchema(): array
    {
        return [
            Grid::make('Create')
                ->schema(static::getCreateFormSchema())
                ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),

            Grid::make('Edit')
                ->schema(static::getEditFormSchema())
                ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
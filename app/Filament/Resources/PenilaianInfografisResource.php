<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianInfografisResource\Pages;
use App\Filament\Resources\PenilaianInfografisResource\RelationManagers;
use App\Models\Infografis;
use App\Models\PenilaianInfografis;
use App\Models\PeriodePenilaian;
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
                    ->relationship(
                        'periode_penilaian', 
                        'triwulan',  
                        // fn ($query) => $query
                        //     ->whereDate('mulai', '<=', now())
                        //     ->whereDate('berakhir', '>=', now())
                    )
                    ->columnSpan([
                        'default' => '1',
                        'sm' => '2'
                    ])
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('infografis_id', []))
                    ->required(),
                CheckboxList::make('infografis_id')
                    ->label('Pilih 3 Infografis Terbaik')
                    ->options(function (callable $get) {
                        $periodeId = $get('periode_penilaian_id'); // ambil value select sebelumnya
                        
                        if (!$periodeId) {
                            return []; // belum pilih periode → kosong
                        }
                        
                        return Infografis::where('triwulan', PeriodePenilaian::find("9f970268-48ae-4608-b0bc-6b8c3eee8f90")->triwulan)
                            ->where('user_id', '!=', auth()->id())
                            ->get()
                            ->mapWithKeys(function ($infografis) {
                                return [
                                    (string) $infografis->id => "<img src='" . Storage::disk('public')->url($infografis->gambar_1) . "' title='{$infografis->judul}'>",
                                ];
                            })
                            ->toArray();
                        }   
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
                    ->required()
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
                TextColumn::make('periode_penilaian.jenis')
                    ->label('Jenis'),
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
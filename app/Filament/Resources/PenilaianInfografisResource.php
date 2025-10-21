<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianInfografisResource\Pages;
use App\Filament\Resources\PenilaianInfografisResource\RelationManagers;
use App\Models\Infografis;
use App\Models\Pemenang;
use App\Models\PenilaianInfografis;
use App\Models\PeriodePenilaian;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class PenilaianInfografisResource extends Resource
{
    protected static ?string $model = PenilaianInfografis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Penilaian';

    protected static ?string $navigationLabel = 'IGA BAKAR';

    public static function canCreate(): bool
    {
        $periodePenilaian = PeriodePenilaian::whereDate('mulai', '<=', now())->whereDate('berakhir', '>=', now())->orderBy('triwulan', 'desc')->orderBy('tahun', 'desc')->first();
        
        if(!$periodePenilaian) {
            return false;
        }

        $isSudahMenilai = PenilaianInfografis::where('user_id', Auth::user()->id)->where('periode_penilaian_id', $periodePenilaian->id)->first();
        
        return !$isSudahMenilai ? true : false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Infografis Jenjang Ahli')
                        ->schema([
                            Hidden::make('user_id')
                                ->default(fn () => Auth::user()->id),
                            Hidden::make('periode_penilaian_id')
                                ->default(fn () => PeriodePenilaian::whereDate('mulai', '<=', now())
                                                    ->whereDate('berakhir', '>=', now())->orderBy('triwulan', 'desc')->orderBy('tahun', 'desc')->first()->id),
                            CheckboxList::make('infografis_ahli_id')
                                ->label('Pilih 3 Infografis Terbaik Jenjang Ahli')
                                ->options(function () {
                                    $pemenangUserIds = Pemenang::whereHas('user', function ($query) {
                                                            $query->where('jenjang', 'Ahli');
                                                        })
                                                        ->whereHas('periode_penilaian', function ($query) {
                                                            $query->where('tahun', now()->year);
                                                        })
                                                        ->pluck('user_id');
                                    return Infografis::whereHas('user', function ($query) use ($pemenangUserIds) {
                                        $query->where('jenjang', 'Ahli')->whereNotIn('user_id', $pemenangUserIds);
                                    })
                                    ->where('triwulan', PeriodePenilaian::find(PeriodePenilaian::whereDate('mulai', '<=', now())->whereDate('berakhir', '>=', now())->orderBy('triwulan', 'desc')->orderBy('tahun', 'desc')->first()->id)->triwulan)
                                    ->where('user_id', '!=', Auth::user()->id)
                                    ->get()
                                    ->mapWithKeys(function ($infografis) {
                                        return [
                                            (string) $infografis->id => $infografis->video,
                                        ];
                                    })
                                    ->toArray();
                                })
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
                                ->required(),
                            
                        ]),
                    Step::make('Infografis Jenjang Terampil/Pelaksana')
                        ->schema([
                            CheckboxList::make('infografis_pelaksana_id')
                                ->label('Pilih 3 Infografis Terbaik Jenjang Terampil/Pelaksana')
                                ->extraAttributes([
                                    'style' => 'font-size: 32px;', // ukuran label input
                                ])
                                ->options(function () {
                                    $pemenangUserIds = Pemenang::whereHas('user', function ($query) {
                                                            $query->where('jenjang', 'Terampil/Pelaksana');
                                                        })
                                                        ->whereHas('periode_penilaian', function ($query) {
                                                            $query->where('tahun', now()->year);
                                                        })
                                                        ->pluck('user_id');
                                    return Infografis::whereHas('user', function ($query) use ($pemenangUserIds) {
                                        $query->where('jenjang', 'Terampil/Pelaksana')->whereNotIn('user_id', $pemenangUserIds);
                                    })
                                    ->where('triwulan', PeriodePenilaian::find(PeriodePenilaian::whereDate('mulai', '<=', now())->whereDate('berakhir', '>=', now())->orderBy('triwulan', 'desc')->orderBy('tahun', 'desc')->first()->id)->triwulan)
                                    ->where('user_id', '!=', Auth::user()->id)
                                    ->get()
                                    ->mapWithKeys(function ($infografis) {
                                        return [
                                            (string) $infografis->id => $infografis->video,
                                        ];
                                    })
                                    ->toArray();
                                })
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
                        ]),
                ])
                ->columnSpan([
                    'default' => 1,
                    'sm' => 2
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {   
        $periodePenilaian = PeriodePenilaian::whereDate('mulai', '<=', now())->whereDate('berakhir', '>=', now())->orderBy('triwulan', 'desc')->orderBy('tahun', 'desc')->first();
        
        if(!$periodePenilaian) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        if (Auth::user()->hasRole('Super Admin') || Auth::user()->name == "Prasaja Arifiyanto") {
            return parent::getEloquentQuery()
                    ->selectRaw('MIN(id) as id, user_id, periode_penilaian_id')
                    ->with(['user', 'periode_penilaian'])
                    ->groupBy('user_id', 'periode_penilaian_id');
        } else {
            return parent::getEloquentQuery()
                    ->selectRaw('MIN(id) as id, user_id, periode_penilaian_id')
                    ->with(['user', 'periode_penilaian'])
                    ->where('user_id', Auth::id())
                    ->groupBy('user_id', 'periode_penilaian_id');
        }
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->width('50px') // lebar kolom
                    ->alignCenter(),
                TextColumn::make('user.name')
                    ->label('Pegawai'),
                TextColumn::make('periode_penilaian.triwulan')
                    ->label('Triwulan')  ,
                TextColumn::make('periode_penilaian.tahun')
                    ->label('Tahun'),
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
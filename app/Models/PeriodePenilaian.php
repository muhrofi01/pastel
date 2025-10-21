<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePenilaian extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    public function penilaian_infografis()
    {
        return $this->hasMany(PenilaianInfografis::class, 'penilaian_infografis_id');
    }

    public function pemenang()
    {
        return $this->hasMany(Pemenang::class, 'pemenang_id');
    }
}
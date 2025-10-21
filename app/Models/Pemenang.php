<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemenang extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $table = 'pemenang_infografis';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function periode_penilaian()
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_penilaian_id');
    }
}
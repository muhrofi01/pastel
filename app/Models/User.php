<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    // use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        $canAccess = auth()->check() && str_ends_with(auth()->user()->email, '@bps.go.id');
        Log::info('canAccessPanel called', ['canAccess' => $canAccess, 'email' => auth()->user()->email ?? 'guest']);
        return $canAccess;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jenjang'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function infografis()
    {
        return $this->hasMany(Infografis::class, 'infografis_id');
    }

    public function penilaian_infografis()
    {
        return $this->hasMany(PenilaianInfografis::class, 'penilaian_infografis_id');
    }

    public function pemenang()
    {
        return $this->hasMany(Pemenang::class, 'pemenang_id');
    }
}
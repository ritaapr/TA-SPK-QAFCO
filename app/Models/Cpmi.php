<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penilaian;
use App\Models\PenilaianHistori;

class Cpmi extends Model
{
    use HasFactory;

    protected $table = 'cpmi';
    
    protected $fillable = [
        'nama_cpmi',
        'nik',
        'alamat',
        'no_hp',
        'tanggal_daftar',
        'foto_profil',
    ];

     public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function penilaianHistori()
{
    return $this->hasMany(PenilaianHistori::class);
}

public function isDirekomendasikan()
{
    return $this->penilaianHistori()->exists();
}

}

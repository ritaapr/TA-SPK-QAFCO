<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penilaian;

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
        'ktp',
    ];

     public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}

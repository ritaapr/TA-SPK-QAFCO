<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'jenis',
        'skala',
        'bobot',
    ];

    public function subkriterias()
    {
        return $this->hasMany(Subkriteria::class, 'kriteria_id');
    }
}

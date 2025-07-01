<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianHistori extends Model
{
    use HasFactory;

    protected $table = 'penilaian_histori';

    protected $fillable = [
        'cpmi_id',
        'kriteria_id',
        'subkriteria_id',
        'nilai',
        'nilai_saw',
    ];

    // Relasi ke model CPMI
    public function cpmi()
    {
        return $this->belongsTo(Cpmi::class);
    }

    // Relasi ke model Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    // Relasi ke model Subkriteria
    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }
}
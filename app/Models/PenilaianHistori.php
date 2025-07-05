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
        'user_id',
        'kriteria_id',
        'subkriteria_id',
        'nilai',
    ];

    // Relasi ke CPMI
    public function cpmi()
    {
        return $this->belongsTo(Cpmi::class, 'cpmi_id');
    }

    // Relasi ke Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    // Relasi ke Subkriteria
    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class, 'subkriteria_id');
    }

    // Relasi ke User (opsional karena bisa null)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

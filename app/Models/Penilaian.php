<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'cpmi_id',
        'kriteria_id',
        'subkriteria_id',
     // jika kamu menyimpan nilai juga
        'user_id',       // tambahkan ini!
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cpmi()
    {
        return $this->belongsTo(Cpmi::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class);
    }
}

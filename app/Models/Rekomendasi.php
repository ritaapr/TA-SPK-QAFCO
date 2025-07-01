<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi'; // Nama tabel
    protected $fillable = ['cpmi_id']; // Field yang boleh diisi

    public function cpmi()
    {
        return $this->belongsTo(Cpmi::class);
    }

    public function histori()
{
    return $this->hasMany(\App\Models\PenilaianHistori::class, 'cpmi_id', 'cpmi_id');
}

}

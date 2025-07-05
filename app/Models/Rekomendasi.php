<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RekomendasiHistori;


class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi'; // Nama tabel
    protected $fillable = ['cpmi_id']; // Field yang boleh diisi

    public function cpmi()
    {
        return $this->belongsTo(Cpmi::class);
    }

    

}

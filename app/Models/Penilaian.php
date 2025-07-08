<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'cpmi_id',
        'kriteria_id',
        'subkriteria_id',
        'user_id',
        'nilai_input'
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

   public static function simpanSementara($cpmi_id, $kriteria_id, $nilai_input = null, $subkriteria_id = null)
{
    if ($nilai_input !== null) {
        // Kasus input angka, cari subkriteria otomatis
        $sub = Subkriteria::where('kriteria_id', $kriteria_id)
            ->where('batas_bawah', '<=', $nilai_input)
            ->where('batas_atas', '>=', $nilai_input)
            ->first();

        if (!$sub) {
            throw new \Exception("Nilai $nilai_input tidak cocok dengan rentang subkriteria pada kriteria ID $kriteria_id.");
        }

        self::updateOrCreate(
            ['cpmi_id' => $cpmi_id, 'kriteria_id' => $kriteria_id],
            ['subkriteria_id' => $sub->id, 'nilai_input' => $nilai_input, 'user_id' => Auth::id()]
        );
    } elseif ($subkriteria_id !== null) {
        // Kasus input via dropdown subkriteria_id langsung
        $sub = Subkriteria::find($subkriteria_id);
        if (!$sub) {
            throw new \Exception("Subkriteria ID $subkriteria_id tidak ditemukan.");
        }

        self::updateOrCreate(
            ['cpmi_id' => $cpmi_id, 'kriteria_id' => $kriteria_id],
            ['subkriteria_id' => $subkriteria_id, 'nilai_input' => $sub->nilai, 'user_id' => Auth::id()]
        );
    }
}


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // Ambil semua data dengan status bobot
    public static function getAllWithStatus()
    {
        $data = self::all();
        $bobotTerkunci = $data->first() ? $data->first()->status_bobot : false;

        return compact('data', 'bobotTerkunci');
    }

    // Tambahkan kriteria baru
    public static function tambahKriteria($data)
    {
        return self::create($data);
    }

    // Update kriteria tertentu
    public function perbaruiKriteria($data)
    {
        $this->update($data);
    }

    // Hitung dan simpan bobot berdasarkan skala
    public static function prosesHitungBobot($data)
    {
        $totalSkala = array_sum(array_column($data, 'skala'));

        if ($totalSkala == 0) {
            return false;
        }

        foreach ($data as $item) {
            $bobot = $item['skala'] / $totalSkala;

            self::where('id', $item['id'])->update([
                'skala' => $item['skala'],
                'bobot' => $bobot,
                'status_bobot' => true
            ]);
        }

        return true;
    }

    // Reset semua bobot dan skala
    public static function resetSemuaBobot()
    {
        self::query()->update([
            'skala' => null,
            'bobot' => null,
            'status_bobot' => false
        ]);
    }
}

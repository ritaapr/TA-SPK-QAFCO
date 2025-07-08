<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    use HasFactory;

    protected $table = 'subkriteria';

    protected $fillable = [
        'kriteria_id',
        'nama_subkriteria',
        'batas_bawah',
        'batas_atas',
        'nilai',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public static function simpanBaru($request)
    {
        return self::create([
            'kriteria_id' => $request->kriteria_id,
            'nama_subkriteria' => $request->nama_subkriteria,
            'batas_bawah' => $request->batas_bawah,
            'batas_atas' => $request->batas_atas,
            'nilai' => $request->nilai,
        ]);
    }

    public static function updateData($id, $request)
    {
        $sub = self::findOrFail($id);

        $sub->update([
            'kriteria_id' => $request->kriteria_id,
            'nama_subkriteria' => $request->nama_subkriteria,
            'batas_bawah' => $request->batas_bawah,
            'batas_atas' => $request->batas_atas,
            'nilai' => $request->nilai,
        ]);

        return $sub;
    }
}

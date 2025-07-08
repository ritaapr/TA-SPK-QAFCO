<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Penilaian;
use App\Models\PenilaianHistori;

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
        'foto_profil',
    ];

    // Ambil daftar tahun unik dari data CPMI
    public static function getTahunList()
    {
        return self::selectRaw('YEAR(tanggal_daftar) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
    }

    // Ambil data CPMI berdasarkan filter tahun
    public static function getFiltered($filterTahun)
    {
        return self::when($filterTahun, function ($query) use ($filterTahun) {
                return $query->whereYear('tanggal_daftar', $filterTahun);
            })
            ->orderBy('tanggal_daftar', 'desc')
            ->paginate(10);
    }

    // Simpan data baru CPMI
    public static function simpanData($request)
    {
        $fotoPath = $request->file('foto_profil')->store('foto_profil', 'public');

        return self::create([
            'nama_cpmi' => $request->nama_cpmi,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'tanggal_daftar' => $request->tanggal_daftar,
            'foto_profil' => $fotoPath,
        ]);
    }

    // Update data CPMI
    public function perbaruiData($request)
    {
        if ($request->hasFile('foto_profil')) {
            if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
                Storage::disk('public')->delete($this->foto_profil);
            }
            $this->foto_profil = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $this->nama_cpmi = $request->nama_cpmi;
        $this->nik = $request->nik;
        $this->alamat = $request->alamat;
        $this->no_hp = $request->no_hp;
        $this->tanggal_daftar = $request->tanggal_daftar;

        $this->save();
    }

    // Hapus data beserta foto
    public function hapusData()
    {
        if ($this->foto_profil && Storage::disk('public')->exists($this->foto_profil)) {
            Storage::disk('public')->delete($this->foto_profil);
        }

        $this->delete();
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function penilaianHistori()
    {
        return $this->hasMany(PenilaianHistori::class, 'cpmi_id');
    }

    public function isDirekomendasikan()
    {
        return $this->penilaianHistori()->exists();
    }
}

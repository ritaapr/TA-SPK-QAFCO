<?php

namespace App\Http\Controllers\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Models\Cpmi;
use App\Helpers\SAWHelper;
use App\Models\Rekomendasi;
use App\Models\PenilaianHistori;

class HasilPenilaianController extends Controller
{
    public function index()
    {
        $kriteriaList = Kriteria::all();

        // Ambil semua penilaian yang tersedia
        $penilaianList = Penilaian::with('subkriteria')->get();

        // Gunakan helper untuk hitung SAW
        [$matrix, $errors] = SAWHelper::generateMatrix($penilaianList);
        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        $hasilRankingDefault = [];
        foreach ($ranking as $cpmiId => $nilai) {
            $hasilRankingDefault[] = [
                'cpmi' => Cpmi::find($cpmiId),
                'nilai' => round($nilai, 4),
            ];
        }

        return view('content.history.filter-penilaian', compact('kriteriaList', 'hasilRankingDefault'));
    }

    public function ajaxFilter(Request $request)
    {
        $filters = $request->filters;

        // 1. Dapatkan ID CPMI yang memenuhi semua filter subkriteria
        $rekomCpmiIds = Rekomendasi::pluck('cpmi_id')->toArray();

        $filteredCpmiQuery = Penilaian::whereNotIn('cpmi_id', $rekomCpmiIds);

        foreach ($filters as $filter) {
            $filteredCpmiQuery->whereHas('subkriteria', function ($q) use ($filter) {
                $q->where('kriteria_id', $filter['kriteria_id'])
                    ->where('subkriteria_id', $filter['subkriteria_id']);
            });
        }

        $filteredCpmiIds = $filteredCpmiQuery->pluck('cpmi_id')->unique();

        // 2. Ambil semua penilaian untuk semua CPMI (agar SAW tetap valid)
        $allPenilaian = Penilaian::with('subkriteria')->get();
        $kriteriaList = Kriteria::all();

        // 3. Hitung SAW global
        [$matrix, $errors] = SAWHelper::generateMatrix($allPenilaian);
        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        // 4. Filter hasil ranking berdasarkan CPMI yg cocok filter
        $hasil = [];
        foreach ($ranking as $cpmiId => $nilai) {
            if ($filteredCpmiIds->contains($cpmiId)) {
                $hasil[] = [
                    'cpmi' => Cpmi::find($cpmiId),
                    'nilai' => round($nilai, 4),
                ];
            }
        }

        // 5. Render HTML dan kembalikan
        $html = view('content.history._table-ranking', compact('hasil'))->render();
        return response()->json(['html' => $html]);
    }

    public function getSubkriteria($id)
    {
        return Subkriteria::where('kriteria_id', $id)->get();
    }

    // Tambahkan di atas kalau belum

    public function histori()
{
    $kriteriaList = Kriteria::all();
    $penilaianHistori = PenilaianHistori::with('subkriteria')->get();

    // Cek jika histori kosong, langsung return view dengan pesan
    if ($penilaianHistori->isEmpty()) {
        $data = collect(); // supaya bisa pakai $data->isEmpty() di blade
        $ranking = [];
        return view('content.history.penilaian-histori', compact('data', 'ranking'));
    }

    [$matrix, $errors] = SAWHelper::generateMatrix($penilaianHistori);
    $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
    $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
    $ranking = [];

foreach ($penilaianHistori as $histori) {
    if ($histori->nilai_saw !== null) {
        $ranking[$histori->cpmi_id] = $histori->nilai_saw;
    }
}


    $data = PenilaianHistori::with('cpmi')
        ->whereIn('cpmi_id', array_keys($ranking))
        ->get()
        ->groupBy('cpmi_id');

    return view('content.history.penilaian-histori', compact('data', 'ranking'));
}


    public function historiDetail($cpmiId)
{
    $cpmi = Cpmi::findOrFail($cpmiId);
    $data = PenilaianHistori::with('kriteria', 'subkriteria')
        ->where('cpmi_id', $cpmiId)
        ->get();

    return view('content.history.penilaian-histori-detail', compact('cpmi', 'data'));
}

}

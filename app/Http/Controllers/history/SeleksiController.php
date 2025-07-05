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

class SeleksiController extends Controller
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

   
}

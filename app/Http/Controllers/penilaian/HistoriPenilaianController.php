<?php

namespace App\Http\Controllers\penilaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenilaianHistori;
use App\Models\Cpmi;
use App\Models\Kriteria;
use App\Helpers\SAWHelper;

class HistoriPenilaianController extends Controller
{
    //
    public function index()
    {
        $kriteriaList = Kriteria::all();

        $histori = PenilaianHistori::with(['cpmi', 'kriteria', 'subkriteria', 'user'])->get();

        if ($histori->isEmpty()) {
            $data = collect();
            $ranking = [];
            return view('content.history.penilaian-histori', compact('data', 'ranking'));
        }

        [$matrix, $errors] = SAWHelper::generateMatrix($histori);

        $ranking = [];
        if (!empty($matrix)) {
            $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
            $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
            $ranking = SAWHelper::ranking($terbobot);
        }

        $data = $histori->groupBy('cpmi_id');

        return view('content.history.penilaian-histori', compact('data', 'ranking'));
    }

    public function detail($cpmiId)
    {
        $cpmi = Cpmi::findOrFail($cpmiId);

        $data = PenilaianHistori::with(['kriteria', 'subkriteria', 'user'])
            ->where('cpmi_id', $cpmiId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('content.history.penilaian-histori-detail', compact('cpmi', 'data'));
    }
}

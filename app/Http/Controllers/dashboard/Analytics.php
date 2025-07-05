<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\PenilaianHistori;
use App\Models\Rekomendasi;

class Analytics extends Controller
{
  public function index()
{
    $kriteria = Kriteria::all();
    $totalKriteria = $kriteria->count();
    $totalRekomendasi = PenilaianHistori::distinct('cpmi_id')->count('cpmi_id');


    // Ambil jumlah rekomendasi per bulan
    $rekomendasiPerBulan = PenilaianHistori::selectRaw('MONTH(created_at) as bulan, COUNT(DISTINCT cpmi_id) as total')
        ->whereYear('created_at', now()->year)
        ->groupByRaw('MONTH(created_at)')
        ->orderByRaw('MONTH(created_at)')
        ->get();

    // Siapkan data untuk chart
    $chartLabels = [];
    $chartData = [];

    for ($i = 1; $i <= 12; $i++) {
        $chartLabels[] = \Carbon\Carbon::create()->month($i)->format('F');
        $bulanData = $rekomendasiPerBulan->firstWhere('bulan', $i);
        $chartData[] = $bulanData ? $bulanData->total : 0;
    }

    return view('content.dashboard.dashboards-analytics', compact(
        'kriteria',
        'totalKriteria',
        'totalRekomendasi',
        'chartLabels',
        'chartData'
    ));
}

}

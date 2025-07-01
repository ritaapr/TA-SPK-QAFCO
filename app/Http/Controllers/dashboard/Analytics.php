<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Cpmi;
use App\Models\Penilaian;
use App\Models\Rekomendasi;
use App\Helpers\SAWHelper;


class Analytics extends Controller
{
  public function index()
  {

    $kriteria = Kriteria::all();
    $totalKriteria = $kriteria->count();
    $rekomendasi = Rekomendasi::all();
    $totalRekomendasi = Rekomendasi::count();

    return view('content.dashboard.dashboards-analytics', compact(
      'kriteria',
      'totalKriteria',
      'totalRekomendasi'
    ));
  }
}

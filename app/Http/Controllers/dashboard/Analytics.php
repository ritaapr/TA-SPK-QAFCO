<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class Analytics extends Controller
{
  public function index()
  {

    $kriteria = Kriteria::all();
$totalKriteria = $kriteria->count();
$labels = $kriteria->pluck('nama_kriteria');
$bobot = $kriteria->pluck('bobot');

return view('content.dashboard.dashboards-analytics', compact('kriteria', 'totalKriteria', 'labels', 'bobot'));

  }
}

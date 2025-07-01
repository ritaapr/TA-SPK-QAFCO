<?php

namespace App\Http\Controllers\rekomendasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekomendasi;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\PenilaianHistori;
use Barryvdh\DomPDF\Facade\Pdf;

class RekomendasiController extends Controller
{
    public function store(Request $request)
    {
        $cpmiId = $request->cpmi_id;

        if (!Rekomendasi::where('cpmi_id', $cpmiId)->exists()) {
            Rekomendasi::create(['cpmi_id' => $cpmiId]);

            //  Ambil SEMUA penilaian
            $allPenilaian = \App\Models\Penilaian::with('subkriteria')->get();
            $kriteriaList = Kriteria::all();

            //  Hitung SAW dari semua data
            [$matrix, $errors] = \App\Helpers\SAWHelper::generateMatrix($allPenilaian);
            $normalisasi = \App\Helpers\SAWHelper::normalisasi($matrix, $kriteriaList);
            $terbobot = \App\Helpers\SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
            $ranking = \App\Helpers\SAWHelper::ranking($terbobot);

            // Ambil hanya penilaian untuk CPMI yg dipilih
            $cpmiPenilaian = Penilaian::where('cpmi_id', $cpmiId)->get();
            $nilaiSAW = $ranking[$cpmiId] ?? null;

            $isFirst = true;
            foreach ($cpmiPenilaian as $p) {
                PenilaianHistori::create([
                    'cpmi_id' => $p->cpmi_id,
                    'kriteria_id' => $p->kriteria_id,
                    'subkriteria_id' => $p->subkriteria_id,
                    'nilai' => optional($p->subkriteria)->nilai,
                    'nilai_saw' => $isFirst ? $nilaiSAW : null,
                ]);
                $isFirst = false;
            }

            // Hapus dari penilaian aktif
            Penilaian::where('cpmi_id', $cpmiId)->delete();
        }

        return redirect()->back()->with('success', 'CPMI berhasil direkomendasikan dan histori disimpan.');
    }


    public function index()
    {
        $data = Rekomendasi::with([
            'cpmi',
            'histori.kriteria',
            'histori.subkriteria',
        ])->get()->sortByDesc(function ($item) {
            return $item->histori->firstWhere('nilai_saw', '!=', null)?->nilai_saw ?? 0;
        })->values(); // reset ulang index biar $loop->index tetap rapi


        return view('content.rekomendasi.rekomendasi-index', compact('data'));
    }

    public function exportPdf(Request $request)
    {
        $data = Rekomendasi::with([
            'cpmi',
            'histori.kriteria',
            'histori.subkriteria',
        ])->get()->sortByDesc(function ($item) {
            return $item->histori->firstWhere('nilai_saw', '!=', null)?->nilai_saw ?? 0;
        })->values(); // reset ulang index biar $loop->index tetap rapi


        $pdf = Pdf::loadView('content.rekomendasi.rekomendasi-pdf', compact('data'));

        if ($request->query('download') === 'true') {
            return $pdf->download('laporan_rekomendasi_cpmi.pdf');
        } else {
            return $pdf->stream('laporan_rekomendasi_cpmi.pdf');
        }
    }
}

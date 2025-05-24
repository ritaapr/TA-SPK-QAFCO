<?php

namespace App\Http\Controllers\form_layouts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Helpers\SAWHelper;
use Barryvdh\DomPDF\Facade\Pdf;


class PenilaianController extends Controller
{
    /**
     * Tampilkan tabel matriks penilaian (alternatif × kriteria)
     */
    public function index()
    {
        // Ambil semua kriteria (kolom) dan semua CPMI beserta penilaian mereka
        $kriteriaList = Kriteria::all();
        $cpmiList = Cpmi::with(['penilaians.subkriteria'])->get();

        return view('content.form-layout.penilaian-index', compact('kriteriaList', 'cpmiList'));
    }

    /**
     * Tampilkan form penilaian per CPMI
     */
    public function create()
    {
        $cpmiList = Cpmi::all();
        $kriteriaList = Kriteria::with('subkriterias')->get();
        $penilaians = Penilaian::with(['cpmi', 'kriteria', 'subkriteria'])->get();

        return view('content.form-layout.penilaian', compact('cpmiList', 'kriteriaList', 'penilaians'));
    }

    /**
     * Simpan penilaian CPMI
     */
    public function store(Request $request)
    {
        $request->validate([
            'cpmi_id' => 'required|exists:cpmi,id',
            'kriteria' => 'required|array',
            'kriteria.*' => 'required|exists:subkriteria,id',
        ]);

        // Hapus penilaian lama untuk CPMI ini
        Penilaian::where('cpmi_id', $request->cpmi_id)->delete();

        // Simpan penilaian baru
        foreach ($request->kriteria as $kriteria_id => $subkriteria_id) {
            Penilaian::create([
                'cpmi_id' => $request->cpmi_id,
                'kriteria_id' => $kriteria_id,
                'subkriteria_id' => $subkriteria_id,
            ]);
        }

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function perhitungan()
    {
        $kriteriaList = Kriteria::all();
        $cpmiList = Cpmi::with('penilaians.subkriteria')->get();

        $matrix = [];

        foreach ($cpmiList as $cpmi) {
            foreach ($kriteriaList as $kriteria) {
                $nilai = $cpmi->penilaians->firstWhere('kriteria_id', $kriteria->id)?->subkriteria->nilai ?? 0;
                $matrix[$cpmi->id][$kriteria->id] = $nilai;
            }
        }

        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        return view('content.form-layout.penilaian-perhitungan', compact(
            'cpmiList',
            'kriteriaList',
            'matrix',
            'normalisasi',
            'terbobot',
            'ranking'
        ));
    }

    public function exportPdf()
    {
        $cpmiList = Cpmi::all();
        $kriteriaList = Kriteria::all();
        $penilaian = Penilaian::with(['cpmi', 'kriteria', 'subkriteria'])->get();

        // Perbaikan: kirim hanya $penilaian ke generateMatrix
        $matrix = SAWHelper::generateMatrix($penilaian);
        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        $pdf = Pdf::loadView('content.form-layout.laporan-ranking', [
            'ranking' => $ranking,
            'cpmiList' => $cpmiList,
            'kriteriaList' => $kriteriaList,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-ranking-cpmi.pdf');
    }
}

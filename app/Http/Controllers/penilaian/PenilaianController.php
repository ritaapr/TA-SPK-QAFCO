<?php

namespace App\Http\Controllers\penilaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Subkriteria;
use App\Helpers\SAWHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class PenilaianController extends Controller
{
    public function index()
    {
        $kriteriaList = Kriteria::all();
        $cpmiList = Cpmi::with(['penilaians.subkriteria'])->get();

        return view('content.form-layout.penilaian-index', compact('kriteriaList', 'cpmiList'));
    }

    public function create() {}

    public function store(Request $request)
{
    $request->validate([
        'cpmi_id' => 'required|exists:cpmi,id',
        'penilaian' => 'array',
        'kriteria' => 'array',
    ]);

    Penilaian::where('cpmi_id', $request->cpmi_id)->delete();

    // Input number
    if ($request->has('penilaian')) {
        foreach ($request->penilaian as $kriteriaId => $inputValue) {
            $sub = Subkriteria::where('kriteria_id', $kriteriaId)
                ->where('batas_bawah', '<=', $inputValue)
                ->where('batas_atas', '>=', $inputValue)
                ->first();

            if (!$sub) {
                return back()->with('error', "Tidak ada subkriteria yang cocok untuk nilai $inputValue pada kriteria ID $kriteriaId");
            }

            Penilaian::create([
                'cpmi_id' => $request->cpmi_id,
                'kriteria_id' => $kriteriaId,
                'subkriteria_id' => $sub->id,
                'nilai' => $sub->nilai,
            ]);
        }
    }

    // Dropdown
    if ($request->has('kriteria')) {
        foreach ($request->kriteria as $kriteriaId => $subkriteriaId) {
            $sub = Subkriteria::find($subkriteriaId);
            if ($sub) {
                Penilaian::create([
                    'cpmi_id' => $request->cpmi_id,
                    'kriteria_id' => $kriteriaId,
                    'subkriteria_id' => $sub->id,
                    'nilai' => $sub->nilai,
                ]);
            }
        }
    }

    return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan!');
}


    public function edit($cpmi_id)
    {
        $cpmiList = Cpmi::all();
        $kriteriaList = Kriteria::with('subkriterias')->get();
        $cpmi = Cpmi::with('penilaians')->findOrFail($cpmi_id);

        return view('content.form-layout.penilaian-index', compact('cpmiList', 'kriteriaList', 'cpmi'));
    }
public function update(Request $request, $cpmi_id)
{
    $request->validate([
        'penilaian' => 'array',
        'kriteria' => 'array',
    ]);

    // Hapus semua penilaian lama untuk CPMI
    Penilaian::where('cpmi_id', $cpmi_id)->delete();

    // Proses nilai dari input angka
    if ($request->has('penilaian')) {
        foreach ($request->penilaian as $kriteriaId => $inputValue) {
            $sub = Subkriteria::where('kriteria_id', $kriteriaId)
                ->where('batas_bawah', '<=', $inputValue)
                ->where('batas_atas', '>=', $inputValue)
                ->first();

            if (!$sub) {
                return back()->with('error', "Tidak ada subkriteria yang cocok untuk nilai $inputValue pada kriteria ID $kriteriaId");
            }

            Penilaian::create([
                'cpmi_id' => $cpmi_id,
                'kriteria_id' => $kriteriaId,
                'subkriteria_id' => $sub->id,
                'nilai' => $sub->nilai,
            ]);
        }
    }

    // Proses nilai dari dropdown
    if ($request->has('kriteria')) {
        foreach ($request->kriteria as $kriteriaId => $subkriteriaId) {
            $sub = Subkriteria::find($subkriteriaId);
            if ($sub) {
                Penilaian::create([
                    'cpmi_id' => $cpmi_id,
                    'kriteria_id' => $kriteriaId,
                    'subkriteria_id' => $sub->id,
                    'nilai' => $sub->nilai,
                ]);
            }
        }
    }

    return redirect()->route('penilaian.index')->with('success', 'Penilaian CPMI berhasil diperbarui!');
}


    public function destroy($cpmi_id)
    {
        Penilaian::where('cpmi_id', $cpmi_id)->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian CPMI berhasil dihapus.');
    }

    public function perhitungan()
    {
        $kriteriaList = Kriteria::all();
        $penilaianList = Penilaian::with(['subkriteria'])->get();
        // Ambil ID CPMI yang sudah direkomendasikan (sudah punya histori)
$rekomendasiIds = \App\Models\Rekomendasi::pluck('cpmi_id');

// Ambil hanya CPMI yang belum direkomendasikan
$cpmiList = \App\Models\Cpmi::whereNotIn('id', $rekomendasiIds)->get();


        [$matrix, $errors] = SAWHelper::generateMatrix($penilaianList);

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
        }

        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        return view('content.form-layout.penilaian-perhitungan', compact(
            'cpmiList', 'kriteriaList', 'matrix', 'normalisasi', 'terbobot', 'ranking'
        ));
    }

    public function exportPdf()
    {
        $cpmiList = Cpmi::all();
        $kriteriaList = Kriteria::all();
        $penilaian = Penilaian::with(['cpmi', 'kriteria', 'subkriteria'])->get();

        [$matrix, ] = SAWHelper::generateMatrix($penilaian);
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

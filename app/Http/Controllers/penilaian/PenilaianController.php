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

        $cpmiList = Cpmi::with(['penilaian.subkriteria'])
            ->whereNotIn('id', function ($query) {
                $query->select('cpmi_id')->from('rekomendasi');
            })
            ->whereNotIn('id', function ($query) {
                $query->select('cpmi_id')->from('penilaian_histori');
            })
            ->get();


        return view('content.form-layout.penilaian-index', compact('kriteriaList', 'cpmiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cpmi_id' => 'required|exists:cpmi,id',
            'penilaian' => 'array',
            'kriteria' => 'array',
        ]);

        Penilaian::where('cpmi_id', $request->cpmi_id)->delete();

        try {
            if ($request->has('penilaian')) {
                foreach ($request->penilaian as $kriteriaId => $inputValue) {
                    Penilaian::simpanSementara($request->cpmi_id, $kriteriaId, $inputValue);
                }
            }

            if ($request->has('kriteria')) {
                foreach ($request->kriteria as $kriteriaId => $subkriteriaId) {
                    Penilaian::simpanSementara($request->cpmi_id, $kriteriaId, null, $subkriteriaId);
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan!');
    }

    public function update(Request $request, $cpmi_id)
    {
        $request->validate([
            'penilaian' => 'array',
            'kriteria' => 'array',
        ]);

        Penilaian::where('cpmi_id', $cpmi_id)->delete();

        try {
            if ($request->has('penilaian')) {
                foreach ($request->penilaian as $kriteriaId => $inputValue) {
                    Penilaian::simpanSementara($cpmi_id, $kriteriaId, $inputValue);
                }
            }

            if ($request->has('kriteria')) {
                foreach ($request->kriteria as $kriteriaId => $subkriteriaId) {
                    Penilaian::simpanSementara($cpmi_id, $kriteriaId, null, $subkriteriaId);
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil diperbarui!');
    }

    public function destroy($cpmi_id)
    {
        Penilaian::where('cpmi_id', $cpmi_id)->delete();
        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil dihapus.');
    }

    public function perhitungan()
    {
        $kriteriaList = Kriteria::all();

        $penilaianList = Penilaian::with(['subkriteria'])->get();
        $cpmiIds = $penilaianList->pluck('cpmi_id')->unique()->toArray();
        $cpmiList = Cpmi::whereIn('id', $cpmiIds)->get();

        [$matrix, $errors] = SAWHelper::generateMatrix($penilaianList);

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors);
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
        $penilaian = \App\Models\PenilaianHistori::with(['cpmi', 'kriteria', 'subkriteria'])->get();

        [$matrix,] = SAWHelper::generateMatrix($penilaian);
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

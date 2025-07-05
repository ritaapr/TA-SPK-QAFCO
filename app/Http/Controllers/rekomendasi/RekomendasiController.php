<?php

namespace App\Http\Controllers\Rekomendasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cpmi;
use App\Models\Rekomendasi;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\PenilaianHistori;
use App\Helpers\SAWHelper;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cpmi_id' => 'required|exists:cpmi,id',
            'nilai_saw' => 'required|numeric',
            'ranking'   => 'nullable|numeric'
        ]);

        $cpmiId = $request->cpmi_id;

        if (!Rekomendasi::where('cpmi_id', $cpmiId)->exists()) {
            // Simpan ke tabel rekomendasi (untuk tampilan rekomendasi sekarang)
            Rekomendasi::create([
                'cpmi_id'   => $cpmiId,
                'nilai_saw' => $request->nilai_saw,
                'ranking'   => $request->ranking ?? null,
            ]);

            // Simpan ke histori penilaian
            $penilaianList = Penilaian::where('cpmi_id', $cpmiId)->get();

            foreach ($penilaianList as $penilaian) {
                PenilaianHistori::create([
                    'cpmi_id'        => $cpmiId,
                    'kriteria_id'    => $penilaian->kriteria_id,
                    'subkriteria_id' => $penilaian->subkriteria_id,
                    'nilai'          => optional($penilaian->subkriteria)->nilai,
                    'user_id'        => $penilaian->user_id,
                    'created_at'     => now(),
                ]);
            }

            // Hapus data penilaian aktif
            Penilaian::where('cpmi_id', $cpmiId)->delete();
        }

        return redirect()->back()->with('success', 'CPMI berhasil direkomendasikan dan dicatat ke histori.');
    }

    public function index()
    {
        $data = Rekomendasi::with(['cpmi'])->get();
        return view('content.rekomendasi.rekomendasi-index', compact('data'));
    }

   public function exportPdf(Request $request)
{
    $data = Rekomendasi::with('cpmi.penilaianHistori.kriteria', 'cpmi.penilaianHistori.subkriteria')->get();

    $pdf = Pdf::loadView('content.rekomendasi.rekomendasi-pdf', compact('data'));

    return $request->query('download') === 'true'
        ? $pdf->download('laporan_rekomendasi_cpmi.pdf')
        : $pdf->stream('laporan_rekomendasi_cpmi.pdf');
}

    

    public function arsipkan()
    {
        Rekomendasi::truncate();

        return redirect()->route('rekomendasi.index')->with('success', 'Rekomendasi berhasil direset. Silakan lakukan seleksi ulang.');
    }
}

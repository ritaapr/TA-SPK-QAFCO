<?php

namespace App\Http\Controllers\data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class KriteriaController extends Controller
{
    public function index()
    {
        $result = Kriteria::getAllWithStatus();
        $kriterias = $result['data'];
        $bobotTerkunci = $result['bobotTerkunci'];

        return view('content.data.data-kriteria-table', compact('kriterias', 'bobotTerkunci'));
    }

    public function getSubkriteria($id)
    {
        $kriteria = Kriteria::with('subkriterias')->findOrFail($id);
        return response()->json($kriteria->subkriterias);
    }

    public function create()
    {
        return view('content.data.data-kriteria');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required',
            'nama_kriteria' => 'required',
            'jenis' => 'required|in:benefit,cost',
        ]);

        Kriteria::tambahKriteria($request->only(['kode_kriteria', 'nama_kriteria', 'jenis']));

        return redirect()->route('data-kriteria.index')->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('content.data.data-kriteria-edit', compact('kriteria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kriteria' => 'required',
            'nama_kriteria' => 'required',
            'jenis' => 'required|in:benefit,cost',
        ]);

        $kriteria = Kriteria::findOrFail($id);
        $kriteria->perbaruiKriteria($request->only(['kode_kriteria', 'nama_kriteria', 'jenis']));

        return redirect()->route('data-kriteria.index')->with('success', 'Kriteria berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('data-kriteria.index')->with('success', 'Kriteria berhasil dihapus');
    }

    public function hitungBobot(Request $request)
    {
        $data = $request->input('data');

        $hasil = Kriteria::prosesHitungBobot($data);

        if (!$hasil) {
            return response()->json(['message' => 'Total skala tidak boleh nol.'], 400);
        }

        return response()->json(['message' => 'Bobot berhasil dihitung dan disimpan!']);
    }

    public function resetBobot()
    {
        Kriteria::resetSemuaBobot();

        return response()->json(['message' => 'Bobot berhasil direset!']);
    }
}

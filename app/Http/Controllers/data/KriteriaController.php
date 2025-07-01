<?php

namespace App\Http\Controllers\data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $bobotTerkunci = $kriterias->first() ? $kriterias->first()->status_bobot : false;
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

        Kriteria::create($request->all());

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
        $kriteria->update($request->all());

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
        $data = $request->input('data'); // Ambil data dari fetch
        $totalSkala = array_sum(array_column($data, 'skala'));

        if ($totalSkala == 0) {
            return response()->json(['message' => 'Total skala tidak boleh nol.'], 400);
        }

        foreach ($data as $item) {
            $bobot = $item['skala'] / $totalSkala;

            Kriteria::where('id', $item['id'])->update([
                'skala' => $item['skala'],
                'bobot' => $bobot,
                'status_bobot' => true
            ]);
        }

        return response()->json(['message' => 'Bobot berhasil dihitung dan disimpan!']);
    }

    public function resetBobot()
    {
        // Set kolom skala dan bobot menjadi null
        Kriteria::query()->update([
            'skala' => null,
            'bobot' => null,
            'status_bobot' => false
        ]);

        return response()->json(['message' => 'Bobot berhasil direset!']);
    }
}

<?php

namespace App\Http\Controllers\form_elements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;

class BasicInput extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all(); // Ambil semua data kriteria
        return view('content.form-elements.forms-kriteria-inputs-table', compact('kriterias'));
    }

    public function create()
    {
        return view('content.form-elements.forms-kriteria-inputs');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required',
            'nama_kriteria' => 'required',
            'jenis' => 'required|in:benefit,cost',

        ]);

        Kriteria::create($request->all());

        return redirect()->route('forms-kriteria-inputs.index')->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('content.form-elements.forms-kriteria-inputs-edit', compact('kriteria'));
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

        return redirect()->route('forms-kriteria-inputs.index')->with('success', 'Kriteria berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();

        return redirect()->route('forms-kriteria-inputs.index')->with('success', 'Kriteria berhasil dihapus');
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
            ]);
        }

        return response()->json(['message' => 'Bobot berhasil dihitung dan disimpan!']);
    }
}

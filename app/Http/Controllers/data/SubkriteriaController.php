<?php

namespace App\Http\Controllers\data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subkriteria;
use App\Models\Kriteria;

class SubkriteriaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kriteria beserta relasi subkriteria
        $kriterias = Kriteria::with('subkriterias')->get();

        // Filter jika ada kriteria_id yang dipilih
        $kriteriaId = request('kriteria_id') ?? $kriterias->first()->id;

        $filteredKriterias = $kriterias->filter(function ($k) use ($kriteriaId) {
            return $k->id == $kriteriaId;
        });


        return view('content.data.data-subkriteria-table', compact('kriterias', 'filteredKriterias'));
    }


    public function create()
    {
        $kriterias = Kriteria::all();
        return view('content.data.data-subkriteria', compact('kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,id',
            'nama_subkriteria' => 'required|string',
            'batas_bawah' => 'nullable|integer',
            'batas_atas' => 'nullable|integer',
            'nilai' => 'required|integer',
        ]);

        Subkriteria::create($request->all());
        return redirect()
            ->route('data-subkriteria', ['kriteria_id' => $request->kriteria_id])
            ->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $subkriteria = Subkriteria::findOrFail($id);
        $kriterias = Kriteria::all();
        return view('content.data.data-subkriteria-edit', compact('subkriteria', 'kriterias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,id',
            'nama_subkriteria' => 'required|string',
            'batas_bawah' => 'nullable|integer',
            'batas_atas' => 'nullable|integer',
            'nilai' => 'required|integer',
        ]);

        $subkriteria = Subkriteria::findOrFail($id);
        $subkriteria->update($request->all());

        return redirect()
            ->route('data-subkriteria', ['kriteria_id' => $request->kriteria_id])
            ->with('success', 'Data berhasil diupdate!');
    }
    public function destroy(Request $request, Subkriteria $forms_subkriteria_input)
    {
        $forms_subkriteria_input->delete();

        return redirect()
            ->route('data-subkriteria', ['kriteria_id' => $request->redirect_kriteria_id])
            ->with('success', 'Subkriteria berhasil dihapus.');
    }
}

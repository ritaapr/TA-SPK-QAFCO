<?php

namespace App\Http\Controllers\form_elements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subkriteria;
use App\Models\Kriteria;

class InputSubkriteria extends Controller
{
  public function index()
    {
        $kriterias = Kriteria::with('subkriterias')->get();
        return view('content.form-elements.forms-subkriteria-inputs-table', compact('kriterias'));
    }

    public function create()
    {
        $kriterias = Kriteria::all();
        return view('content.form-elements.forms-subkriteria-inputs', compact('kriterias'));
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

        return redirect()->route('forms-subkriteria-inputs')->with('success', 'Subkriteria berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $subkriteria = Subkriteria::findOrFail($id);
        $kriterias = Kriteria::all();
        return view('content.form-elements.forms-subkriteria-inputs-edit', compact('subkriteria', 'kriterias'));
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

        return redirect()->route('forms-subkriteria-inputs')->with('success', 'Subkriteria berhasil diupdate.');
    }

    public function destroy($id)
    {
        $subkriteria = Subkriteria::findOrFail($id);
        $subkriteria->delete();

        return redirect()->route('forms-subkriteria-inputs')->with('success', 'Subkriteria berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;

class CpmiController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = Cpmi::getTahunList();
        $filterTahun = $request->input('tahun');
        $cpmis = Cpmi::getFiltered($filterTahun);

        return view('content.data.data-cpmi-table', compact('cpmis', 'tahunList'));
    }

    public function create()
    {
        return view('content.data.data-cpmi-copy');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cpmi' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tanggal_daftar' => 'required|date',
            'foto_profil' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        Cpmi::simpanData($request);

        return redirect()->route('data-cpmi-copy', ['page' => $request->input('page')])
                         ->with('success', 'Data CPMI berhasil disimpan!');
    }

    public function edit($id)
    {
        $cpmi = Cpmi::findOrFail($id);
        return view('content.data.data-cpmi-copy-edit', compact('cpmi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_cpmi' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'tanggal_daftar' => 'required|date',
            'foto_profil' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $cpmi = Cpmi::findOrFail($id);
        $cpmi->perbaruiData($request);

        return redirect()->route('data-cpmi-copy', ['page' => $request->input('page')])
                         ->with('success', 'Data CPMI berhasil diupdate!');
    }

    public function destroy(Request $request, $id)
    {
        $cpmi = Cpmi::findOrFail($id);
        $cpmi->hapusData();

        return redirect()->route('data-cpmi-copy', ['page' => $request->input('page')])
                         ->with('success', 'Data CPMI berhasil dihapus!');
    }
}
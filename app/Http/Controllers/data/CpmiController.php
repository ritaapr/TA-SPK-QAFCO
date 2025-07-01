<?php

namespace App\Http\Controllers\data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;
use Illuminate\Support\Facades\Storage;

class CpmiController extends Controller
{
  public function index(Request $request)
  {
    // Ambil daftar tahun unik dari data CPMI
    $tahunList = Cpmi::selectRaw('YEAR(tanggal_daftar) as tahun')
      ->distinct()
      ->orderBy('tahun', 'desc')
      ->pluck('tahun');

    // Ambil tahun yang difilter dari query string
    $filterTahun = $request->input('tahun');

    // Query data CPMI sesuai filter tahun
    $cpmis = Cpmi::when($filterTahun, function ($query) use ($filterTahun) {
      return $query->whereYear('tanggal_daftar', $filterTahun);
    })->orderBy('tanggal_daftar', 'desc')->paginate(10);

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

    $fotoPath = $request->file('foto_profil')->store('foto_profil', 'public');

    Cpmi::create([
      'nama_cpmi' => $request->nama_cpmi,
      'nik' => $request->nik,
      'alamat' => $request->alamat,
      'no_hp' => $request->no_hp,
      'tanggal_daftar' => $request->tanggal_daftar,
      'foto_profil' => $fotoPath,
    ]);

    $page = $request->input('page'); // Ambil dari hidden input

    return redirect()->route('data-cpmi-copy', ['page' => $page])->with('success', 'Data CPMI berhasil disimpan!');
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

    if ($request->hasFile('foto_profil')) {
      if ($cpmi->foto_profil && Storage::disk('public')->exists($cpmi->foto_profil)) {
        Storage::disk('public')->delete($cpmi->foto_profil);
      }
      $fotoPath = $request->file('foto_profil')->store('foto_profil', 'public');
      $cpmi->foto_profil = $fotoPath;
    }

    $cpmi->nama_cpmi = $request->nama_cpmi;
    $cpmi->nik = $request->nik;
    $cpmi->alamat = $request->alamat;
    $cpmi->no_hp = $request->no_hp;
    $cpmi->tanggal_daftar = $request->tanggal_daftar;
    $cpmi->save();

    $page = $request->input('page');

    return redirect()->route('data-cpmi-copy', ['page' => $page])->with('success', 'Data CPMI berhasil diupdate!');
  }

  public function destroy(Request $request, $id)
  {
    $cpmi = Cpmi::findOrFail($id);

    if ($cpmi->foto_profil && Storage::disk('public')->exists($cpmi->foto_profil)) {
      Storage::disk('public')->delete($cpmi->foto_profil);
    }

    $cpmi->delete();

    $page = $request->input('page');

    return redirect()->route('data-cpmi-copy', ['page' => $page])->with('success', 'Data CPMI berhasil dihapus!');
  }
}

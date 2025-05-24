<?php

namespace App\Http\Controllers\form_elements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;
use Illuminate\Support\Facades\Storage;


class BasicInputCopy extends Controller
{
  public function index()
  {
    $cpmis = Cpmi::all(); // ambil semua data dari table cpmis
    return view('content.form-elements.forms-basic-inputs-table', compact('cpmis'));
  }

  public function create()
  {
    return view('content.form-elements.forms-basic-inputs-copy'); // ini file form inputnya
  }

  public function store(Request $request)
  {
    // Validasi input
    $request->validate([
      'nama_cpmi' => 'required|string|max:255',
      'nik' => 'required|string|max:16',
      'alamat' => 'required|string',
      'no_hp' => 'required|string|max:20',
      'tanggal_daftar' => 'required|date',
      'ktp' => 'required|image|mimes:jpeg,jpg,png|max:2048', // maksimal 2MB
    ]);

    // Upload file KTP
    $ktpPath = $request->file('ktp')->store('ktp', 'public'); // disimpan di storage/app/public/ktp

    // Simpan ke database
    Cpmi::create([
      'nama_cpmi' => $request->nama_cpmi,
      'nik' => $request->nik,
      'alamat' => $request->alamat,
      'no_hp' => $request->no_hp,
      'tanggal_daftar' => $request->tanggal_daftar,
      'ktp' => $ktpPath,
    ]);

    // Redirect atau kasih pesan sukses
    return redirect()->route('forms-basic-inputs-copy')->with('success', 'Data CPMI berhasil disimpan!');
  }

  public function edit($id)
  {
    $cpmi = Cpmi::findOrFail($id); // cari data berdasarkan id
    return view('content.form-elements.forms-basic-inputs-copy-edit', compact('cpmi'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'nama_cpmi' => 'required|string|max:255',
      'nik' => 'required|string|max:16',
      'alamat' => 'required|string',
      'no_hp' => 'required|string|max:20',
      'tanggal_daftar' => 'required|date',
      'ktp' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    ]);

    $cpmi = Cpmi::findOrFail($id);

    if ($request->hasFile('ktp')) {
      $ktpPath = $request->file('ktp')->store('ktp', 'public');
      $cpmi->ktp = $ktpPath;
    }

    $cpmi->nama_cpmi = $request->nama_cpmi;
    $cpmi->nik = $request->nik;
    $cpmi->alamat = $request->alamat;
    $cpmi->no_hp = $request->no_hp;
    $cpmi->tanggal_daftar = $request->tanggal_daftar;
    $cpmi->save();

    return redirect()->route('forms-basic-inputs-copy')->with('success', 'Data CPMI berhasil diupdate!');
  }

  public function destroy($id)
  {
    $cpmi = Cpmi::findOrFail($id);

    // Kalau ada file KTP, bisa sekalian hapus file dari storage
    if ($cpmi->ktp && Storage::disk('public')->exists($cpmi->ktp)) {
      Storage::disk('public')->delete($cpmi->ktp);
    }

    $cpmi->delete();

    return redirect()->route('forms-basic-inputs-copy')->with('success', 'Data CPMI berhasil dihapus!');
  }
}

@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs-copy.js')
@endsection

@section('content')

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header mb-0">Tambah Data Kriteria</h5>
        <a href="{{ route('forms-kriteria-inputs.index') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali ke daftar" style="font-size: 1.2rem;">&times;</a>
      </div>

      <div class="card-body demo-vertical-spacing demo-only-element">
        <form method="POST" action="{{ route('forms-kriteria-inputs.store') }}">
          @csrf

          <!-- Kode Kriteria -->
          <div class="mb-3 row">
            <label for="kodeKriteria" class="col-md-2 col-form-label">Kode Kriteria</label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="kodeKriteria" name="kode_kriteria" placeholder="Masukkan Kode Kriteria (contoh: K01)" required />
            </div>
          </div>

          <!-- Nama Kriteria -->
          <div class="mb-3 row">
            <label for="namaKriteria" class="col-md-2 col-form-label">Nama Kriteria</label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="namaKriteria" name="nama_kriteria" placeholder="Masukkan Nama Kriteria" required />
            </div>
          </div>

          <!-- Jenis -->
          <div class="mb-3 row">
            <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
            <div class="col-md-10">
              <select class="form-select" id="jenis" name="jenis" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
              </select>
            </div>
          </div>


          <!-- Tombol Submit -->
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-outline-secondary">Reset</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection

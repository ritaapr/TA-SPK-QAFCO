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
        <h5 class="card-header mb-0">Tambah Data Subkriteria</h5>
        <a href="{{ route('forms-subkriteria-inputs') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali ke daftar" style="font-size: 1.2rem;">&times;</a>
      </div>

      <div class="card-body demo-vertical-spacing demo-only-element">
        <form method="POST" action="{{ route('forms-subkriteria-inputs.store') }}">
          @csrf

          <!-- Kriteria (Relasi) -->
          <div class="mb-3 row">
            <label for="kriteria_id" class="col-md-2 col-form-label">Kriteria</label>
            <div class="col-md-10">
              <select class="form-select" id="kriteria_id" name="kriteria_id" required>
                <option value="">-- Pilih Kriteria --</option>
                @foreach ($kriterias as $kriteria)
                  <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Nama Subkriteria -->
          <div class="mb-3 row">
            <label for="nama_subkriteria" class="col-md-2 col-form-label">Nama Subkriteria</label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="nama_subkriteria" name="nama_subkriteria" placeholder="Masukkan Nama Subkriteria" required />
            </div>
          </div>

          <!-- Batas Bawah -->
          <div class="mb-3 row">
            <label for="batas_bawah" class="col-md-2 col-form-label">Batas Bawah</label>
            <div class="col-md-10">
              <input type="number" class="form-control" id="batas_bawah" name="batas_bawah" placeholder="Opsional - Masukkan Batas Bawah" />
            </div>
          </div>

          <!-- Batas Atas -->
          <div class="mb-3 row">
            <label for="batas_atas" class="col-md-2 col-form-label">Batas Atas</label>
            <div class="col-md-10">
              <input type="number" class="form-control" id="batas_atas" name="batas_atas" placeholder="Opsional - Masukkan Batas Atas" />
            </div>
          </div>

          <!-- Nilai -->
          <div class="mb-3 row">
            <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
            <div class="col-md-10">
              <input type="number" class="form-control" id="nilai" name="nilai" placeholder="Masukkan Nilai Subkriteria (contoh: 5)" required />
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

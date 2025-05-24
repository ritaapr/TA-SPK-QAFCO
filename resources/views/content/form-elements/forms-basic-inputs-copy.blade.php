@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-header mb-0">Tambah Data CPMI</h5>
          <a href="{{ route('forms-basic-inputs-copy') }}" class="btn btn-sm btn-outline-secondary me-3" title="Kembali ke daftar" style="font-size: 1.2rem;">&times;</a>
        </div>

        <div class="card-body demo-vertical-spacing demo-only-element">
          <form class="row g-3" method="POST" action="{{ route('forms-basic-inputs-copy.store') }}" enctype="multipart/form-data">
            @csrf
            <!-- Nama CPMI -->
            <div class="mb-3 row">
              <label for="namaCpmi" class="col-md-2 col-form-label">Nama CPMI</label>
              <div class="col-md-10">
                <input type="text" class="form-control" id="namaCpmi" name="nama_cpmi" placeholder="Masukkan Nama" required />
              </div>
            </div>

            <!-- NIK -->
            <div class="mb-3 row">
              <label for="nik" class="col-md-2 col-form-label">NIK</label>
              <div class="col-md-10">
                <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required />
              </div>
            </div>

            <!-- Alamat -->
            <div class="mb-3 row">
              <label for="alamat" class="col-md-2 col-form-label">Alamat</label>
              <div class="col-md-10">
                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat" required></textarea>
              </div>
            </div>

            <!-- No. HP -->
            <div class="mb-3 row">
              <label for="noHp" class="col-md-2 col-form-label">No. Hp</label>
              <div class="col-md-10">
                <input type="text" class="form-control" id="noHp" name="no_hp" placeholder="Masukkan No. Hp" required />
              </div>
            </div>

            <!-- Tanggal Daftar -->
            <div class="mb-3 row">
              <label for="tglDaftar" class="col-md-2 col-form-label">Tanggal Daftar</label>
              <div class="col-md-10">
                <input type="date" class="form-control" id="tglDaftar" name="tanggal_daftar" required />
              </div>
            </div>

            <!-- Upload KTP -->
            <div class="mb-3 row">
              <label for="ktp" class="col-md-2 col-form-label">Upload KTP</label>
              <div class="col-md-10">
                <input type="file" class="form-control" id="ktp" name="ktp" accept=".jpg,.jpeg,.png" required />
              </div>
            </div>

            <!-- Tombol -->
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
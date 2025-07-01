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
                    <h5 class="card-header mb-0">Edit Data Kriteria</h5>
                    <a href="{{ route('data-kriteria.index') }}" class="btn btn-sm btn-outline-secondary me-3"
                        title="Kembali ke daftar" style="font-size: 1.2rem;">&times;</a>
                </div>

                <div class="card-body demo-vertical-spacing demo-only-element">
                    <form class="row g-3" method="POST" action="{{ route('data-kriteria.update', $kriteria->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Kode Kriteria -->
                        <div class="mb-3 row">
                            <label for="kodeKriteria" class="col-md-2 col-form-label">Kode Kriteria</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="kodeKriteria" name="kode_kriteria"
                                    value="{{ old('kode_kriteria', $kriteria->kode_kriteria) }}"
                                    placeholder="Masukkan Kode Kriteria" required />
                            </div>
                        </div>

                        <!-- Nama Kriteria -->
                        <div class="mb-3 row">
                            <label for="namaKriteria" class="col-md-2 col-form-label">Nama Kriteria</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="namaKriteria" name="nama_kriteria"
                                    value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}"
                                    placeholder="Masukkan Nama Kriteria" required />
                            </div>
                        </div>

                        <!-- Jenis -->
                        <div class="mb-3 row">
                            <label for="jenis" class="col-md-2 col-form-label">Jenis</label>
                            <div class="col-md-10">
                                <select class="form-select" id="jenis" name="jenis" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="benefit"
                                        {{ old('jenis', $kriteria->jenis) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                    <option value="cost" {{ old('jenis', $kriteria->jenis) == 'cost' ? 'selected' : '' }}>
                                        Cost</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bobot -->
                        <div class="mb-3 row">
                            <label for="bobot" class="col-md-2 col-form-label">Bobot</label>
                            <div class="col-md-10">
                                <input type="number" step="0.01" min="0" max="1" class="form-control"
                                    id="bobot" name="bobot" value="{{ old('bobot', $kriteria->bobot) }}"
                                    placeholder="Masukkan Bobot (0 - 1)" readonly />
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('data-kriteria.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

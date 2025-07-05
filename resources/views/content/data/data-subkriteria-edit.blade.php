@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header mb-0">Edit Data Subkriteria</h5>
                    <a href="{{ route('data-subkriteria') }}" class="btn btn-sm btn-outline-secondary me-3"
                        title="Kembali ke daftar" style="font-size: 1.2rem;">&times;</a>
                </div>

                <div class="card-body demo-vertical-spacing demo-only-element">
                    <form class="row g-3" method="POST" action="{{ route('data-subkriteria.update', $subkriteria->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Pilih Kriteria -->
                        <div class="mb-3 row">
                            <label for="kriteria" class="col-md-2 col-form-label">Kriteria</label>
                            <div class="col-md-10">
                                <select class="form-select" id="kriteria" name="kriteria_id" required>
                                    <option value="">-- Pilih Kriteria --</option>
                                    @foreach ($kriterias as $kriteria)
                                        <option value="{{ $kriteria->id }}"
                                            {{ old('kriteria_id', $subkriteria->kriteria_id) == $kriteria->id ? 'selected' : '' }}>
                                            {{ $kriteria->nama_kriteria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nama Subkriteria -->
                        <div class="mb-3 row">
                            <label for="namaSubkriteria" class="col-md-2 col-form-label">Nama Subkriteria</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="namaSubkriteria" name="nama_subkriteria"
                                    value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}"
                                    placeholder="Masukkan Nama Subkriteria" required />
                            </div>
                        </div>

                        <!-- Batas Bawah -->
                        <div class="mb-3 row">
                            <label for="batasBawah" class="col-md-2 col-form-label">Batas Bawah</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" id="batasBawah" name="batas_bawah"
                                    value="{{ old('batas_bawah', $subkriteria->batas_bawah) }}"
                                    placeholder="Opsional (boleh dikosongi)" />
                            </div>
                        </div>

                        <!-- Batas Atas -->
                        <div class="mb-3 row">
                            <label for="batasAtas" class="col-md-2 col-form-label">Batas Atas</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" id="batasAtas" name="batas_atas"
                                    value="{{ old('batas_atas', $subkriteria->batas_atas) }}"
                                    placeholder="Opsional (boleh dikosongi)" />
                            </div>
                        </div>

                        <!-- Nilai -->
                        <div class="mb-3 row">
                            <label for="nilai" class="col-md-2 col-form-label">Nilai</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" id="nilai" name="nilai"
                                    value="{{ old('nilai', $subkriteria->nilai) }}" placeholder="Masukkan Nilai" required />
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('data-subkriteria') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

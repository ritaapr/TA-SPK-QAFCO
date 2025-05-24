@extends('layouts/contentNavbarLayout')

@section('title', 'Form Penilaian CPMI')

@section('content')

@if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

<div class="card">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="card-header">Form Penilaian CPMI</h5>
  </div>

  <div class="card-body demo-vertical-spacing demo-only-element">
    <form action="{{ route('penilaian.store') }}" method="POST">
      @csrf

      <!-- Dropdown untuk pilih CPMI -->
      <div class="mb-3">
        <label for="cpmi_id" class="form-label">Pilih CPMI</label>
        <select name="cpmi_id" id="cpmi_id" class="form-select" required>
          <option value="">-- Pilih CPMI --</option>
          @foreach($cpmiList as $cpmi)
            <option value="{{ $cpmi->id }}">{{ $cpmi->nama_cpmi }}</option>
          @endforeach
        </select>
      </div>

      <hr>

      <!-- Loop semua kriteria -->
      <div class="row">
        @foreach($kriteriaList as $kriteria)
          <div class="col-md-6 mb-3">
            <div class="card card-body">
              <label class="form-label">{{ $kriteria->nama_kriteria }}</label>
              <select name="kriteria[{{ $kriteria->id }}]" class="form-select" required>
                <option value="">-- Pilih {{ $kriteria->nama_kriteria }} --</option>
                @foreach($kriteria->subkriterias as $sub)
                  <option value="{{ $sub->id }}">{{ $sub->nama_subkriteria }} (Nilai: {{ $sub->nilai }})</option>
                @endforeach
              </select>
            </div>
          </div>
        @endforeach
      </div>

      <button type="submit" class="btn btn-primary mt-3">Simpan Penilaian</button>
    </form>
  </div>
</div>
@endsection

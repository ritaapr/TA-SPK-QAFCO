@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs-copy.js')
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Bordered Table -->
 <div class="card">
<div class="d-flex justify-content-between align-items-center">
    <h5 class="card-header mb-0">Data Subkriteria</h5>
    <a href="{{ route('forms-subkriteria-inputs.create') }}" class="btn rounded-pill btn-primary me-6">
      <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
    </a>
  </div>

  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Subkriteria</th>
            <th>Batas Bawah</th>
            <th>Batas Atas</th>
            <th>Nilai</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($kriterias as $kriteria)
          <tr class="table-primary">
            <td colspan="6"><strong>{{ $kriteria->nama_kriteria }}</strong></td>
          </tr>
          @forelse ($kriteria->subkriterias as $index => $subkriteria)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $subkriteria->nama_subkriteria }}</td>
            <td>{{ $subkriteria->batas_bawah ?? '-' }}</td>
            <td>{{ $subkriteria->batas_atas ?? '-' }}</td>
            <td>{{ $subkriteria->nilai }}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('forms-subkriteria-inputs.edit', $subkriteria->id) }}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <form action="{{ route('forms-subkriteria-inputs.destroy', $subkriteria->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item">
                      <i class="bx bx-trash me-1"></i> Delete
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center">Belum ada subkriteria untuk kriteria ini.</td>
          </tr>
          @endforelse
          @empty
          <tr>
            <td colspan="6" class="text-center">Data tidak tersedia.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
<!--/ Bordered Table -->
@endsection
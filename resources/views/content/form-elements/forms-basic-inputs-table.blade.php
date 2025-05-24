@extends('layouts/contentNavbarLayout')

@section('title', 'Data CPMI')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="card-header mb-0">Data CPMI</h5>
    <a href="{{ route('forms-basic-inputs-copy.create') }}" class="btn rounded-pill btn-primary me-6">
      <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
    </a>
  </div>
  <!-- Bordered Table -->


  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama CPMI</th>
            <th>NIK</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Tanggal Daftar</th>
            <th>KTP</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($cpmis as $index => $cpmi)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $cpmi->nama_cpmi }}</td>
            <td>{{ $cpmi->nik }}</td>
            <td>{{ $cpmi->alamat }}</td>
            <td>{{ $cpmi->no_hp }}</td>
            <td>{{ $cpmi->tanggal_daftar }}</td>
            <td>
              @if($cpmi->ktp)
              <img src="{{ asset('storage/' . $cpmi->ktp) }}" alt="KTP" width="100">
              @else
              Tidak ada foto
              @endif
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('forms-basic-inputs-copy.edit', $cpmi->id) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <form action="{{ route('forms-basic-inputs-copy.destroy', $cpmi->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
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
            <td colspan="8" class="text-center">Data tidak tersedia.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
<!--/ Bordered Table -->

@endsection
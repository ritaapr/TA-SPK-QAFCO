@extends('layouts/contentNavbarLayout')

@section('title', 'Data Subkriteria')
@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')
    @vite('resources/assets/js/success-message.js')
    @vite('resources/assets/js/delete-sweetalert.js')
    @vite('resources/assets/js/ambil-dropdown-kriteria.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('content')

    {{-- Blade Layout --}}

    <body @if (session('success')) data-success="{{ session('success') }}" @endif
        @if (session('error')) data-error="{{ session('error') }}" @endif>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Subkriteria</h5>

                <div class="d-flex align-items-center gap-2">
                    {{-- Filter Dropdown --}}
                    <form method="GET" action="{{ route('data-subkriteria') }}" class="mb-0">
                        <select name="kriteria_id" id="filter_kriteria" class="form-select" style="width: 200px"
                            onchange="this.form.submit()">
                            @foreach ($kriterias as $index => $kriteria)
                                <option value="{{ $kriteria->id }}"
                                    {{ request('kriteria_id', $kriterias->first()->id) == $kriteria->id ? 'selected' : '' }}>
                                    {{ $kriteria->nama_kriteria }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{-- Tombol Tambah --}}
                    @if (auth()->user()->role === 'superadmin')
                        <a href="javascript:void(0);" class="btn rounded-pill btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTambahSubkriteria">
                            <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body">


                {{-- Table --}}
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Subkriteria</th>
                                <th>Batas Bawah</th>
                                <th>Batas Atas</th>
                                <th>Nilai</th>
                                @if (auth()->user()->role === 'superadmin')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($filteredKriterias as $kriteria)
                                <tr class="table-secondary">
                                    <td colspan="{{ auth()->user()->role === 'superadmin' ? 6 : 5 }}">
                                        <strong>{{ $kriteria->nama_kriteria }}</strong>
                                    </td>
                                </tr>


                                @forelse ($kriteria->subkriterias as $index => $subkriteria)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subkriteria->nama_subkriteria }}</td>
                                        <td>{{ $subkriteria->batas_bawah ?? '-' }}</td>
                                        <td>{{ $subkriteria->batas_atas ?? '-' }}</td>
                                        <td>{{ $subkriteria->nilai }}</td>
                                        @if (auth()->user()->role === 'superadmin')
                                            <td class="text-center align-middle">

                                                <!-- Tombol Edit -->
                                                <a href="{{ route('data-subkriteria.edit', ['subkriteria' => $subkriteria->id, 'kriteria_id' => request('kriteria_id')]) }}"
                                                    class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>

                                                <!-- Tombol Delete -->
                                                <form method="POST" class="d-inline delete-form"
                                                    data-id="{{ $subkriteria->id }}"
                                                    data-action="{{ route('data-subkriteria.destroy', $subkriteria->id) }}">
                                                    <input type="hidden" name="redirect_kriteria_id"
                                                        value="{{ request('kriteria_id') }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete"
                                                        title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>

                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'superadmin' ? 6 : 5 }}"
                                            class="text-center text-muted">
                                            Belum ada subkriteria untuk kriteria ini.
                                        </td>
                                    </tr>
                                @endforelse
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'superadmin' ? 6 : 5 }}"
                                        class="text-center text-muted">
                                        Data tidak ditemukan.
                                    </td>
                                </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Tambah Data --}}
        <!-- Modal Tambah Subkriteria -->
        <div class="modal fade" id="modalTambahSubkriteria" tabindex="-1" aria-labelledby="modalTambahSubkriteriaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahSubkriteriaLabel">Tambah Data Subkriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <form method="POST" action="{{ route('data-subkriteria.store') }}">
                        @csrf
                        <div class="modal-body">

                            <!-- Kriteria (Relasi) -->
                            <div class="mb-3 row">
                                <label for="kriteria_id" class="col-md-3 col-form-label">Kriteria</label>
                                <div class="col-md-9">
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
                                <label for="nama_subkriteria" class="col-md-3 col-form-label">Nama Subkriteria</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="nama_subkriteria" name="nama_subkriteria"
                                        required />
                                </div>
                            </div>

                            <!-- Batas Bawah -->
                            <div class="mb-3 row">
                                <label for="batas_bawah" class="col-md-3 col-form-label">Batas Bawah</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="batas_bawah" name="batas_bawah"
                                        placeholder="Opsional" />
                                </div>
                            </div>

                            <!-- Batas Atas -->
                            <div class="mb-3 row">
                                <label for="batas_atas" class="col-md-3 col-form-label">Batas Atas</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="batas_atas" name="batas_atas"
                                        placeholder="Opsional" />
                                </div>
                            </div>

                            <!-- Nilai -->
                            <div class="mb-3 row">
                                <label for="nilai" class="col-md-3 col-form-label">Nilai</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="nilai" name="nilai" required />
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endsection

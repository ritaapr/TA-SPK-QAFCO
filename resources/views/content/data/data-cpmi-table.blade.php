@extends('layouts/contentNavbarLayout')

@section('title', 'Data CPMI')

@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')

    <!-- Tambahkan SweetAlert2 di sini -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/assets/js/delete-sweetalert.js')
    @vite('resources/assets/js/success-message.js')

@endsection

@section('content')

   
    <body @if (session('success')) data-success="{{ session('success') }}" @endif
        @if (session('error')) data-error="{{ session('error') }}" @endif>


    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Judul tetap tampil -->
                <h5 class="mb-0">Data CPMI</h5>

                <!-- Filter & Tombol Tambah -->
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ route('data-cpmi-copy') }}" class="mb-0">
                        <select name="tahun" id="tahun" class="form-select" style="width: 150px"
                            onchange="this.form.submit()">
                            <option value="">Periode</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <a href="javascript:void(0)" class="btn rounded-pill btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modalTambahData">
                        <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
                    </a>
                </div>
            </div>


            <!-- Card Body dan Modal tetap di bawah sini -->

            <!-- Modal Tambah Data -->
            <div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="modalTambahDataLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahDataLabel">Tambah Data CPMI</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('data-cpmi-copy.store') }}"
                            enctype="multipart/form-data">
                            <input type="hidden" name="page" value="{{ request('page') }}">
                            @csrf
                            <div class="modal-body">
                                <!-- Nama CPMI -->
                                <div class="mb-3 row">
                                    <label for="namaCpmi" class="col-md-2 col-form-label">Nama CPMI</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="namaCpmi" name="nama_cpmi"
                                            required />
                                    </div>
                                </div>

                                <!-- NIK -->
                                <div class="mb-3 row">
                                    <label for="nik" class="col-md-2 col-form-label">NIK</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="nik" name="nik" required />
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="mb-3 row">
                                    <label for="alamat" class="col-md-2 col-form-label">Alamat</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                    </div>
                                </div>

                                <!-- No HP -->
                                <div class="mb-3 row">
                                    <label for="noHp" class="col-md-2 col-form-label">No. Hp</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="noHp" name="no_hp" required />
                                    </div>
                                </div>

                                <!-- Tanggal Daftar -->
                                <div class="mb-3 row">
                                    <label for="tglDaftar" class="col-md-2 col-form-label">Tanggal Daftar</label>
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" id="tglDaftar" name="tanggal_daftar"
                                            required />
                                    </div>
                                </div>

                                <!-- Upload Foto Profil -->
                                <div class="mb-3 row">
                                    <label for="foto_profil" class="col-md-2 col-form-label">Upload Foto Profil</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" id="foto_profil" name="foto_profil"
                                            accept=".jpg,.jpeg,.png" required />
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- <a href="{{ route('data-cpmi-copy.create') }}" class="btn rounded-pill btn-primary me-6">
                                      <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
                                    </a> -->
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
                            <th>Foto Profil</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cpmis as $index => $cpmi)
                            <tr>
                                <td>{{ $cpmis->firstItem() + $index }}</td>
                                <td>{{ $cpmi->nama_cpmi }}</td>
                                <td>{{ $cpmi->nik }}</td>
                                <td>{{ Str::limit($cpmi->alamat, 30, '...') }}</td>

                                <td>{{ $cpmi->no_hp }}</td>
                                <td>{{ $cpmi->tanggal_daftar }}</td>
                                <td>

                                    @if ($cpmi->foto_profil)
                                        <img src="{{ asset('storage/' . $cpmi->foto_profil) }}" alt="Foto Profil"
                                            width="100">
                                    @else
                                        Tidak ada foto
                                    @endif
                                </td>

                                <td class="text-center align-middle">
                                   

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('data-cpmi-copy.edit', $cpmi->id) }}?page={{ request('page') }}"
                                        class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>


                                    <!-- Tombol Delete -->
                                    <form method="POST" class="d-inline delete-form" data-id="{{ $cpmi->id }}"
                                        data-action="{{ route('data-cpmi-copy.destroy', $cpmi->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="page" value="{{ request('page') }}">
                                        <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete"
                                            title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data tidak tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $cpmis->links() }}
                </div>

            </div>
        </div>
    </div>
    <!--/ Bordered Table -->

@endsection

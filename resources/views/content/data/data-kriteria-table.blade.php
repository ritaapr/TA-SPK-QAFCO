@extends('layouts/contentNavbarLayout')

@section('title', 'Data Kriteria')

@section('page-script')
    @vite('resources/assets/js/form-basic-inputs-copy.js')
    @vite('resources/assets/js/success-message.js')
    <script>
        document.getElementById('hitungsemua').addEventListener('click', function() {
            const rows = document.querySelectorAll('tr[data-id]');
            let totalSkala = 0;
            let data = [];

            // Hitung total skala
            rows.forEach(row => {
                const skala = parseFloat(row.querySelector('.skala-dropdown').value);
                if (!isNaN(skala)) {
                    totalSkala += skala;
                }
            });

            // Hitung bobot per kriteria
            rows.forEach(row => {
                const kriteriaId = row.getAttribute('data-id');
                const skala = parseFloat(row.querySelector('.skala-dropdown').value);
                let bobot = 0;

                if (totalSkala > 0 && !isNaN(skala)) {
                    bobot = skala / totalSkala;
                }

                data.push({
                    id: kriteriaId,
                    skala: skala
                });

                // Tampilkan bobot
                row.querySelector('.bobot-cell').textContent = bobot.toFixed(2);
            });

            // Kirim ke server via AJAX
            fetch('{{ route('kriteria.hitungBobot') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    data
                })
            }).then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Bobot berhasil dihitung dan disimpan!',
                        confirmButtonColor: '#28a745'
                    });


                    // Disable tombol setelah berhasil
                    const button = document.getElementById('hitungsemua');
                    button.disabled = true;
                    button.classList.add('disabled');
                    button.innerHTML = `<span class="tf-icons bx bx-lock me-1"></span> Bobot Terkunci`;
                } else {
                    alert("Gagal menyimpan bobot.");
                }
            }).catch(error => console.error("ERROR:", error));
        });
    </script>
    <script>
        document.getElementById('resetBobot').addEventListener('click', function() {
            Swal.fire({
                title: "Yakin ingin mereset semua bobot?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, reset!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('kriteria.resetBobot') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                        }
                    }).then(response => {
                        if (response.ok) {
                            // Hanya kosongkan kolom bobot
                            document.querySelectorAll('.bobot-cell').forEach(cell => {
                                cell.textContent = '0.00';
                            });

                            // Aktifkan tombol hitung kembali
                            const button = document.getElementById('hitungsemua');
                            button.disabled = false;
                            button.classList.remove('disabled');
                            button.innerHTML =
                                `<span class="tf-icons bx bx-calculator me-1"></span>Hitung`;

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Bobot berhasil direset.',
                                confirmButtonColor: '#28a745'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal mereset bobot.',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    }).catch(error => {
                        console.error("ERROR:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan!',
                            text: 'Silakan coba lagi nanti.',
                            confirmButtonColor: '#dc3545'
                        });
                    });
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/assets/js/delete-sweetalert.js')
@endsection

@section('content')



    {{-- Blade Layout --}}

    <body @if (session('success')) data-success="{{ session('success') }}" @endif
        @if (session('error')) data-error="{{ session('error') }}" @endif>

        <div class="card-xl-6">
            <h6 class="text-muted">Informasi</h6>
            <div class="nav-align-top mb-6">
                <ul class="nav nav-pills mb-4 nav-fill" role="tablist">
                    <li class="nav-item mb-1 mb-sm-0">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home"
                            aria-selected="true"><span class="d-none d-sm-block"><i
                                    class="tf-icons bx bx-info-circle me-1 align-text-bottom"></i>Penjelasan Skala</span><i
                                class="bx bx-home bx-sm d-sm-none"></i></button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-messages" aria-controls="navs-pills-justified-messages"
                            aria-selected="false"><span class="d-none d-sm-block"><i
                                    class="tf-icons bx bx-calculator me-1 align-text-bottom"></i>Hitung Bobot</span><i
                                class="bx bx-message-square bx-sm d-sm-none"></i></button>
                    </li>
                    <li class="nav-item mb-1 mb-sm-0">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile"
                            aria-selected="false"><span class="d-none d-sm-block"><i
                                    class="tf-icons bx bx-reset me-1 align-text-bottom"></i>Reset Bobot</span><i
                                class="bx bx-user bx-sm d-sm-none"></i></button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navs-pills-justified-home" role="tabpanel">
                        <p>Berikut penjelasan skala 1–5 untuk kriteria:</p>
                        <ul>
                            <li><strong>1</strong> – Sangat Rendah</li>
                            <li><strong>2</strong> – Rendah</li>
                            <li><strong>3</strong> – Cukup</li>
                            <li><strong>4</strong> – Tinggi</li>
                            <li><strong>5</strong> – Sangat Tinggi</li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">
                        <p>Silakan tekan tombol <strong>Reset Bobot</strong> untuk menghitung kembali bobot berdasarkan
                            skala yang dipilih.
                        </p>
                    </div>
                    <div class="tab-pane fade" id="navs-pills-justified-messages" role="tabpanel">
                        <p>Silakan tekan tombol <strong>Hitung</strong> untuk memproses bobot berdasarkan skala yang
                            dipilih.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bordered Table -->
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header mb-0">Data Kriteria</h5>
                @if (auth()->user()->role === 'superadmin')
                    <a href="javascript:void(0);" class="btn rounded-pill btn-primary me-6" data-bs-toggle="modal"
                        data-bs-target="#modalTambahKriteria">
                        <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
                    </a>
                @endif



            </div>
            <div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-labelledby="modalTambahKriteriaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahKriteriaLabel">Tambah Data Kriteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form method="POST" action="{{ route('data-kriteria.store') }}">
                            @csrf
                            <div class="modal-body">

                                <!-- Kode Kriteria -->
                                <div class="mb-3 row">
                                    <label for="kodeKriteria" class="col-md-3 col-form-label">Kode Kriteria</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="kodeKriteria" name="kode_kriteria"
                                            placeholder="Masukkan Kode Kriteria (contoh: K01)" required />
                                    </div>
                                </div>

                                <!-- Nama Kriteria -->
                                <div class="mb-3 row">
                                    <label for="namaKriteria" class="col-md-3 col-form-label">Nama Kriteria</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="namaKriteria" name="nama_kriteria"
                                            placeholder="Masukkan Nama Kriteria" required />
                                    </div>
                                </div>

                                <!-- Jenis -->
                                <div class="mb-3 row">
                                    <label for="jenis" class="col-md-3 col-form-label">Jenis</label>
                                    <div class="col-md-9">
                                        <select class="form-select" id="jenis" name="jenis" required>
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="benefit">Benefit</option>
                                            <option value="cost">Cost</option>
                                        </select>
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

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th>Jenis</th>
                                <th>Skala</th>
                                <th>Bobot</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kriterias as $index => $kriteria)
                                <tr data-id="{{ $kriteria->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $kriteria->kode_kriteria }}</td>
                                    <td>{{ $kriteria->nama_kriteria }}</td>
                                    <td>{{ ucfirst($kriteria->jenis) }}</td>
                                    <td>
                                        <select class="form-select skala-dropdown" {{ $bobotTerkunci ? 'disabled' : '' }}>
                                            <option value="">-- Pilih Skala --</option>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}"
                                                    {{ $kriteria->skala == $i ? 'selected' : '' }}>{{ $i }}
                                                </option>
                                            @endfor
                                        </select>

                                    </td>
                                    <td class="bobot-cell" id="bobot-{{ $kriteria->id }}">
                                        {{ number_format($kriteria->bobot, 2) }}
                                    </td>


                                    <td class="text-center align-middle">

                                        <!-- Tombol Detail -->
                                        <button type="button" class="btn btn-sm btn-icon btn-info me-1 btn-detail"
                                            data-id="{{ $kriteria->id }}" data-nama="{{ $kriteria->nama_kriteria }}"
                                            data-kode="{{ $kriteria->kode_kriteria }}"
                                            data-jenis="{{ $kriteria->jenis }}" data-bobot="{{ $kriteria->bobot }}"
                                            data-bs-toggle="modal" data-bs-target="#modalDetailKriteria">
                                            <i class="bx bx-show"></i>
                                        </button>

                                        @if (auth()->user()->role === 'superadmin')
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('data-kriteria.edit', $kriteria->id) }}"
                                            class="btn btn-sm btn-icon btn-primary me-1" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <form method="POST" class="d-inline delete-form" data-id="{{ $kriteria->id }}"
                                            data-action="{{ route('data-kriteria.destroy', $kriteria->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete"
                                                title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Data tidak tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Modal Detail -->
                    <div class="modal fade" id="modalDetailKriteria" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header ">
                                    <h5 class="modal-title"></i>Detail Kriteria & Subkriteria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-semibold">Kode Kriteria</div>
                                            <div class="col-sm-8" id="detail-kode">-</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-semibold">Nama Kriteria</div>
                                            <div class="col-sm-8" id="detail-nama">-</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-semibold">Jenis</div>
                                            <div class="col-sm-8">
                                                <span id="detail-jenis" class="badge bg-label-info">-</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 fw-semibold">Bobot</div>
                                            <div class="col-sm-8">
                                                <span id="detail-bobot" class="badge bg-label-warning">0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <h6 class="fw-bold mb-2"><i class="bx bx-list-ul me-1"></i>Daftar Subkriteria</h6>
                                    <table class="table table-bordered table-sm" id="subkriteria-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Subkriteria</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subkriteria-list">
                                            <tr>
                                                <td colspan="2">Memuat...</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            @if (auth()->user()->role === 'superadmin')
            <button type="button" id="hitungsemua" class="btn btn-outline-primary"
                {{ $bobotTerkunci ? 'disabled class=disabled' : '' }}>
                <span class="tf-icons bx {{ $bobotTerkunci ? 'bx-lock' : 'bx-calculator' }} me-1"></span>
                {{ $bobotTerkunci ? 'Bobot Terkunci' : 'Hitung' }}
            </button>

            <button type="button" id="resetBobot" class="btn btn-outline-danger mt-2 ms-2">
                <span class="tf-icons bx bx-reset me-1"></span> Reset Bobot
            </button>
            @endif

        </div>

        <script>
            document.querySelectorAll('.btn-detail').forEach(button => {
                button.addEventListener('click', async function() {
                    const id = this.dataset.id;

                    document.getElementById('detail-kode').textContent = this.dataset.kode;
                    document.getElementById('detail-nama').textContent = this.dataset.nama;
                    const jenisEl = document.getElementById('detail-jenis');
                    const jenis = this.dataset.jenis;

                    jenisEl.textContent = jenis;

                    // Hapus semua class badge warna sebelumnya
                    jenisEl.classList.remove('bg-label-info', 'bg-label-success', 'bg-label-primary',
                        'bg-label-danger');

                    // Tambahkan class sesuai jenis
                    if (jenis === 'benefit') {
                        jenisEl.classList.add('bg-label-success'); // hijau untuk benefit
                    } else if (jenis === 'cost') {
                        jenisEl.classList.add('bg-label-danger'); // merah untuk cost
                    }

                    document.getElementById('detail-bobot').textContent = parseFloat(this.dataset.bobot)
                        .toFixed(2);

                    const subList = document.getElementById('subkriteria-list');
                    subList.innerHTML = '<tr><td colspan="2">Memuat...</td></tr>';

                    try {
                        const response = await fetch(`/kriteria/${id}/subkriteria`);
                        const data = await response.json();

                        if (data.length > 0) {
                            subList.innerHTML = '';
                            data.forEach(sub => {
                                subList.innerHTML += `
                            <tr>
                            <td>${sub.nama_subkriteria}</td>
                            <td>${sub.nilai}</td>
                            </tr>`;
                            });
                        } else {
                            subList.innerHTML = '<tr><td colspan="2">Tidak ada subkriteria.</td></tr>';
                        }
                    } catch (error) {
                        subList.innerHTML = '<tr><td colspan="2">Gagal memuat data.</td></tr>';
                    }

                });
            });
        </script>


    @endsection

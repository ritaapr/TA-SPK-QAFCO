@extends('layouts/contentNavbarLayout')

@section('title', 'Form Penilaian CPMI')
@section('page-script')
    @vite('resources/assets/js/success-message.js')
    @vite('resources/assets/js/delete-sweetalert.js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('content')

    <body @if (session('success')) data-success="{{ session('success') }}" @endif
        @if (session('error')) data-error="{{ session('error') }}" @endif>


        <div class="card">
            <div class="card-header">
                <h5>Daftar CPMI & Status Penilaian</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama CPMI</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($cpmiList as $cpmi)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $cpmi->nama_cpmi }}</td>
                                <td>{{ $cpmi->tanggal_daftar }}</td>
                                <td>
                                    @if ($cpmi->penilaian->isNotEmpty())
                                        <span class="badge bg-success">Sudah Dinilai (sementara)</span>
                                    @else
                                        <span class="badge bg-warning">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($cpmi->penilaian->isNotEmpty())
                                        <!-- Edit-->
                                        <button type="button" class="btn btn-sm btn-icon btn-primary me-1 btn-open-modal"
                                            title="Edit Penilaian" data-mode="edit" data-cpmi-id="{{ $cpmi->id }}"
                                            data-cpmi-nama="{{ $cpmi->nama_cpmi }}"
                                            @foreach ($kriteriaList as $kriteria)
        @php
            $pen = $cpmi->penilaian->firstWhere('kriteria_id', $kriteria->id);
            $isRange = $kriteria->subkriterias->first()?->batas_bawah !== null;
            $value = $isRange ? $pen?->nilai_input : $pen?->subkriteria_id;
        @endphp
        data-kriteria-{{ $kriteria->id }}="{{ $value }}" @endforeach>
                                            <i class="bx bx-edit-alt"></i>
                                        </button>

                                        <!-- Hapus -->
                                        <form method="POST" class="d-inline delete-form"
                                            action="{{ route('penilaian.destroy', $cpmi->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete"
                                                title="Hapus Penilaian">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Tambah -->
                                        <button type="button" class="btn btn-sm btn-icon btn-success btn-open-modal"
                                            title="Tambah Penilaian" data-mode="create" data-cpmi-id="{{ $cpmi->id }}"
                                            data-cpmi-nama="{{ $cpmi->nama_cpmi }}"
                                            @foreach ($kriteriaList as $kriteria)
                    data-kriteria-{{ $kriteria->id }}="" @endforeach>
                                            <i class="bx bx-plus"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>


        <!-- Modal Penilaian -->
        <div class="modal fade" id="modalPenilaian" tabindex="-1" aria-labelledby="modalPenilaianLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" id="penilaianForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPenilaianLabel">Form Penilaian CPMI</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2">
                                <label for="cpmi_nama" class="form-label">Nama CPMI</label>
                                <input type="text" id="cpmi_nama" class="form-control" readonly>
                                <input type="hidden" name="cpmi_id" id="cpmi_id">
                            </div>
                            <div class="row">
                                @foreach ($kriteriaList as $kriteria)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ $kriteria->nama_kriteria }}</label>

                                        @php
                                            $hasRange =
                                                $kriteria->subkriterias->first()?->batas_bawah !== null &&
                                                $kriteria->subkriterias->first()?->batas_atas !== null;
                                        @endphp

                                        @if ($hasRange)
                                            <input type="number" name="penilaian[{{ $kriteria->id }}]"
                                                id="kriteria_{{ $kriteria->id }}" class="form-control" step="any"
                                                required placeholder="Masukkan nilai">
                                        @else
                                            <select name="kriteria[{{ $kriteria->id }}]" class="form-select" required>
                                                <option value="">Pilih Nilai</option>
                                                @foreach ($kriteria->subkriterias as $sub)
                                                    <option value="{{ $sub->id }}">{{ $sub->nama_subkriteria }}
                                                        ({{ $sub->nilai }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif

                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitButton">Simpan Penilaian</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <script>
            // Persiapan filter table status
            document.getElementById('filter-penilaian').addEventListener('change', function() {
                const filter = this.value;
                const rows = document.querySelectorAll('#cpmi-table tr');
                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    row.style.display = (filter === 'semua' || filter === status) ? '' : 'none';
                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('modalPenilaian'));
                const form = document.getElementById('penilaianForm');
                const cpmiIdInput = document.getElementById('cpmi_id');
                const cpmiNamaInput = document.getElementById('cpmi_nama');
                const methodInput = document.getElementById('formMethod');
                const submitButton = document.getElementById('submitButton');

                const kriteriaIds = @json($kriteriaList->pluck('id'));

                document.querySelectorAll('.btn-open-modal').forEach(button => {
                    button.addEventListener('click', () => {
                        const mode = button.getAttribute('data-mode');
                        const cpmiId = button.getAttribute('data-cpmi-id');
                        const cpmiNama = button.getAttribute('data-cpmi-nama');

                        cpmiNamaInput.value = cpmiNama;
                        cpmiIdInput.value = cpmiId;

                        if (mode === 'edit') {
                            form.action = `/penilaian/${cpmiId}`;
                            methodInput.value = 'PUT';
                            submitButton.textContent = 'Update Penilaian';
                        } else {
                            form.action = `{{ route('penilaian.store') }}`;
                            methodInput.value = 'POST';
                            submitButton.textContent = 'Simpan Penilaian';
                        }

                        // Set nilai dropdown
                        kriteriaIds.forEach(id => {
                            const value = button.getAttribute('data-kriteria-' + id);

                            // Cek apakah elemen input range
                            const input = document.getElementById('kriteria_' + id);
                            if (input) {
                                input.value = value || '';
                            } else {
                                const select = form.querySelector(
                                    `select[name="kriteria[${id}]"]`);
                                if (select) {
                                    select.value = value || '';
                                }
                            }

                        });


                        modal.show();
                    });
                });
            });
        </script>




    @endsection

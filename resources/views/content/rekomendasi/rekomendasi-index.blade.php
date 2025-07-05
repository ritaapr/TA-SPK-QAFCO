@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar CPMI Terpilih')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .btn-fixed {
            width: 120px;
            /* atau sesuaikan misalnya 110 / 130 */
            height: 40px;
            padding: 6px 10px !important;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
    </style>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Daftar CPMI yang Direkomendasikan</h5>
            <div class="d-flex gap-2 mt-2 mt-sm-0">
                <!-- Tombol Preview -->
                <button class="btn rounded-pill btn-outline-info btn-fixed" onclick="previewPDF()">
                    <i class="bx bx-show"></i> Preview
                </button>

                <!-- Tombol Download -->
                <a href="{{ route('rekomendasi.export-pdf', ['download' => 'true']) }}"
                    class="btn rounded-pill btn-outline-success btn-fixed">
                    <i class="bx bx-download"></i> Unduh
                </a>

                <!-- Tombol Reset dengan id -->
<form id="formResetRekomendasi" action="{{ route('rekomendasi.arsipkan') }}" method="POST">
    @csrf
    <button type="submit" class="btn rounded-pill btn-outline-danger btn-fixed">
        <i class="bx bx-trash"></i> Reset
    </button>
</form>



            </div>
        </div>

        <!-- Modal Preview PDF -->
        <div class="modal fade" id="pdfModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Laporan Rekomendasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfFrame" src="" width="100%" height="600px"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($data->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama CPMI</th>
                            <th>No Hp</th>
                            <th>Alamat</th>
                            <th>Tanggal Direkomendasikan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $rekomendasi)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rekomendasi->cpmi->nama_cpmi }}</td>
                                <td>{{ $rekomendasi->cpmi->no_hp }}</td>
                                <td>{{ $rekomendasi->cpmi->alamat }}</td>
                                <td>
                                    {{ $rekomendasi->created_at?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-info" data-bs-toggle="collapse"
                                        data-bs-target="#detail-{{ $rekomendasi->id }}">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            <tr id="detail-{{ $rekomendasi->id }}" class="collapse bg-light">
                                <td colspan="6">
                                    <ul class="mb-0">
                                        @if ($rekomendasi->cpmi && $rekomendasi->cpmi->penilaianHistori)
                                            @foreach ($rekomendasi->cpmi->penilaianHistori as $histori)
                                                <li>
                                                    <strong>{{ $histori->kriteria->nama_kriteria ?? 'N/A' }}:</strong>
                                                    {{ $histori->subkriteria->nama_subkriteria ?? '-' }}
                                                    ({{ $histori->nilai }})
                                                </li>
                                            @endforeach
                                        @else
                                            <li>Belum ada histori penilaian.</li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">Belum ada CPMI yang dipilih.</div>
            @endif
        </div>
    </div>
@endsection

<!-- Script -->
<script>
    function previewPDF() {
        document.getElementById('pdfFrame').src = "{{ route('rekomendasi.export-pdf') }}";
        var modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formResetRekomendasi');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Cegah submit langsung

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-outline-secondary'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Yakin ingin reset semua rekomendasi?",
                text: "Semua data rekomendasi akan diarsipkan dan dikosongkan untuk seleksi berikutnya.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, reset!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Lanjutkan submit
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Dibatalkan",
                        text: "Data rekomendasi tidak jadi direset.",
                        icon: "info"
                    });
                }
            });
        });
    });
</script>

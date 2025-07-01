@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar CPMI Terpilih')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0">Daftar CPMI yang Direkomendasikan</h5>
            <div class="d-flex gap-2 mt-2 mt-sm-0">
                <!-- Tombol Preview -->
                <button class="btn btn-outline-info" onclick="previewPDF()">
                    <i class="bx bx-show"></i> Preview PDF
                </button>
                <!-- Tombol Download -->
                <a href="{{ route('rekomendasi.export-pdf', ['download' => 'true']) }}" class="btn btn-outline-success">
                    <i class="bx bx-download"></i> Unduh PDF
                </a>
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
                            <th>Nilai SAW</th>
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
                                    {{ number_format(optional($rekomendasi->histori->firstWhere('nilai_saw', '!=', null))?->nilai_saw ?? 0, 4) }}
                                </td>
                                <td>{{ $rekomendasi->created_at->format('d M Y') }}</td>
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
                                        @foreach ($rekomendasi->histori as $histori)
                                            <li>
                                                <strong>{{ $histori->kriteria->nama_kriteria }}:</strong>
                                                {{ $histori->subkriteria->nama_subkriteria ?? '-' }}
                                                ({{ $histori->nilai }})
                                            </li>
                                        @endforeach
                                        <li><strong>Nilai SAW:</strong>
                                            {{ number_format(optional($rekomendasi->histori->firstWhere('nilai_saw', '!=', null))?->nilai_saw ?? 0, 4) }}
                                        </li>
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

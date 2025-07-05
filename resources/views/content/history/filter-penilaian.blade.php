@extends('layouts/contentNavbarLayout')

@section('title', 'Seleksi CPMI')

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/assets/js/rekomendasi-sweetalert.js')
@vite('resources/assets/js/success-message.js')
@endsection

@section('content')

    <body @if (session('success')) data-success="{{ session('success') }}" @endif
        @if (session('error')) data-error="{{ session('error') }}" @endif>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <!-- Judul -->
                    <h5 class="mb-0">Seleksi CPMI berdasarkan Subkriteria</h5>

                    <!-- Filter Form -->
                    <form method="POST" action="#" id="filter-form" class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="d-flex gap-2 filter-row">
                            <select name="filters[0][kriteria_id]" class="form-select kriteria-select" required
                                style="min-width: 200px">
                                <option value="">Pilih Kriteria</option>
                                @foreach ($kriteriaList as $kriteria)
                                    <option value="{{ $kriteria->id }}">{{ $kriteria->nama_kriteria }}</option>
                                @endforeach
                            </select>

                            <select name="filters[0][subkriteria_id]" class="form-select subkriteria-select" required
                                style="min-width: 200px">
                                <option value="">Pilih Subkriteria</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body">
                {{-- Tabel Hasil Filter --}}
                <div id="result-penilaian" class="mt-3"></div>

                {{-- Tabel Default Ranking --}}
                @if (!empty($hasilRankingDefault))
                    <div id="default-ranking" class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Hasil Ranking SAW (Seluruh CPMI)</h6>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ranking</th>
                                            <th>Nama CPMI</th>
                                            <th>Nilai SAW</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hasilRankingDefault as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item['cpmi']->nama_cpmi }}</td>
                                                <td>{{ $item['nilai'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </body>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let index = 1;

        function fetchTable() {
            const rows = document.querySelectorAll('.filter-row');
            const filters = [];

            rows.forEach((row, i) => {
                const kriteria = row.querySelector('.kriteria-select').value;
                const subkriteria = row.querySelector('.subkriteria-select').value;
                if (kriteria && subkriteria) {
                    filters.push({
                        kriteria_id: kriteria,
                        subkriteria_id: subkriteria
                    });
                }
            });

            fetch(`{{ route('hasilpenilaian.ajaxFilter') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        filters
                    })
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('result-penilaian').innerHTML = data.html;
                    if (typeof window.bindRekomendasiButtons === 'function') {
                        window.bindRekomendasiButtons();
                    }
                });
        }

        function bindKriteriaChange(row) {
            row.querySelector('.kriteria-select').addEventListener('change', async function() {
                const kriteriaId = this.value;
                const subSelect = row.querySelector('.subkriteria-select');
                subSelect.innerHTML = '<option value="">Memuat...</option>';

                const response = await fetch(`/get-subkriteria/${kriteriaId}`);
                const subkriterias = await response.json();

                subSelect.innerHTML = '<option value="">Pilih Subkriteria</option>';
                subkriterias.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.nama_subkriteria;
                    subSelect.appendChild(opt);
                });

                subSelect.addEventListener('change', fetchTable);
            });
        }


        bindKriteriaChange(document.querySelector('.filter-row'));

    });

    document.querySelector('#filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        fetchTable();
    });
</script>

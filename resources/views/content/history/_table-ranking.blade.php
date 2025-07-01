@if (count($hasil) > 0)
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Hasil Ranking SAW (Terfilter)</h6>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ranking</th>
                        <th>Nama CPMI</th>
                        <th>Nilai SAW</th>
                        <th>Aksi</th> {{-- Tambahkan kolom aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasil as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['cpmi']->nama_cpmi }}</td>
                            <td>{{ $item['nilai'] }}</td>
                            <td>
                                <form method="POST" class="form-rekomendasi"
                                    data-action="{{ route('rekomendasi.store') }}">
                                    @csrf
                                    <input type="hidden" name="cpmi_id" value="{{ $item['cpmi']->id }}">
                                    <button type="button" class="btn btn-sm btn-primary btn-pilih">Pilih</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-warning">Tidak ada data yang sesuai dengan filter.</div>
@endif

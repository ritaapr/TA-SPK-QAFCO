@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Histori Penilaian')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Detail Penilaian untuk {{ $cpmi->nama_cpmi }}</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
    <tr>
        <th>Kriteria</th>
        <th>Subkriteria</th>
        <th>Nilai</th>
        <th>Penilai</th> {{-- Tambahan --}}
        <th>Waktu</th>
    </tr>
</thead>
<tbody>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->kriteria->nama_kriteria }}</td>
            <td>{{ $item->subkriteria->nama_subkriteria }}</td>
            <td>{{ $item->nilai }}</td>
            <td>{{ $item->user->name ?? '-' }}</td> {{-- Nama User --}}
            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    @endforeach
</tbody>

        </table>
    </div>
</div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Penilaian')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Histori Penilaian CPMI</h5>
    </div>
    <div class="card-body table-responsive">
        @if ($data->isEmpty())
    <div class="alert alert-info">Belum ada riwayat penilaian disimpan.</div>
@else
    <table class="table table-bordered">
           <thead>
    <tr>
        <th>CPMI</th>
        <th>Penilai</th> {{-- Tambahan --}}
        <th>Tanggal Direkomendasikan</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    @foreach ($data as $cpmiId => $items)
        @php $firstItem = $items->first(); @endphp
        <tr>
            <td>{{ $firstItem->cpmi->nama_cpmi }}</td>
            <td>{{ $firstItem->user->name ?? '-' }}</td> {{-- Penilai --}}
            <td>{{ $firstItem->created_at->format('d-m-Y H:i') }}</td>
            <td class="text-center align-middle">
                <a href="{{ route('rekomendasi.histori.detail', $cpmiId) }}" class="btn btn-sm btn-info">Lihat Detail</a>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>
@endif
        
    </div>
</div>
@endsection

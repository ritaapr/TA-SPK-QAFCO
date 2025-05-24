@extends('layouts/contentNavbarLayout')

@section('title', 'Form Penilaian CPMI')

@section('content')

@if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif


  {{-- Matriks Nilai Asli --}}
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Matriks Alternatif (Nilai Asli)</h5>
      <a href="{{ route('penilaian.export-pdf') }}" class="btn btn-sm btn-danger">Export PDF</a>
    </div>

    
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nama CPMI</th>
              @foreach ($kriteriaList as $k)
                <th>{{ $k->nama_kriteria }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($cpmiList as $cpmi)
              <tr>
                <td>{{ $cpmi->nama_cpmi }}</td>
                @foreach ($kriteriaList as $k)
                  <td>{{ $matrix[$cpmi->id][$k->id] }}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Matriks Normalisasi --}}
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Matriks Normalisasi</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nama CPMI</th>
              @foreach ($kriteriaList as $k)
                <th>{{ $k->nama_kriteria }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($cpmiList as $cpmi)
              <tr>
                <td>{{ $cpmi->nama_cpmi }}</td>
                @foreach ($kriteriaList as $k)
                  <td>{{ number_format($normalisasi[$cpmi->id][$k->id], 3) }}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Matriks Terbobot --}}
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Matriks Terbobot</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nama CPMI</th>
              @foreach ($kriteriaList as $k)
                <th>{{ $k->nama_kriteria }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($cpmiList as $cpmi)
              <tr>
                <td>{{ $cpmi->nama_cpmi }}</td>
                @foreach ($kriteriaList as $k)
                  <td>{{ number_format($terbobot[$cpmi->id][$k->id], 3) }}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Ranking Akhir --}}
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="mb-0">Ranking Akhir</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Ranking</th>
              <th>Nama CPMI</th>
              <th>Nilai Akhir</th>
            </tr>
          </thead>
          <tbody>
            @php $rank = 1; @endphp
            @foreach ($ranking as $cpmi_id => $nilai)
              <tr>
                <td>{{ $rank++ }}</td>
                <td>{{ $cpmiList->firstWhere('id', $cpmi_id)->nama_cpmi }}</td>
                <td>{{ number_format($nilai, 4) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>


@endsection

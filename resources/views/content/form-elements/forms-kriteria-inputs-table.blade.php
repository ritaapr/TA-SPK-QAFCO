@extends('layouts/contentNavbarLayout')

@section('title', 'Data Kriteria')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs-copy.js')
<script>
  document.getElementById('hitungsemua').addEventListener('click', function() {
    const rows = document.querySelectorAll('tr[data-id]');
    let totalSkala = 0;
    let data = [];

    // Hitung total semua skala dulu
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


      // Tampilkan di kolom bobot (tanpa reload)
      row.querySelector('.bobot-cell').textContent = bobot.toFixed(2);
    });

    // Kirim ke server via AJAX
    fetch('{{ route("kriteria.hitungBobot") }}', {
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
        alert("Bobot berhasil dihitung dan disimpan!");
      } else {
        alert("Gagal menyimpan bobot.");
      }
    }).catch(error => console.error("ERROR:", error));
  });
</script>

@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card-xl-6">
  <h6 class="text-muted">Informasi</h6>
  <div class="nav-align-top mb-6">
    <ul class="nav nav-pills mb-4 nav-fill" role="tablist">
      <li class="nav-item mb-1 mb-sm-0">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home" aria-selected="true"><span class="d-none d-sm-block"><i class="tf-icons bx bx-info-circle me-1 align-text-bottom"></i>Penjelasan Skala</span><i class="bx bx-home bx-sm d-sm-none"></i></button>
      </li>
      <li class="nav-item mb-1 mb-sm-0">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile" aria-selected="false"><span class="d-none d-sm-block"><i class="tf-icons bx bx-list-ul me-1 align-text-bottom"></i>Data Kriteria</span><i class="bx bx-user bx-sm d-sm-none"></i></button>
      </li>
      <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-justified-messages" aria-controls="navs-pills-justified-messages" aria-selected="false"><span class="d-none d-sm-block"><i class="tf-icons bx bx-calculator me-1 align-text-bottom"></i>Hitung Bobot</span><i class="bx bx-message-square bx-sm d-sm-none"></i></button>
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
        <p>Data kriteria akan muncul di tabel di bawah ini.</p>
      </div>
      <div class="tab-pane fade" id="navs-pills-justified-messages" role="tabpanel">
        <p>Silakan tekan tombol <strong>Hitung</strong> untuk memproses bobot berdasarkan skala yang dipilih.</p>
      </div>
    </div>
  </div>
</div>



<!-- Bordered Table -->
<div class="card">
  <div class="d-flex justify-content-between align-items-center">
    <h5 class="card-header mb-0">Data Kriteria</h5>
    <a href="{{ route('forms-kriteria-inputs.create') }}" class="btn rounded-pill btn-primary me-6">
      <span class="tf-icons bx bx-plus bx-18px me-2"></span>Tambah Data
    </a>
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
            <th>Skala
              <button type="button" class="btn btn-sm btn-outline-info p-0 ms-1" data-bs-toggle="modal" data-bs-target="#infoSkalaModal" title="Info Skala">
                <i class="bx bx-info-circle"></i>
              </button>
            </th>
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
              <select class="form-select skala-dropdown">
                <option value="">-- Pilih Skala --</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </td>
            <td class="bobot-cell" id="bobot-{{ $kriteria->id }}">
              {{ number_format($kriteria->bobot, 2) }}
            </td>
            <td>

              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('forms-kriteria-inputs.edit', $kriteria->id) }}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <form action="{{ route('forms-kriteria-inputs.destroy', $kriteria->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item">
                      <i class="bx bx-trash me-1"></i> Delete
                    </button>
                  </form>

                </div>
              </div>
            </td>

          </tr>

          @empty
          <tr>
            <td colspan="7" class="text-center">Data tidak tersedia.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
  

    </div>
    
  </div>
  <button type="button" id="hitungsemua" class="btn btn-outline-primary">
                <span class="tf-icons bx bx-calculator me-1"></span>Hitung
              </button>
</div>
<!-- Modal Penjelasan Skala -->
<div class="modal fade" id="infoSkalaModal" tabindex="-1" aria-labelledby="infoSkalaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoSkalaModalLabel">Penjelasan Skala Kriteria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><strong>1</strong> - Sangat Rendah</li>
          <li class="list-group-item"><strong>2</strong> - Rendah</li>
          <li class="list-group-item"><strong>3</strong> - Cukup</li>
          <li class="list-group-item"><strong>4</strong> - Tinggi</li>
          <li class="list-group-item"><strong>5</strong> - Sangat Tinggi</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!--/ Bordered Table -->



@endsection
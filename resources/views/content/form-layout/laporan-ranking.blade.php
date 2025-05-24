<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil SAW CPMI</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        .no-border { border: none; }
        .header-info p { margin: 2px 0; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    {{-- Header / Logo --}}
    <table class="no-border" style="margin-bottom: 10px;">
        <tr class="no-border">
            <td class="no-border" style="width: 80px;">
                <img src="{{ public_path('logo.png') }}" width="60">
            </td>
            <td class="no-border">
                <h2 style="margin: 0;">PT QAFCO MADIUN</h2>
                <p style="margin: 0;">Laporan Hasil Penilaian dan Perangkingan CPMI</p>
            </td>
        </tr>
    </table>

    {{-- Info Umum --}}
    <div class="header-info">
        <p><strong>Periode:</strong> Mei 2025</p>
        <p><strong>Metode:</strong> Simple Additive Weighting (SAW)</p>
        <p><strong>Jumlah Kandidat:</strong> {{ $cpmiList->count() }}</p>
        <p><strong>Jumlah Kriteria:</strong> {{ count($kriteriaList ?? []) }}</p>
    </div>

    {{-- Tabel Kriteria --}}
    <h3>Daftar Kriteria</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Kriteria</th>
                <th>Jenis</th>
                <th>Bobot</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kriteriaList as $kriteria)
                <tr>
                    <td>{{ $kriteria->nama_kriteria }}</td>
                    <td>{{ ucfirst($kriteria->jenis) }}</td>
                    <td>{{ $kriteria->bobot }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tabel Ranking --}}
    <h3>Hasil Perangkingan CPMI</h3>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama CPMI</th>
                <th>Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 1; @endphp
            @foreach ($ranking as $cpmiId => $total)
                @php $cpmi = $cpmiList->firstWhere('id', $cpmiId); @endphp
                <tr>
                    <td>{{ $rank++ }}</td>
                    <td>{{ $cpmi->nama_cpmi }}</td>
                    <td>{{ number_format($total, 4) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Kesimpulan --}}
    <h3>Kesimpulan</h3>
    @php
        $terbaikId = array_key_first($ranking);
        $terbaik = $cpmiList->firstWhere('id', $terbaikId);
        $nilaiTertinggi = reset($ranking);
    @endphp
    <p>
        Berdasarkan hasil perangkingan menggunakan metode SAW, kandidat terbaik adalah
        <strong>{{ $terbaik->nama_cpmi }}</strong> dengan total nilai
        <strong>{{ number_format($nilaiTertinggi, 4) }}</strong>.
    </p>

    <p><i>Catatan: Nilai di atas dihitung berdasarkan bobot dan jenis kriteria yang telah ditetapkan oleh PT QAFCO.</i></p>

    {{-- Tanggal dan Tanda Tangan --}}
    <br><br>
    <div style="width: 40%; float: right; text-align: center;">
        <p>Madiun, {{ now()->format('d F Y') }}</p>
        <p>Manajer HRD PT QAFCO</p>
        <br><br><br>
        <p><strong>(__________________________)</strong></p>
    </div>

    <div style="clear: both;"></div>

    {{-- Timestamp cetak --}}
    <p style="font-size: 10px; text-align: right;"><i>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</i></p>
</body>
</html>

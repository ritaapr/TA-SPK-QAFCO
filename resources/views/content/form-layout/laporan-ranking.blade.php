<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian Kelayakan & Prioritas CPMI</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        .no-border {
            border: none;
        }

        .header-info p {
            margin: 2px 0;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- Header / Logo --}}
    <table class="no-border" style="margin-bottom: 0;">
        <tr class="no-border">
            <td class="no-border" style="width: 80px;">
                <img src="{{ public_path('assets/img/favicon/logo.png') }}" width="60">

            </td>
            <td class="no-border">
                <h2 style="margin: 0;">PT QAFCO MADIUN</h2>
                <p style="margin: 0;">Jl. Raya Jogodayuh, RT.1/RW.1, Dakon, Jogodayuh,</p>
                <p style="margin: 0;">Kec. Geger, Kabupaten Madiun, Jawa Timur 63171</p>
            </td>
        </tr>
    </table>

    <hr style="border: 2px solid black; margin-top: 10px; margin-bottom: 20px;">

    {{-- Judul --}}
    <h3 style="text-align: center; margin-top: 0; margin-bottom: 20px;">
        Laporan Penilaian Kelayakan dan Prioritas Keberangkatan CPMI
    </h3>

    {{-- Deskripsi --}}
    <p>
        Laporan ini menyajikan hasil penilaian terhadap Calon Pekerja Migran Indonesia (CPMI) di PT QAFCO Madiun. Penilaian dilakukan berdasarkan sejumlah kriteria yang telah ditentukan oleh perusahaan menggunakan metode <strong>Simple Additive Weighting (SAW)</strong>. Hasil akhir berupa perangkingan digunakan untuk menentukan kelayakan dan prioritas keberangkatan tiap CPMI.
    </p>

    {{-- Info Umum --}}
    <div class="header-info">
        <p><strong>Periode:</strong> Mei 2025</p>
        <p><strong>Jumlah Kandidat:</strong> {{ $cpmiList->count() }}</p>
    </div>

    {{-- Tabel Ranking --}}
    <h3>Hasil Perangkingan Kelayakan CPMI</h3>
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

    <p><i>Catatan: Peringkat menunjukkan urutan kelayakan dan prioritas keberangkatan CPMI berdasarkan hasil perhitungan nilai akhir.</i></p>

    {{-- Kesimpulan --}}
    <h3>Kesimpulan</h3>
    @php
    $terbaikId = array_key_first($ranking);
    $terbaik = $cpmiList->firstWhere('id', $terbaikId);
    $nilaiTertinggi = reset($ranking);
    @endphp
    <p>
        Berdasarkan hasil penilaian menggunakan metode SAW, kandidat dengan kelayakan dan prioritas keberangkatan tertinggi adalah <strong>{{ $terbaik->nama_cpmi }}</strong> dengan total nilai <strong>{{ number_format($nilaiTertinggi, 4) }}</strong>. Nilai ini diperoleh dari akumulasi bobot kriteria seperti usia, pendidikan, pengalaman, dan aspek lainnya yang relevan dengan kebutuhan penempatan kerja luar negeri.
    </p>

    <p><i>Catatan: Seluruh data dan penilaian bersumber dari sistem berbasis web yang telah diimplementasikan di lingkungan PT QAFCO Madiun.</i></p>

    {{-- Tanggal dan Tanda Tangan --}}
    <br><br>
    <div style="width: 40%; float: right; text-align: center;">
        <p>Madiun, {{ now()->format('d F Y') }}</p>
        <p>Staf PT QAFCO Madiun</p>
        <br><br>
        <p><strong>Myranda Enzellina</strong></p>
    </div>

    <div style="clear: both;"></div>

    {{-- Timestamp cetak --}}
    <p style="font-size: 10px; text-align: right;">
        <i>Dicetak melalui sistem: {{ now()->format('d-m-Y H:i') }}</i>
    </p>
</body>

</html>
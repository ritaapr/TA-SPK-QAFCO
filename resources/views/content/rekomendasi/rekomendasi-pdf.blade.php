<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekomendasi CPMI</title>
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

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .no-border {
            border: none;
        }

        .header-info p {
            margin: 2px 0;
        }

        h3, h2 {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    {{-- Kop Surat --}}
    <table class="no-border" style="margin-bottom: 0;">
        <tr class="no-border">
            <td class="no-border" style="width: 80px;">
                <img src="{{ public_path('assets/img/favicon/logo.png') }}" width="60">
            </td>
            <td class="no-border" style="text-align: center;">
                <h2 style="margin: 0;">PT QAFCO MADIUN</h2>
                <p style="margin: 0;">Jl. Raya Jogodayuh, RT.1/RW.1, Dakon, Jogodayuh,</p>
                <p style="margin: 0;">Kec. Geger, Kabupaten Madiun, Jawa Timur 63171</p>
            </td>
        </tr>
    </table>

    <hr style="border: 2px solid black; margin-top: 10px; margin-bottom: 20px;">

    <h3>Laporan Rekomendasi CPMI</h3>

    {{-- Info Umum --}}
    <div class="header-info">
        <p><strong>Periode:</strong> Juni {{ now()->format('Y') }}</p>
        <p><strong>Jumlah Rekomendasi:</strong> {{ $data->count() }}</p>
    </div>

    {{-- Tabel Data --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Nilai SAW</th>
                <th>Tanggal Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                @php
                    $nilai = optional($row->histori->firstWhere('nilai_saw', '!=', null))?->nilai_saw ?? 0;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->cpmi->nama_cpmi }}</td>
                    <td>{{ $row->cpmi->no_hp }}</td>
                    <td>{{ $row->cpmi->alamat }}</td>
                    <td>{{ number_format($nilai, 4) }}</td>
                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td colspan="6">
                        <strong>Rincian Penilaian:</strong>
                        <ul>
                            @foreach ($row->histori as $h)
                                <li>
                                    {{ $h->kriteria->nama_kriteria }}:
                                    {{ $h->subkriteria->nama_subkriteria ?? '-' }}
                                    ({{ $h->nilai }})
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div style="width: 40%; float: right; text-align: center;">
        <p>Madiun, {{ now()->format('d F Y') }}</p>
        <p>Staf PT QAFCO Madiun</p>
        <br><br>
        <p><strong>Myranda Enzellina</strong></p>
    </div>

    <div style="clear: both;"></div>

    <p style="font-size: 10px; text-align: right;">
        <i>Dicetak otomatis melalui sistem | {{ now()->format('d-m-Y H:i') }}</i>
    </p>

</body>

</html>

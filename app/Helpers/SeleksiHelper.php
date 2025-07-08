<?php

namespace App\Helpers;

use App\Models\Penilaian;
use App\Models\Cpmi;
use App\Models\Kriteria;
use App\Models\Rekomendasi;
use App\Helpers\SAWHelper;

class SeleksiHelper
{
    public static function getRankingDefault()
    {
        $kriteriaList = Kriteria::all();
        $penilaianList = Penilaian::with('subkriteria')->get();

        [$matrix, $errors] = SAWHelper::generateMatrix($penilaianList);
        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        $hasil = [];
        foreach ($ranking as $cpmiId => $nilai) {
            $cpmi = Cpmi::find($cpmiId);
            if ($cpmi) {
                $hasil[] = [
                    'cpmi' => $cpmi,
                    'nilai' => round($nilai, 4),
                ];
            }
        }

        return $hasil;
    }

    public static function getFilteredRanking(array $filters)
    {
        $rekomCpmiIds = Rekomendasi::pluck('cpmi_id')->toArray();

        $filteredCpmiQuery = Penilaian::whereNotIn('cpmi_id', $rekomCpmiIds);

        foreach ($filters as $filter) {
            $filteredCpmiQuery->whereHas('subkriteria', function ($q) use ($filter) {
                $q->where('kriteria_id', $filter['kriteria_id'])
                    ->where('subkriteria_id', $filter['subkriteria_id']);
            });
        }

        $filteredCpmiIds = $filteredCpmiQuery->pluck('cpmi_id')->unique();

        $kriteriaList = Kriteria::all();
        $penilaianList = Penilaian::with('subkriteria')->get();

        [$matrix, $errors] = SAWHelper::generateMatrix($penilaianList);
        $normalisasi = SAWHelper::normalisasi($matrix, $kriteriaList);
        $terbobot = SAWHelper::hitungTerbobot($normalisasi, $kriteriaList);
        $ranking = SAWHelper::ranking($terbobot);

        $hasil = [];
        foreach ($ranking as $cpmiId => $nilai) {
            if ($filteredCpmiIds->contains($cpmiId)) {
                $cpmi = Cpmi::find($cpmiId);
                if ($cpmi) {
                    $hasil[] = [
                        'cpmi' => $cpmi,
                        'nilai' => round($nilai, 4),
                    ];
                }
            }
        }

        return $hasil;
    }
}

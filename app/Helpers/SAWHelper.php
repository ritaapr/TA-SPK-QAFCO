<?php

namespace App\Helpers;

class SAWHelper
{

    public static function generateMatrix($penilaianList)
{
    $matrix = [];

    foreach ($penilaianList as $item) {
        if ($item->subkriteria) {
            $matrix[$item->cpmi_id][$item->kriteria_id] = $item->subkriteria->nilai;
        } else {
            $matrix[$item->cpmi_id][$item->kriteria_id] = 0; // fallback nilai
        }
    }

    return $matrix;
}


    public static function normalisasi(array $matrix, $kriteriaList)
    {
        $normal = [];

        foreach ($kriteriaList as $kriteria) {
            $id = $kriteria->id;
            $jenis = $kriteria->jenis;

            $columnValues = array_column($matrix, $id);
            $max = max($columnValues);
            $min = min($columnValues);

            foreach ($matrix as $cpmiId => $nilai) {
                $x = $nilai[$id] ?? 0;
                $r = $jenis === 'benefit' ? $x / $max : $min / $x;
                $normal[$cpmiId][$id] = $r;
            }
        }

        return $normal;
    }

    public static function hitungTerbobot(array $normalisasi, $kriteriaList)
    {
        $terbobot = [];

        foreach ($normalisasi as $cpmiId => $nilaiKriteria) {
            foreach ($kriteriaList as $kriteria) {
                $id = $kriteria->id;
                $bobot = $kriteria->bobot;
                $terbobot[$cpmiId][$id] = $nilaiKriteria[$id] * $bobot;
            }
        }

        return $terbobot;
    }

    public static function ranking(array $terbobot)
    {
        $result = [];

        foreach ($terbobot as $cpmiId => $nilai) {
            $result[$cpmiId] = array_sum($nilai);
        }

        arsort($result); // descending

        return $result;
    }
}

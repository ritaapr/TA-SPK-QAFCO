<?php

namespace App\Helpers;

class SAWHelper
{

    public static function generateMatrix($penilaianList)
    {
        $matrix = [];
        $errors = [];

        foreach ($penilaianList as $item) {
            $cpmiId = $item->cpmi_id;
            $kriteriaId = $item->kriteria_id;

            if ($item->subkriteria) {
                $matrix[$cpmiId][$kriteriaId] = $item->subkriteria->nilai;
            } else {
                $matrix[$cpmiId][$kriteriaId] = null; 
                $errors[] = "CPMI ID <strong>{$cpmiId}</strong> belum memiliki penilaian untuk kriteria ID <strong>{$kriteriaId}</strong>.";
            }
        }

        return [$matrix, $errors];
    }


    public static function normalisasi(array $matrix, $kriteriaList)
    {
        $normal = [];

        foreach ($kriteriaList as $kriteria) {
            $id = $kriteria->id;
            $jenis = $kriteria->jenis;

            $columnValues = array_column($matrix, $id);

            // Cek apakah data ada sebelum hitung max/min
            if (empty($columnValues)) {
                continue; // skip kriteria ini
            }

            $max = max($columnValues);
            $min = min($columnValues);

            foreach ($matrix as $cpmiId => $nilai) {
                $x = $nilai[$id] ?? 0;

                // Hindari pembagian nol
                if ($jenis === 'benefit') {
                    $r = $max > 0 ? $x / $max : 0;
                } else {
                    $r = $x > 0 ? $min / $x : 0;
                }

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

    public static function hitung($penilaianList, $kriteriaList, $cpmiList)
    {
        [$matrix, $errors] = self::generateMatrix($penilaianList);
        $normal = self::normalisasi($matrix, $kriteriaList);
        $terbobot = self::hitungTerbobot($normal, $kriteriaList);
        $ranking = self::ranking($terbobot);

        // Format akhir: [cpmi_id => ['nama_cpmi' => ..., 'nilai_saw' => ..., 'ranking' => ...]]
        $formatted = [];
        $rank = 1;
        foreach ($ranking as $cpmiId => $nilai) {
            $cpmi = $cpmiList->firstWhere('id', $cpmiId);
            if (!$cpmi) continue; // Skip CPMI yang tidak tersedia

            $formatted[$cpmiId] = [
                'nama_cpmi' => $cpmi->nama_cpmi,
                'nilai_saw' => round($nilai, 4),
                'ranking' => $rank++
            ];
        }


        return $formatted;
    }
}

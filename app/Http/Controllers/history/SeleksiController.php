<?php

namespace App\Http\Controllers\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\Subkriteria;
use App\Helpers\SeleksiHelper;

class SeleksiController extends Controller
{
    public function index()
    {
        $kriteriaList = Kriteria::all();
        $hasilRankingDefault = SeleksiHelper::getRankingDefault();

        return view('content.history.filter-penilaian', compact('kriteriaList', 'hasilRankingDefault'));
    }

    public function ajaxFilter(Request $request)
    {
        $filters = $request->filters;
        $hasil = SeleksiHelper::getFilteredRanking($filters);

        $html = view('content.history._table-ranking', compact('hasil'))->render();
        return response()->json(['html' => $html]);
    }

    public function getSubkriteria($id)
    {
        return Subkriteria::where('kriteria_id', $id)->get();
    }
}

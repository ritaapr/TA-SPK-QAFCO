<?php

namespace App\Http\Controllers\form_layouts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;
use App\Models\Kriteria;

class HorizontalForm extends Controller
{
  public function index()
{
    $cpmiList = Cpmi::all();
    $kriteriaList = Kriteria::with('subkriteria')->get();

    return view('content.form-layout.form-layouts-horizontal', compact('cpmiList', 'kriteriaList'));
}
}

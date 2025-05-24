<?php

namespace App\Http\Controllers\form_layouts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cpmi;
use App\Models\Kriteria;

class VerticalForm extends Controller
{
  public function index()
  {
    $cpmiList = Cpmi::all();
    $kriteriaList = Kriteria::with('subkriterias')->get();

    return view('content.form-layout.form-layouts-vertical', compact('cpmiList', 'kriteriaList'));
  }
}

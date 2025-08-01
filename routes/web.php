<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\data\KriteriaController;
use App\Http\Controllers\data\CpmiController;
use App\Http\Controllers\data\SubkriteriaController;
use App\Http\Controllers\data\UserController;
use App\Http\Controllers\authentications\AccountController;
use App\Http\Controllers\penilaian\PenilaianController;
use App\Http\Controllers\history\SeleksiController;
use App\Http\Controllers\rekomendasi\RekomendasiController;
use App\Http\Controllers\penilaian\HistoriPenilaianController;

Route::get('/', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');

// Main Page Route
Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');

// Data CPMI fix
Route::get('/data-cpmi', [CpmiController::class, 'index'])->name('data-cpmi-copy');
Route::get('/data-cpmi/create', [CpmiController::class, 'create'])->name('data-cpmi-copy.create');
Route::post('/data-cpmi', [CpmiController::class, 'store'])->name('data-cpmi-copy.store');
Route::get('/data-cpmi/{id}/edit', [CpmiController::class, 'edit'])->name('data-cpmi-copy.edit');
Route::put('/data-cpmi/{id}', [CpmiController::class, 'update'])->name('data-cpmi-copy.update');
Route::delete('/data-cpmi/{id}', [CpmiController::class, 'destroy'])->name('data-cpmi-copy.destroy');

// Kriteria
Route::resource('data-kriteria', KriteriaController::class)->except(['show']);

// Endpoint untuk hitung dan reset bobot
Route::post('/data-kriteria/hitung-bobot', [KriteriaController::class, 'hitungBobot'])->name('kriteria.hitungBobot');
Route::post('/data-kriteria/reset-bobot', [KriteriaController::class, 'resetBobot'])->name('kriteria.resetBobot');



Route::middleware(['auth', 'role:superadmin'])->group(function () {

    // Data User
    Route::resource('users', UserController::class);

    // Endpoint untuk ambil data subkriteria berdasarkan ID kriteria
Route::get('/data-kriteria/{id}/subkriteria', [KriteriaController::class, 'getSubkriteria'])->name('kriteria.getSubkriteria');
    
Route::get('/get-subkriteria/{id}', [SeleksiController::class, 'getSubkriteria']);

    Route::get('/hasilpenilaian', [SeleksiController::class, 'index'])->name('hasilpenilaian.index');
    Route::post('/hasilpenilaian/ajax-filter', [SeleksiController::class, 'ajaxFilter'])->name('hasilpenilaian.ajaxFilter');

});

Route::post('/rekomendasi', [RekomendasiController::class, 'store'])->name('rekomendasi.store');
Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
Route::get('/rekomendasi/export-pdf', [RekomendasiController::class, 'exportPdf'])->name('rekomendasi.export-pdf');

Route::post('/rekomendasi/arsipkan', [RekomendasiController::class, 'arsipkan'])->name('rekomendasi.arsipkan');

Route::get('/rekomendasi-histori', [HistoriPenilaianController::class, 'index'])->name('rekomendasi.histori');
Route::get('/rekomendasi-histori/{cpmi}', [HistoriPenilaianController::class, 'detail'])->name('rekomendasi.histori.detail');
// Route::middleware(['auth', 'role:admin'])->group(function () {



// });

// Subkriteria
Route::resource('data-subkriteria', SubkriteriaController::class)
    ->parameters(['data-subkriteria' => 'subkriteria']) // ganti binding jadi 'subkriteria'
    ->names([
        'index' => 'data-subkriteria',
        'create' => 'data-subkriteria.create',
        'store' => 'data-subkriteria.store',
        'edit' => 'data-subkriteria.edit',
        'update' => 'data-subkriteria.update',
        'destroy' => 'data-subkriteria.destroy',
        'show' => 'data-subkriteria.show',
    ]);

// penilaian
Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
Route::post('/penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');

Route::get('/penilaian/{id}/edit', [PenilaianController::class, 'edit'])->name('penilaian.edit');
Route::put('/penilaian/{id}', [PenilaianController::class, 'update'])->name('penilaian.update');
Route::delete('/penilaian/{id}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

// proses spk saw
Route::get('/penilaian/perhitungan', [PenilaianController::class, 'perhitungan'])->name('penilaian.perhitungan');
Route::get('/penilaian/export-pdf', [PenilaianController::class, 'exportPdf'])->name('penilaian.export-pdf');




// edit profile
Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
Route::post('/account/settings', [AccountController::class, 'update'])->name('account.settings.update');

//logout
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');

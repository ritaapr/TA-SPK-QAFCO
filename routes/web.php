<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;

use App\Http\Controllers\authentications\LoginBasic;

use App\Http\Controllers\form_elements\BasicInput;

use App\Http\Controllers\form_elements\BasicInputCopy;

use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\form_layouts\PenilaianController;
use App\Http\Controllers\tables\Basic as TablesBasic;

Route::get('/', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginBasic::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Main Page Route
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');


    // form elements
    Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
    // Route::get('/forms/input-groups', [InputSubkriteria::class, 'index'])->name('forms-input-groups');

    // Data CPMI fix
    Route::get('/forms/basic-inputs-copy', [BasicInputCopy::class, 'index'])->name('forms-basic-inputs-copy');
    Route::get('/forms/basic-inputs-copy/create', [BasicInputCopy::class, 'create'])->name('forms-basic-inputs-copy.create');
    Route::post('/forms/basic-inputs-copy/store', [BasicInputCopy::class, 'store'])->name('forms-basic-inputs-copy.store');
    Route::get('/forms/basic-inputs-copy/{id}/edit', [BasicInputCopy::class, 'edit'])->name('forms-basic-inputs-copy.edit');
    Route::put('/forms/basic-inputs-copy/{id}', [BasicInputCopy::class, 'update'])->name('forms-basic-inputs-copy.update');
    Route::delete('/forms/basic-inputs-copy/{id}', [BasicInputCopy::class, 'destroy'])->name('forms-basic-inputs-copy.destroy');

    Route::resource('forms-kriteria-inputs', \App\Http\Controllers\form_elements\BasicInput::class);
    Route::post('/hitung-bobot', [BasicInput::class, 'hitungBobot'])->name('kriteria.hitungBobot');
    Route::post('update-bobot/{id}', [\App\Http\Controllers\form_elements\BasicInput::class, 'updateBobot'])->name('update-bobot');
    Route::post('/path/to/store/bobot', [BasicInput::class, 'hitungBobotSemua']);


    //Route::resource('forms-subkriteria-inputs', \App\Http\Controllers\form_elements\InputSubkriteria::class);
    Route::resource('forms-subkriteria-inputs', \App\Http\Controllers\form_elements\InputSubkriteria::class)
        ->names([
            'index' => 'forms-subkriteria-inputs',
            'create' => 'forms-subkriteria-inputs.create',
            'store' => 'forms-subkriteria-inputs.store',
            'edit' => 'forms-subkriteria-inputs.edit',
            'update' => 'forms-subkriteria-inputs.update',
            'destroy' => 'forms-subkriteria-inputs.destroy',
            'show' => 'forms-subkriteria-inputs.show',
        ]);


    // penilaian
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');

    // proses spk saw
    Route::get('/penilaian/perhitungan', [PenilaianController::class, 'perhitungan'])->name('penilaian.perhitungan');
    Route::get('/penilaian/export-pdf', [PenilaianController::class, 'exportPdf'])->name('penilaian.export-pdf');


    // form layouts
    Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');

    Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

    // tables
    Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');
});

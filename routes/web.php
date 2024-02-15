<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\KabupatenController;
use App\Models\Kabupaten;
use App\Models\Provinsi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([], function () {
    Route::prefix('')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::get('/data-penduduk', [AdminController::class, 'index'])->name('data-penduduk');
        Route::get('/data-penduduk/create', [AdminController::class, 'create'])->name('data-penduduk/create');
        Route::post('/data-penduduk/store', [AdminController::class, 'store'])->name('data-penduduk/store');
        Route::post('/data-penduduk/edit/{nik}', [AdminController::class, 'edit'])->name('data-penduduk/edit');
        Route::put('/data-penduduk/update/{id}', [AdminController::class, 'update'])->name('data-penduduk/update');
        Route::delete('/data-penduduk/delete/{nik}', [AdminController::class, 'destroy'])->name('data-penduduk/delete');
        Route::post('/data-penduduk/excel', [AdminController::class, 'cetak_excel'])->name('data-penduduk/excel');
        Route::get('/data-penduduk/cari', [AdminController::class, 'index'])->name('data-penduduk/cari');
        Route::get('/data-penduduk/getKab/{provinsi}', function ($provinsi) {
            $idProvinsi = Provinsi::where('nama_provinsi', $provinsi)->first()->id;
            $kabupaten = Kabupaten::where('provinsi_id', $idProvinsi)->get();
            return response()->json($kabupaten);
        })->name('data-penduduk/getKab');
    });

    Route::prefix('provinsi')->group(function () {
        Route::get('/', [ProvinsiController::class, 'index'])->name('provinsi');
        Route::get('/create', [ProvinsiController::class, 'create'])->name('provinsi/create');
        Route::post('/store', [ProvinsiController::class, 'store'])->name('provinsi/store');
        Route::get('/edit/{nik}', [ProvinsiController::class, 'edit'])->name('provinsi/edit');
        Route::put('/update/{id}', [ProvinsiController::class, 'update'])->name('provinsi/update');
        Route::delete('/delete/{nik}', [ProvinsiController::class, 'destroy'])->name('provinsi/delete');
        Route::get('/cari', [ProvinsiController::class, 'index'])->name('provinsi/cari');
    });

    Route::prefix('kabupaten')->group(function () {
        Route::get('/', [KabupatenController::class, 'index'])->name('kabupaten');
        Route::post('/create', [KabupatenController::class, 'create'])->name('kabupaten/create');
        Route::post('/store', [KabupatenController::class, 'store'])->name('kabupaten/store');
        Route::get('/edit/{nik}', [KabupatenController::class, 'edit'])->name('kabupaten/edit');
        Route::put('/update/{id}', [KabupatenController::class, 'update'])->name('kabupaten/update');
        Route::delete('/delete/{nik}', [KabupatenController::class, 'destroy'])->name('kabupaten/delete');
        Route::get('/cari', [KabupatenController::class, 'index'])->name('kabupaten/cari');
    });
});

// Route Laporan
Route::get('/laporan', [LaporanController::class, 'laporan'])->name('laporan');

// Route Filter Cari Data Laporan
Route::get('/laporan/cari', [LaporanController::class, 'laporan'])->name('laporan/cari');

// Route Cetak Laporan Ke Excel
Route::get('/excel', [LaporanController::class, 'cetak_excel'])->name('cetak-excel');

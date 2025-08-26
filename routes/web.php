<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IqroController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SabaqController;
use App\Http\Controllers\SabqiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ManzilController;
use App\Http\Controllers\UstadzController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login',[LoginController::class, 'handleLogin'])->name('login');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->middleware('role:admin|ustadz|siswa')->name('dashboard');

    Route::get('/kelasnyas',[KelasController::class, 'index'])->middleware('role:admin')->name('kelasnyas');
    Route::post('kelasnyas/store', [KelasController::class, 'store'])->middleware('role:admin')->name('kelasnyas.store');
    Route::get('kelasnyas/{kelasnya}/edit', [KelasController::class, 'edit'])->middleware('role:admin')->name('kelasnyas.edit');
    Route::put('kelasnyas/{kelasnya}', [KelasController::class, 'update'])->middleware('role:admin')->name('kelasnyas.update');
    Route::delete('kelasnyas/{kelasnya}', [KelasController::class, 'destroy'])->middleware('role:admin')->name('kelasnyas.destroy');

    Route::resource('ustadzs', UstadzController::class)->middleware('role:admin');
    Route::resource('siswas', SiswaController::class)->middleware('role:admin');
    Route::get('get-ustadz/{kelas_id}', [SiswaController::class, 'getUstadzByKelas'])->middleware('role:admin');

    Route::get('sabaqs',[SabaqController::class, 'index'])->middleware('role:admin|ustadz')->name('sabaqs');
    Route::get('sabaq-history/{siswa_id}', [SabaqController::class, 'showSabaqHistory'])->middleware('role:admin|ustadz')->name('sabaq-history.show');
    // Route untuk mendapatkan data surat berdasarkan nomor surat (sura_no)
    Route::get('get-surat-details/{sura_no}', [SabaqController::class, 'getSuratDetails'])->middleware('role:admin|ustadz')->name('get.surat.details');
    Route::post('/sabaq/store', [SabaqController::class, 'store'])->middleware('role:admin|ustadz')->name('sabaq.store');
    Route::get('sabaq-history/{siswa_id}/edit/{id}', [SabaqController::class, 'edit'])->middleware('role:admin')->name('sabaq-history.edit');
    Route::post('sabaq-history/{siswa_id}/update/{id}', [SabaqController::class, 'update'])->middleware('role:admin')->name('sabaq-history.update');
    Route::delete('sabaq-history/{siswa_id}/{id}', [SabaqController::class, 'destroy'])->middleware('role:admin|ustadz')->name('sabaq-history.destroy');
    Route::get('sabaqs/sabaqsiswa', [SabaqController::class, 'showSiswaHistory'])->middleware('role:siswa')->name('sabaqs.sabaqsiswa');

    
    Route::get('sabqis',[SabqiController::class, 'index'])->middleware('role:admin|ustadz')->name('sabqis');
    Route::get('sabqi-history/{siswa_id}', [SabqiController::class, 'showSabqiHistory'])->middleware('role:admin|ustadz')->name('sabqi-history.show');
    Route::post('/sabqi/store', [SabqiController::class, 'store'])->middleware('role:admin|ustadz')->name('sabqi.store');
    Route::delete('sabqi-history/{siswa_id}/{id}', [SabqiController::class, 'destroy|ustadz'])->middleware('role:admin')->name('sabqi-history.destroy');
    Route::get('sabqis/sabqisiswa', [SabqiController::class, 'showSiswaHistory'])->middleware('role:siswa')->name('sabqis.sabqisiswa');

    Route::get('manzils',[ManzilController::class, 'index'])->middleware('role:admin|ustadz')->name('manzils');
    Route::get('manzil-history/{siswa_id}', [ManzilController::class, 'showmanzilHistory'])->middleware('role:admin|ustadz')->name('manzil-history.show');
    Route::post('/manzil/store', [ManzilController::class, 'store'])->middleware('role:admin|ustadz')->name('manzil.store');
    Route::delete('manzil-history/{siswa_id}/{id}', [ManzilController::class, 'destroy'])->middleware('role:admin')->name('manzil-history.destroy');
    Route::get('manzils/manzilsiswa', [ManzilController::class, 'showSiswaHistory'])->middleware('role:siswa')->name('manzils.manzilsiswa');

    Route::get('iqros',[IqroController::class, 'index'])->middleware('role:admin|ustadz')->name('iqros');
    Route::get('iqro-history/{siswa_id}', [IqroController::class, 'showiqroHistory'])->middleware('role:admin|ustadz')->name('iqro-history.show');
    // Route untuk store iqro history
    Route::post('iqro-history/{siswa_id}/store', [IqroController::class, 'store'])->middleware('role:admin|ustadz')->name('iqro-history.store');
    Route::get('iqro-history/{siswa_id}/{id}/edit', [IqroController::class, 'edit'])->middleware('role:admin')->name('iqro-history.edit');
    Route::put('iqro-history/{siswa_id}/{id}/update', [IqroController::class, 'update'])->middleware('role:admin')->name('iqro-history.update');
    Route::get('iqros/iqrosiswa', [IqroController::class, 'showSiswaHistory'])->middleware('role:siswa')->name('iqros.iqrosiswa');
});
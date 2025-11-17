<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IqroController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UstadzController;
use App\Http\Controllers\TadarusController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HariliburController;
use App\Http\Controllers\JenisiqroController;
use App\Http\Controllers\AdminsiswaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AbsenustadzController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login',[LoginController::class, 'handleLogin'])->name('login');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
// FORM GANTI PASSWORD
Route::get('/password/change', [LoginController::class, 'changePasswordForm'])
    ->middleware('auth')
    ->name('password.change');

// PROSES UPDATE PASSWORD
Route::post('/password/update', [LoginController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->middleware('role:admin|ustadz|siswa')->name('dashboard');

    Route::get('/kelasnyas',[KelasController::class, 'index'])->middleware('role:admin')->name('kelasnyas');
    Route::post('kelasnyas/store', [KelasController::class, 'store'])->middleware('role:admin')->name('kelasnyas.store');
    Route::get('kelasnyas/{kelasnya}/edit', [KelasController::class, 'edit'])->middleware('role:admin')->name('kelasnyas.edit');
    Route::put('kelasnyas/{kelasnya}', [KelasController::class, 'update'])->middleware('role:admin')->name('kelasnyas.update');
    Route::delete('kelasnyas/{kelasnya}', [KelasController::class, 'destroy'])->middleware('role:admin')->name('kelasnyas.destroy');

    Route::get('/kelompoks',[KelompokController::class, 'index'])->middleware('role:admin')->name('kelompoks');
    Route::post('kelompoks/store', [KelompokController::class, 'store'])->middleware('role:admin')->name('kelompoks.store');
    Route::get('kelompoks/{kelompok}/edit', [KelompokController::class, 'edit'])->middleware('role:admin')->name('kelompoks.edit');
    Route::put('kelompoks/{kelompok}', [KelompokController::class, 'update'])->middleware('role:admin')->name('kelompoks.update');
    Route::delete('kelompoks/{kelompok}', [KelompokController::class, 'destroy'])->middleware('role:admin')->name('kelompoks.destroy');

    Route::get('/jenisiqros',[JenisiqroController::class, 'index'])->middleware('role:admin')->name('jenisiqros');
    Route::post('jenisiqros/store', [JenisiqroController::class, 'store'])->middleware('role:admin')->name('jenisiqros.store');
    Route::get('jenisiqros/{jenisiqro}/edit', [JenisiqroController::class, 'edit'])->middleware('role:admin')->name('jenisiqros.edit');
    Route::put('jenisiqros/{jenisiqro}', [JenisiqroController::class, 'update'])->middleware('role:admin')->name('jenisiqros.update');
    Route::delete('jenisiqros/{jenisiqro}', [JenisiqroController::class, 'destroy'])->middleware('role:admin')->name('jenisiqros.destroy');

    Route::get('/hariliburs',[HariliburController::class, 'index'])->middleware('role:admin')->name('hariliburs');
    Route::get('/hariliburs/create',[HariliburController::class, 'create'])->middleware('role:admin')->name('hariliburs.create'); 
    Route::post('hariliburs/store', [HariliburController::class, 'store'])->middleware('role:admin')->name('hariliburs.store');
    Route::get('/hariliburs/{id}/edit', [HariliburController::class, 'edit'])->middleware('role:admin')->name('hariliburs.edit');
    Route::put('/hariliburs/{id}', [HariliburController::class, 'update'])->middleware('role:admin')->name('hariliburs.update');
    Route::delete('/hariliburs/{id}/destroy', [HariliburController::class, 'destroy'])->middleware('role:admin')->name('hariliburs.destroy');
    Route::get('hariliburs/bulanan', [HariliburController::class, 'createMonthly'])->name('hariliburs.monthly');
    Route::post('hariliburs/bulanan', [HariliburController::class, 'storeMonthly'])->name('hariliburs.monthly.store');
    Route::get('/cek-hari-libur/{tanggal}', [HariliburController::class, 'cekHariLibur'])->name('harilibur.cek');
    

    Route::get('/adminsiswas',[AdminsiswaController::class, 'index'])->middleware('role:admin')->name('adminsiswas');
    Route::post('adminsiswas/store', [AdminsiswaController::class, 'store'])->middleware('role:admin')->name('adminsiswas.store');
    Route::get('adminsiswas/{adminsiswa}/edit', [AdminsiswaController::class, 'edit'])->middleware('role:admin')->name('adminsiswas.edit');
    Route::put('adminsiswas/{adminsiswa}', [AdminsiswaController::class, 'update'])->middleware('role:admin')->name('adminsiswas.update');
    Route::delete('adminsiswas/{adminsiswa}', [AdminsiswaController::class, 'destroy'])->middleware('role:admin')->name('adminsiswas.destroy');

    Route::get('/ustadzs',[UstadzController::class, 'index'])->middleware('role:admin')->name('ustadzs');
    Route::post('ustadzs/store', [UstadzController::class, 'store'])->middleware('role:admin')->name('ustadzs.store');
    Route::get('ustadzs/{ustadz}/edit', [UstadzController::class, 'edit'])->middleware('role:admin')->name('ustadzs.edit');
    Route::put('ustadzs/{ustadz}', [UstadzController::class, 'update'])->middleware('role:admin')->name('ustadzs.update');
    Route::delete('ustadzs/{ustadz}', [UstadzController::class, 'destroy'])->middleware('role:admin')->name('ustadzs.destroy');
    // Route::resource('ustadzs', UstadzController::class)->middleware('role:admin');
    Route::get('/siswas',[SiswaController::class, 'index'])->middleware('role:admin')->name('siswas');
    Route::post('siswas/store', [SiswaController::class, 'store'])->middleware('role:admin')->name('siswas.store');
    Route::get('siswas/{siswa}/edit', [SiswaController::class, 'edit'])->middleware('role:admin')->name('siswas.edit');
    Route::put('siswas/{siswa}', [SiswaController::class, 'update'])->middleware('role:admin')->name('siswas.update');
    Route::delete('siswas/{ustadz}', [SiswaController::class, 'destroy'])->middleware('role:admin')->name('siswas.destroy');
    // Route::resource('siswas', SiswaController::class)->middleware('role:admin');
    
    Route::get('/tadaruses',[TadarusController::class, 'index'])->middleware('role:admin|ustadz')->name('tadaruses');
    Route::get('/tadaruses/create',[TadarusController::class, 'create'])->middleware('role:admin|ustadz')->name('tadaruses.create');
    Route::get('/tadaruses',[TadarusController::class, 'index'])->middleware('role:admin|ustadz')->name('tadaruses');
    Route::get('tadarus-history/{siswa_id}', [TadarusController::class, 'showTadarusHistory'])->middleware('role:admin|ustadz')->name('tadarus-history.show');
    Route::get('get-surat-details/{sura_no}', [TadarusController::class, 'getSuratDetails'])->middleware('role:admin|ustadz')->name('get.surat.details');
    Route::post('/tadaruses/store', [TadarusController::class, 'store'])->middleware('role:admin|ustadz')->name('tadarus.store');
    // Route::post('/tadaruses/update/{id}', [TadarusController::class, 'update'])
    // ->middleware('role:admin|ustadz')
    // ->name('tadarus.update');
   // ambil data buat modal edit

    Route::get('/tadarus/{id}', [TadarusController::class, 'show'])->name('tadarus.show');

    Route::get('tadaruses/history/{id}/edit', [TadarusController::class, 'getHistory'])
    ->middleware('role:admin|ustadz')
    ->name('tadarus.history.edit');

    // update via AJAX
    Route::put('tadaruses/history/{id}', [TadarusController::class, 'updateHistory'])
    ->middleware('role:admin|ustadz')
    ->name('tadarus.history.update');

    // route untuk ambil detail surat (juz, halaman, dll) – dipakai tambah & edit
    Route::get('/get-surat-details/{sura_no}', [TadarusController::class, 'getSuratDetails'])
    ->middleware('role:admin|ustadz')
    ->name('tadarus.surat.details');

    Route::delete('tadaruses/history/{id}', [TadarusController::class, 'destroy'])
    ->middleware('role:admin|ustadz')
    ->name('tadarus.history.destroy');

    // ajax: ambil siswa per kelompok
    Route::get('/api/kelompoks/{kelompok}/siswas',[TadarusController::class,'getSiswasByKelompok'])
    ->middleware('role:admin|ustadz')->name('api.kelompok.siswas');

    // ajax: (opsional) detail surat (kalau mau isi No/Juz/Hal otomatis)
    Route::get('/api/madina/{sura_no}',[TadarusController::class,'getSuratDetails'])
    ->middleware('role:admin|ustadz')->name('api.madina.details');

    Route::get('/api/siswas/{siswa}/tadarus', [TadarusController::class, 'getTadarusBySiswa'])
    ->middleware('role:admin|ustadz')
    ->name('api.siswa.tadarus');

    // Route untuk edit tadarus
    Route::get('/tadaruses/{id}/edit', [TadarusController::class, 'edit'])->middleware('role:admin|ustadz')->name('tadaruses.edit');

    // Route untuk update tadarus
    Route::put('/tadaruses/{id}/update', [TadarusController::class, 'update'])->middleware('role:admin|ustadz')->name('tadaruses.update');

    // Route untuk delete tadarus
    Route::delete('/tadaruses/{id}/destroy', [TadarusController::class, 'destroy'])->middleware('role:admin|ustadz')->name('tadarus.destroy');


    Route::get('/absensiustadz', [AbsenustadzController::class, 'index'])->middleware('role:admin')->name('absensiustadz.index');
    Route::post('absensiustadz/store', [AbsenustadzController::class, 'store'])->middleware('role:admin')->name('absensiustadz.store');
    Route::get('absensiustadz/{absensiustadz}/edit', [AbsenustadzController::class, 'edit'])->middleware('role:admin')->name('absensiustadz.edit');
    Route::put('absensiustadz/{absensiustadz}', [AbsenustadzController::class, 'update'])->middleware('role:admin')->name('absensiustadz.update');
    Route::delete('absensiustadz/{absensiustadz}', [AbsenustadzController::class, 'destroy'])->middleware('role:admin')->name('absensiustadz.destroy');

    Route::get('/iqros',[IqroController::class, 'index'])->middleware('role:admin')->name('iqros');
    Route::get('/iqros/create',[IqroController::class, 'create'])->middleware('role:admin')->name('iqros.create');
    Route::post('/iqros/store',[IqroController::class, 'store'])->middleware('role:admin')->name('iqros.store');
    Route::get('/iqros/get-siswa-by-kelompok/{kelompokId}', [IqroController::class, 'getSiswaByKelompok']);
    Route::get('/iqros/{id}/edit', [IqroController::class, 'edit'])->middleware('role:admin')->name('iqros.edit');
    Route::put('/iqros/{id}', [IqroController::class, 'update'])->middleware('role:admin')->name('iqros.update');
    // routes/web.php atau routes/api.php
    Route::get('/api/siswas/{siswa}/iqrohistories', [IqroController::class, 'getHistories']);
    Route::get('/iqros/{id}', [IqroController::class, 'show'])->name('iqros.show');


});

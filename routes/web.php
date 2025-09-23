<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\RaperdaController;
use App\Http\Controllers\PublikasiController;

Route::get('/', fn() => view('home'))->name('home');
Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/ajukan', fn() => view('layanan.ajukan'))->name('ajukan');
    Route::get('/tracking', fn() => view('layanan.tracking'))->name('tracking');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('raperdas', RaperdaController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/raperdas', 'admin.raperdas.index')->name('raperdas.index');
});

Route::get('/publikasi', [PublikasiController::class, 'index'])->name('publikasi.index');
Route::get('/publikasi/{raperda:slug}', [PublikasiController::class, 'show'])->name('publikasi.show');

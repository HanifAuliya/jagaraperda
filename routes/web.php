<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Livewire\Admin\Dashboard;

use App\Http\Controllers\Admin\RaperdaController;
use App\Http\Controllers\PublikasiController;

use App\Livewire\Public\AspirasiForm;
use App\Livewire\Public\AspirasiTracking;
use App\Livewire\Admin\AspirasiQueue;

use App\Livewire\Admin\GalleryCrud;

use App\Livewire\Admin\NewsCrud;
use App\Livewire\Public\NewsIndex;
use App\Models\News;

// routes/web.php
use App\Livewire\Public\AspirasiSuksesList;
use App\Livewire\Public\AspirasiSuksesShow;

Route::get('/', fn() => view('home'))->name('home');
Route::view('/kontak', 'kontak')->name('kontak');

// Route::prefix('layanan')->name('layanan.')->group(function () {
//     Route::get('/ajukan', fn() => view('layanan.ajukan'))->name('ajukan');
//     Route::get('/tracking', fn() => view('layanan.tracking'))->name('tracking');
// });
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('raperdas', RaperdaController::class);
});

Route::get('/raperda', [PublikasiController::class, 'index'])->name('publikasi.index');
Route::get('/raperda/{raperda:slug}', [PublikasiController::class, 'show'])->name('publikasi.show');

Route::get('/layanan/ajukan', AspirasiForm::class)->name('aspirasi.form');
Route::get('/layanan/tracking', AspirasiTracking::class)->name('aspirasi.tracking');

Route::get('/dl/{aspirasi}/{path}', function (Request $req, $aspirasi, $path) {
    // Validasi parameter sederhana
    abort_unless(is_numeric($aspirasi), 404);

    // Cegah path traversal
    abort_if(Str::contains($path, ['..', './', '\\']), 400);

    // (Opsional) batasi karakter path
    abort_unless(preg_match('#^[A-Za-z0-9/_\.-]+$#', $path), 400);

    $rel = "aspirasi/{$aspirasi}/{$path}";
    abort_unless(Storage::disk('public')->exists($rel), 404);

    // Untuk “view-only”, file inline:
    return response()->file(Storage::disk('public')->path($rel));
    // atau kalau mau paksa download:
    // return Storage::disk('public')->download($rel);
})
    ->where('path', '.*')
    ->middleware('throttle:60,1') // cegah brute-force tebak path
    ->name('aspirasi.file');

Route::get('/aspirasi-sukses', AspirasiSuksesList::class)->name('aspirasi.sukses');
// Route::get('/aspirasi-sukses/{aspirasi}', AspirasiSuksesShow::class)->name('aspirasi.sukses.show');


Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Admin (pastikan auth + is_admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/aspirasi/antrian', AspirasiQueue::class)->name('aspirasi.queue');


    // CRUD Raperda (gunakan resource saja, jangan digandakan oleh Route::view)
    Route::resource('raperdas', RaperdaController::class);
});


// Publik
Route::get('/publikasi', fn() => view('news.index'))->name('news.index');
Route::get('/publikasi/{news:slug}', function (News $news) {
    abort_unless($news->active, 404);
    return view('news.show', compact('news'));
})->name('news.show');

// Admin (gabung ke group admin yang sudah ada)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/berita', NewsCrud::class)->name('news');
});

// PUBLIC
Route::get('/galeri', fn() => view('galeri/galeri'))->name('galeri.index');

// ADMIN (dalam group admin yang sudah ada)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/galeri', GalleryCrud::class)->name('galeri');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

@extends('layouts.app')
@section('title', 'Tracking Masukkan — JAGARPERDA KALSEL')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- Judul -->
            <header class="mb-3">
                <h1 class="h3 fw-bold mb-1 text-royal-ink">Lacak Status Laporan</h1>
                <div class="pg-underline"></div>

                <p class="text-muted mt-2 mb-0">
                    Masukkan
                    <span class="fw-semibold">nomor laporan</span>
                    atau
                    <span class="fw-semibold">kata kunci</span>
                    judul/isi laporan Anda.
                </p>
            </header>

            <!-- Form Pencarian -->
            <section class="panel-simple p-3 mb-4">
                <form class="row g-2 align-items-center" role="search">
                    <div class="col-12 col-md">
                        <label for="q" class="form-label small text-muted mb-1">
                            Nomor laporan / kata kunci
                        </label>
                        <input id="q" type="search" class="form-control"
                            placeholder="Ketik nomor laporan atau kata kunci…" />
                        <div class="form-text">
                            Format nomor:
                            <code>JGR-YYYY-NNNNNN</code>
                        </div>
                    </div>
                    <div class="col-12 col-md-auto d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>
                        <button class="btn btn-outline-secondary" type="reset">
                            Reset
                        </button>
                    </div>
                </form>
            </section>

            <!-- Ringkasan Hasil -->
            <div class="mb-2">
                <div class="small text-muted">
                    Ditemukan
                    <span class="fw-semibold">4</span>
                    hasil untuk:
                    <span class="fw-semibold">"sampah"</span>
                </div>
            </div>

            <!-- Daftar Hasil -->
            <section class="results-stack">
                <!-- Item -->
                <article class="card lux-card overflow-hidden result-item">
                    <div class="card-body p-3 p-sm-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <h2 class="h5 text-royal-ink card-title mb-0">
                                        Penertiban reklame liar di jalan utama
                                    </h2>
                                </a>
                                <div class="small text-muted mt-1">
                                    <span class="me-2">
                                        <i class="bi bi-hash"></i>
                                        <code class="fw-semibold">JGR-2025-000123</code>
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-calendar-event"></i>
                                        21 September 2025
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-journal-text"></i>
                                        Raperda Ketertiban Umum
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark">Verifikasi</span>
                        </div>
                        <p class="text-muted mb-3">
                            Mohon dilakukan penertiban terhadap pemasangan reklame liar
                            yang mengganggu pandangan dan keselamatan berkendara…
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Lihat Status
                        </a>
                    </div>
                </article>

                <article class="card lux-card overflow-hidden result-item">
                    <div class="card-body p-3 p-sm-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <h2 class="h5 text-royal-ink card-title mb-0">
                                        Pengelolaan sampah pasar malam
                                    </h2>
                                </a>
                                <div class="small text-muted mt-1">
                                    <span class="me-2">
                                        <i class="bi bi-hash"></i>
                                        <code class="fw-semibold">JGR-2025-000118</code>
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-calendar-event"></i>
                                        20 September 2025
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-journal-text"></i>
                                        Raperda Lingkungan Hidup
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-primary">Tindak Lanjut</span>
                        </div>
                        <p class="text-muted mb-3">
                            Setiap akhir pekan, sampah menumpuk usai pasar malam. Mohon
                            penambahan TPS sementara dan petugas…
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Lihat Status
                        </a>
                    </div>
                </article>

                <article class="card lux-card overflow-hidden result-item">
                    <div class="card-body p-3 p-sm-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <h2 class="h5 card-title mb-0">
                                        Penataan ulang trayek angkot
                                    </h2>
                                </a>
                                <div class="small text-muted mt-1">
                                    <span class="me-2">
                                        <i class="bi bi-hash"></i>
                                        <code class="fw-semibold">JGR-2025-000102</code>
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-calendar-event"></i>
                                        17 September 2025
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-journal-text"></i>
                                        Raperda Transportasi
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-info text-dark">
                                Menunggu Tanggapan
                            </span>
                        </div>
                        <p class="text-muted mb-3">
                            Sejumlah trayek tumpang tindih dan menyebabkan kemacetan di
                            titik X. Diusulkan penataan ulang…
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Lihat Status
                        </a>
                    </div>
                </article>

                <article class="card lux-card overflow-hidden result-item">
                    <div class="card-body p-3 p-sm-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <a href="#" class="text-decoration-none">
                                    <h2 class="h5 card-title mb-0">
                                        Pojok ASI di fasilitas umum
                                    </h2>
                                </a>
                                <div class="small text-muted mt-1">
                                    <span class="me-2">
                                        <i class="bi bi-hash"></i>
                                        <code class="fw-semibold">JGR-2025-000077</code>
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-calendar-event"></i>
                                        12 September 2025
                                    </span>
                                    <span class="me-2">
                                        <i class="bi bi-journal-text"></i>
                                        Raperda Kesehatan
                                    </span>
                                </div>
                            </div>
                            <span class="badge bg-success">Selesai</span>
                        </div>
                        <p class="text-muted mb-3">
                            Mohon mempertimbangkan kewajiban penyediaan ruang laktasi di
                            fasilitas publik tertentu…
                        </p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            Lihat Status
                        </a>
                    </div>
                </article>
            </section>

            <!-- Pagination -->
            <nav class="pager-compact" aria-label="Navigasi halaman hasil">
                <button class="btn btn-outline-primary btn-sm" disabled>
                    <i class="bi bi-chevron-left"></i>
                    Sebelumnya
                </button>
                <div class="small text-muted">Halaman 1 / 3</div>
                <button class="btn btn-outline-primary btn-sm">
                    Berikutnya
                    <i class="bi bi-chevron-right"></i>
                </button>
            </nav>

            <!-- Catatan Privasi -->
            <div class="alert alert-light border border-soft mt-4 mb-0" role="alert">
                <i class="bi bi-shield-check me-2 text-royal"></i>
                Nomor laporan digunakan untuk menampilkan status. Data pribadi tidak
                dipublikasi.
            </div>
        </div>
    </div>
@endsection

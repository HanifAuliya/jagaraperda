@extends('layouts.guest')

@section('title', 'Kontak')
@section('main-class', 'wave-touch')

@section('hero')
    <div class="hero">
        <div class="container section pt-3 pb-4 text-center mt-4">
            <h1 class="hero-title mb-2">
                Kontak Kami
            </h1>

            <p class="hero-sub mb-3">
                Silahkan Hubungi kami lewat :
            </p>
        </div>
    </div>

    {{-- Wave divider (tetap di header agar menyatu dengan hero) --}}
    <svg class="wave-bottom" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0,40 C180,70 360,85 540,70 C720,55 900,10 1080,22 C1260,34 1350,52 1440,64 L1440,120 L0,120 Z"
            fill="rgba(255,255,255,.45)"></path>
        <path d="M0,64 C200,96 400,96 600,72 C800,48 1000,0 1200,20 C1320,32 1380,44 1440,56 L1440,120 L0,120 Z"
            fill="var(--bg)"></path>
    </svg>
@endsection
@section('content')
    <!-- ==================== MAIN: KONTAK ==================== -->

    <div class="row g-3 g-lg-4">
        <!-- KIRI: Info Kontak -->
        <section class="col-12 col-lg-5">
            <div class="card panel-simple shadow-sm h-100">
                <div class="card-body p-3 p-lg-4">
                    <h5 class="card-title mb-1">Kontak Kami</h5>
                    <p class="contact-lead text-muted mb-3">
                        Silakan hubungi kami melalui kanal berikut.
                    </p>

                    <ul class="list-unstyled m-0 d-grid gap-3">
                        <li class="contact-item d-flex">
                            <span class="icon-pill me-3"><i class="bi bi-geo-alt"></i></span>
                            <div>
                                <div class="fw-semibold">Alamat</div>
                                <small class="text-muted">
                                    Jl. Lambung Mangkurat No. 18, Banjarmasin, Kalimantan Selatan
                                </small>
                            </div>
                        </li>

                        <li class="contact-item d-flex">
                            <span class="icon-pill me-3"><i class="bi bi-telephone"></i></span>
                            <div>
                                <div class="fw-semibold">Telepon</div>
                                <small class="text-muted">
                                    <a href="tel:+625113366351">(+62) 511-3366351</a>
                                    <span class="mx-1">/</span>
                                    <a href="tel:+625113366352">3366352</a>
                                    <button type="button" class="btn btn-link btn-sm p-0 ms-2 align-baseline copy-btn"
                                        data-copy="+625113366351" aria-label="Salin nomor">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </small>
                            </div>
                        </li>

                        <li class="contact-item d-flex">
                            <span class="icon-pill me-3"><i class="bi bi-envelope"></i></span>
                            <div>
                                <div class="fw-semibold">Email</div>
                                <small class="text-muted">
                                    <a href="mailto:halo@jagarperda.id">halo@jagarperda.id</a>
                                    <button type="button" class="btn btn-link btn-sm p-0 ms-2 align-baseline copy-btn"
                                        data-copy="halo@jagarperda.id" aria-label="Salin email">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </small>
                            </div>
                        </li>

                        <li class="contact-item d-flex">
                            <span class="icon-pill me-3"><i class="bi bi-clock"></i></span>
                            <div>
                                <div class="fw-semibold">Jam Layanan</div>
                                <small class="text-muted">Senin–Jumat, 08.00–16.00 WITA</small>
                            </div>
                        </li>
                    </ul>

                    <div class="d-flex gap-2 mt-3">
                        <a class="social-pill-contact sp-fb" href="#" aria-label="Facebook" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a class="social-pill-contact sp-ig" href="#" aria-label="Instagram" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a class="social-pill-contact sp-x" href="#" aria-label="X" title="X (Twitter)">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    </div>


                </div>
            </div>
        </section>

        <!-- KANAN: Kanal Layanan + Tautan + FAQ (tanpa form) -->
        <section class="col-12 col-lg-7">
            <!-- Kanal Layanan -->
            <div class="card panel-simple shadow-sm">
                <div class="card-body p-3 p-lg-4">
                    <h5 class="card-title mb-1">Kanal Layanan</h5>
                    <p class="text-muted small mb-3">Pilih kanal di bawah untuk menghubungi kami atau menggunakan
                        layanan publik.</p>

                    <div class="row g-2 g-md-3">
                        <div class="col-12 col-sm-6">
                            <a href="tel:+625113366351"
                                class="btn btn-royal w-100 d-flex align-items-center justify-content-center gap-2 contact-cta">
                                <i class="bi bi-telephone"></i> Telepon Kantor
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="mailto:halo@jagarperda.id?subject=Pertanyaan%20Umum"
                                class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 contact-cta">
                                <i class="bi bi-envelope"></i> Kirim Email
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="{{ route('aspirasi.form') }}"
                                class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 contact-cta">
                                <i class="bi bi-megaphone"></i> Ajukan Aspirasi
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="{{ route('aspirasi.tracking') }}"
                                class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 contact-cta">
                                <i class="bi bi-search"></i> Lacak Status
                            </a>
                        </div>
                    </div>

                    <div class="text-muted small mt-2">
                        <i class="bi bi-info-circle me-1"></i> Balasan email biasanya dalam 1–2 hari kerja.
                    </div>
                </div>
            </div>

            <!-- Tautan Cepat -->
            <div class="card panel-simple shadow-sm mt-3">
                <div class="card-body p-3 p-lg-4">
                    <h5 class="card-title mb-2">Tautan Cepat</h5>
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('news.index') }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-newspaper me-2"></i>Publikasi / Berita</span>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                        </div>
                        <div class="col-12 col-md-6">
                            <a href="{{ route('galeri.index') }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-images me-2"></i>Galeri Foto</span>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ singkat -->
            <div class="card panel-simple shadow-sm mt-3">
                <div class="card-body p-3 p-lg-4">
                    <h5 class="card-title mb-2">FAQ</h5>
                    <div class="accordion" id="faqAcc">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="q1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#a1">
                                    Bagaimana cara menyampaikan aspirasi?
                                </button>
                            </h2>
                            <div id="a1" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    Buka halaman <a href="{{ route('aspirasi.form') }}">Ajukan Aspirasi</a>, isi
                                    formulir, dan unggah lampiran bila perlu.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="q2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#a2">
                                    Kapan saya mendapat balasan?
                                </button>
                            </h2>
                            <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    Balasan awal biasanya 1–3 hari kerja, tergantung kompleksitas laporan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="q3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#a3">
                                    Bagaimana cara melacak status laporan?
                                </button>
                            </h2>
                            <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    Gunakan menu <a href="{{ route('aspirasi.tracking') }}">Lacak Status</a> dan
                                    masukkan nomor laporan Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MAP / Lokasi (opsional) -->

        </section>
    </div>



    {{-- JS: tombol salin --}}
    @push('scripts')
        <script>
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.copy-btn');
                if (!btn) return;
                const text = btn.getAttribute('data-copy') || '';
                if (!text) return;
                navigator.clipboard?.writeText(text).then(() => {
                    btn.innerHTML = '<i class="bi bi-clipboard-check"></i>';
                    setTimeout(() => btn.innerHTML = '<i class="bi bi-clipboard"></i>', 1500);
                });
            });
        </script>
    @endpush
@endsection

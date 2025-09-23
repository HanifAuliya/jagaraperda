@extends('layouts.app')

@section('title', 'JAGARPERDA KALSEL — Memberi Makna Partisipasi')

{{-- HERO hanya untuk home --}}
@section('hero')
    <div class="hero">
        <div class="container section pt-3 pb-4 text-center mt-4">
            <h1 class="hero-title mb-2">Wadah Partisipasi Publik</h1>
            <p class="hero-sub mb-3">Sampaikan masukan Anda untuk penyempurnaan Raperda.</p>
            <a href="{{ route('layanan.ajukan') }}" class="btn btn-royal" aria-label="Beri Masukkan">
                <i class="bi bi-megaphone me-2"></i> Klik Untuk Beri Masukkan
            </a>
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
    <div class="row g-3 g-lg-4">
        <!-- KIRI: Pemerintah Provinsi -->
        <section class="col-12 col-lg-6">
            <div class="card panel-simple shadow-sm">
                <div class="card-body p-3 p-lg-4">
                    <h5 class="card-title mb-3">Pemerintah Provinsi</h5>
                    <!-- ============ Slider: Pemerintah Provinsi ============ -->
                    <div id="govCarousel" class="carousel slide mini-carousel" data-bs-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img" src="{{ asset('assets/img/pemprov/gubernur.png') }}"
                                            alt="Gubernur" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Gubernur</div>
                                        <div class="name">H. Muhidin</div>
                                    </div>
                                </article>
                            </div>

                            <div class="carousel-item">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img" src="{{ asset('assets/img/pemprov/wagub.png') }}"
                                            alt="Wakil Gubernur" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Wakil Gubernur</div>
                                        <div class="name">Hasnuryadi Sulaiman</div>
                                    </div>
                                </article>
                            </div>

                            <div class="carousel-item">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img" src="{{ asset('assets/img/dprd/sekda.png') }}"
                                            alt="Sekretaris Daerah" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Sekretaris Daerah</div>
                                        <div class="name">Nama Sekretaris Daerah</div>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <!-- tombol dengan ikon -->
                        <button class="carousel-control-prev mini-prev" type="button" data-bs-target="#govCarousel"
                            data-bs-slide="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="carousel-control-next mini-next" type="button" data-bs-target="#govCarousel"
                            data-bs-slide="next">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        <!-- indikator tidak absolute -->
                        <div class="carousel-indicators mini-indicators">
                            <button type="button" data-bs-target="#govCarousel" data-bs-slide-to="0"
                                class="active"></button>
                            <button type="button" data-bs-target="#govCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#govCarousel" data-bs-slide-to="2"></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- KANAN: Pimpinan DPRD -->
        <section class="col-12 col-lg-6">
            <div class="card panel-simple shadow-sm">
                <div class="card-body p-3 p-lg-4">
                    <h5 class="card-title mb-3">Pimpinan DPRD</h5>

                    <!-- ============ Slider:DPR ============ -->
                    <div id="dprdCarousel" class="carousel slide mini-carousel" data-bs-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img"
                                            src="{{ asset('assets/img/dprd/ketuadprd.png') }}"alt="Ketua DPRD" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Ketua DPRD</div>
                                        <div class="name">Nama Ketua DPRD</div>
                                    </div>
                                </article>
                            </div>

                            <div class="carousel-item">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img" src="{{ asset('assets/img/dprd/wakil1.png') }}"
                                            alt="Wakil Ketua DPRD I" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Wakil Ketua DPRD I</div>
                                        <div class="name">Nama Wakil Ketua I</div>
                                    </div>
                                </article>
                            </div>

                            <div class="carousel-item">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img" src="{{ asset('assets/img/dprd/wakil2.png') }}"
                                            alt="Wakil Ketua DPRD II" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Wakil Ketua DPRD II</div>
                                        <div class="name">Nama Wakil Ketua II</div>
                                    </div>
                                </article>
                            </div>
                            <div class="carousel-item">
                                <article class="avatar-card">
                                    <div class="avatar-photo ratio ratio-1x1">
                                        <img class="avatar-img" src="{{ asset('assets/img/dprd/wakil3.png') }}"
                                            alt="Wakil Ketua DPRD III" />
                                    </div>
                                    <div class="avatar-meta">
                                        <div class="role">Wakil Ketua DPRD II</div>
                                        <div class="name">Nama Wakil Ketua II</div>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <button class="carousel-control-prev mini-prev" type="button" data-bs-target="#dprdCarousel"
                            data-bs-slide="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="carousel-control-next mini-next" type="button" data-bs-target="#dprdCarousel"
                            data-bs-slide="next">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        <div class="carousel-indicators mini-indicators">
                            <button type="button" data-bs-target="#dprdCarousel" data-bs-slide-to="0"
                                class="active"></button>
                            <button type="button" data-bs-target="#dprdCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#dprdCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#dprdCarousel" data-bs-slide-to="3"></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Tagline -->
    <div class="tagline-line text-center py-3 mt-5">
        <span class="tagline-pill">
            <span class="tagline-text">“Memberi Makna Partisipasi”</span>
        </span>
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const tooltipTriggerList = [].slice.call(
                    document.querySelectorAll('[data-bs-toggle="tooltip"]')
                );
                tooltipTriggerList.map((el) => new bootstrap.Tooltip(el));
            });
        </script>
    @endpush
@endsection

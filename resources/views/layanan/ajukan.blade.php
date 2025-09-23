@extends('layouts.app')

@section('title', 'JAGARPERDA KALSEL — Layanan Aspirasi dan Partisipasi Publik')
@section('main-class', 'wave-touch')
{{-- HERO hanya untuk home --}}
@section('hero')
    <div class="hero">
        <div class="container section pt-3 pb-4 text-center mt-4">
            <h1 class="hero-title mb-2">
                Layanan Aspirasi dan Partisipasi Publik
            </h1>
            <p class="hero-sub mb-3">
                Sampaikan masukan Anda untuk penyempurnaan Raperda.
            </p>
            <a href="tracking.html" class="btn btn-royal" aria-label="Lacak status masukan Anda">
                <i class="bi bi-search me-2"></i>
                Lacak status masukan Anda
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
    <div class="row justify-content-center">
        <section class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="card-header card-header-royal mb-3">
                        <h5 class="card-title-royal mb-0 fw-bold">
                            Sampaikan Masukkan Anda
                        </h5>
                    </div>

                    <form id="formAspirasi">
                        <!-- Identitas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama *</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat</label>
                            <input type="text" name="alamat" class="form-control"
                                placeholder="Alamat domisili (opsional)" />
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="nama@contoh.id"
                                    autocomplete="email" />
                            </div>
                        </div>

                        <!-- Raperda -->
                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">
                                Raperda yang Dipilih
                            </label>
                            <select class="form-select select-blue" id="raperdaSelect" name="raperda_id" required>
                                <option value="" selected disabled>Pilih Raperda</option>
                                <option value="administrasi">Peraturan administrasi</option>
                                <option value="fasilitas-publik">Fasilitas Publik</option>
                                <option value="pelayanan">Pengelolaan sampah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Isi -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Masukkan *</label>
                            <input type="text" name="judul" class="form-control"
                                placeholder="Ringkas, jelas (maks. 150 karakter)" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Isi Masukkan *</label>
                            <textarea rows="4" name="isi" class="form-control" placeholder="Uraikan masukan/pertimbangan Anda..."
                                required></textarea>
                        </div>

                        <!-- Lampiran -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lampiran</label>
                            <input type="file" name="lampiran[]" class="form-control" multiple />
                            <small class="text-muted">
                                PDF/JPG/PNG (maks 10 MB per file)
                            </small>
                        </div>

                        <!-- Mode Privasi -->
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="mode_privasi" id="normal"
                                        value="normal" checked />
                                    <label class="form-check-label" for="normal" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Nama Anda tidak akan terpublikasi pada laporan">
                                        Normal
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="mode_privasi" id="anonim"
                                        value="anonim" />
                                    <label class="form-check-label" for="anonim" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Identitas tidak diminta, laporan tetap bisa diproses">
                                        Anonim
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="mode_privasi" id="rahasia"
                                        value="rahasia" />
                                    <label class="form-check-label" for="rahasia" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Identitas hanya diketahui admin, tidak ditampilkan di publik">
                                        Rahasia
                                    </label>
                                </div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        const tooltipTriggerList = [].slice.call(
                                            document.querySelectorAll(
                                                '[data-bs-toggle="tooltip"]'
                                            )
                                        );
                                        tooltipTriggerList.map(
                                            (el) => new bootstrap.Tooltip(el)
                                        );
                                    });
                                </script>
                            </div>

                            <button type="submit" class="btn btn-royal fw-bold px-4">
                                LAPOR!
                            </button>
                        </div>

                        <!-- Consent -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="setuju" required />
                            <label class="form-check-label" for="setuju">
                                Saya setuju dengan ketentuan dan kebijakan privasi.
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            });
        </script>
    @endpush
@endsection

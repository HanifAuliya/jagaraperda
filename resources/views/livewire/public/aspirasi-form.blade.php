@section('title', 'Ajukan Aspirasi — JAGARPERDA KALSEL')
@section('meta_title', 'Ajukan Aspirasi | JAGARPERDA KALSEL')
@section('meta_description',
    'Sampaikan aspirasi Anda terkait Rancangan Peraturan Daerah Kalimantan Selatan secara mudah
    dan cepat. Partisipasi Anda penting dalam proses legislasi yang transparan.')
@section('canonical', route('aspirasi.form'))

{{-- HERO hanya untuk home --}}
@section('hero')
    <!-- HERO -->
    <div class="hero">
        <div class="container section pt-3 pb-4 text-center mt-4">
            <h1 class="hero-title mb-2">
                Layanan Aspirasi dan Partisipasi Publik
            </h1>
            <p class="hero-sub mb-3">
                Sampaikan masukan Anda untuk penyempurnaan Raperda.
            </p>
            <a href="{{ route('aspirasi.tracking') }}" class="btn btn-royal" aria-label="Lacak status masukan Anda">
                <i class="bi bi-search me-2"></i>
                Lacak status masukan Anda
            </a>
        </div>
    </div>

    <!-- wave divider -->
    <svg class="wave-bottom" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
        <!-- layer highlight tipis (opsional) -->
        <path d="M0,40 C180,70 360,85 540,70 C720,55 900,10 1080,22 C1260,34 1350,52 1440,64 L1440,120 L0,120 Z"
            fill="rgba(255,255,255,.45)"></path>
        <!-- layer utama: potong ke warna latar halaman -->
        <path d="M0,64 C200,96 400,96 600,72 C800,48 1000,0 1200,20 C1320,32 1380,44 1440,56 L1440,120 L0,120 Z"
            fill="var(--bg)"></path>
    </svg>
@endsection
@section('main-class', 'wave-touch')

<div class="row justify-content-center">
    <section class="col-lg-8">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <div class="card-header card-header-royal mb-3">
                    <h5 class="card-title-royal mb-0 fw-bold">Sampaikan Masukkan Anda</h5>
                </div>

                <form wire:submit.prevent="submit" id="formAspirasi" class="needs-validation" novalidate>

                    {{-- Judul (wajib) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Masukkan *</label>
                        <input type="text" class="form-control" placeholder="Ringkas, jelas (maks. 150 karakter)"
                            wire:model.defer="judul" required>
                        @error('judul')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Isi (wajib) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi Masukkan *</label>
                        <textarea rows="4" class="form-control" placeholder="Uraikan masukan/pertimbangan Anda..." wire:model.defer="isi"
                            required></textarea>
                        @error('isi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lampiran --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lampiran (opsional)</label>
                        <input type="file" class="form-control" wire:model="files" multiple
                            @if ($files) disabled @endif>
                        <small class="text-muted">PDF/JPG/PNG (maks 10 MB per file)</small>
                        @error('files.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror>

                        {{-- Loading indicator --}}
                        <div wire:loading wire:target="files" class="mt-1">
                            <div class="text-primary small d-flex align-items-center gap-1">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span>Mengunggah file...</span>
                            </div>
                        </div>

                        {{-- Daftar file + tombol hapus --}}
                        @if ($files)
                            <ul class="list-unstyled mt-2">
                                @foreach ($files as $i => $f)
                                    <li
                                        class="d-flex align-items-center justify-content-between border rounded px-2 py-1 mb-1">
                                        <span class="small text-truncate">{{ $f->getClientOriginalName() }}</span>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            wire:click="removeFile({{ $i }})">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Raperda (wajib) --}}
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold">Raperda yang Dipilih *</label>
                        <div wire:ignore>
                            <select id="raperda-select" class="form-select select-blue" autocomplete="off" required>
                                <option value="">Pilih Raperda</option>
                                @foreach ($raperdas as $r)
                                    <option value="{{ $r->id }}">{{ $r->tahun }} — {{ $r->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('raperda_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama (wajib untuk NORMAL/RAHASIA, nonaktif di ANONIM) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama *</label>
                        <input type="text" class="form-control" placeholder="Nama lengkap Anda"
                            wire:model.defer="nama" @disabled($mode_privasi === 'anonim')
                            @if ($mode_privasi !== 'anonim') required @endif>
                        @error('nama')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @if ($mode_privasi === 'anonim')
                            <small class="text-muted">Mode anonim aktif: identitas tidak disimpan.</small>
                        @endif
                    </div>

                    {{-- Email (opsional) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email (opsional)</label>
                        <input type="email" class="form-control" placeholder="nama@contoh.id" autocomplete="email"
                            wire:model.defer="email" @disabled($mode_privasi === 'anonim')>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Mode Privasi --}}
                    <div class="d-flex align-items-center justify-content-between mt-4 flex-wrap gap-3">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="normal" value="normal"
                                    wire:model="mode_privasi">
                                <label class="form-check-label" for="normal" data-bs-toggle="tooltip"
                                    title="Identitas disimpan & dapat ditampilkan di halaman status.">
                                    Normal
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="anonim" value="anonim"
                                    wire:model="mode_privasi">
                                <label class="form-check-label" for="anonim" data-bs-toggle="tooltip"
                                    title="Identitas tidak diminta & tidak disimpan.">
                                    Anonim
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="rahasia" value="rahasia"
                                    wire:model="mode_privasi">
                                <label class="form-check-label" for="rahasia" data-bs-toggle="tooltip"
                                    title="Identitas disimpan untuk admin; tidak ditampilkan di publik.">
                                    Rahasia
                                </label>
                            </div>
                        </div>

                        {{-- Catatan mode, tampil statis di bawah --}}
                        <div class="alert alert-light border mt-3" role="alert" style="border-color:#e5e7eb">
                            <ul class="mb-0 small">
                                <li><strong>Normal:</strong> identitas disimpan dan <em>dapat</em> ditampilkan pada
                                    halaman
                                    status laporan.</li>
                                <li><strong>Anonim:</strong> identitas <em>tidak disimpan</em> sama sekali, laporan
                                    tetap
                                    diproses.</li>
                                <li><strong>Rahasia:</strong> identitas disimpan untuk keperluan verifikasi admin, namun
                                    <em>tidak dipublikasikan</em>. Laporan juga tidak akan muncul pada daftar publik.
                                </li>
                            </ul>
                        </div>

                        {{-- Consent --}}
                        <div class="form-check mt-1 mb-3">
                            <input class="form-check-input" type="checkbox" id="setuju" wire:model="consent">
                            <label class="form-check-label" for="setuju">
                                Saya setuju dengan ketentuan dan kebijakan privasi.
                            </label>
                            @error('consent')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- reCAPTCHA (tengah) --}}

                        <div class="mb-3 text-center" wire:ignore>
                            {!! NoCaptcha::display([
                                'data-callback' => 'onRecaptchaChecked',
                                'data-expired-callback' => 'onRecaptchaExpired',
                            ]) !!}
                            {{-- optional: slot pesan kecil di bawah widget --}}
                            @if ($errors->has('captcha'))
                                <div class="text-danger small">{{ $errors->first('captcha') }}</div>
                            @endif
                        </div>

                        <script>
                            // dipanggil otomatis saat user mencentang captcha
                            window.onRecaptchaChecked = function(token) {
                                @this.set('captcha', token);
                            };
                            // dipanggil saat token expired
                            window.onRecaptchaExpired = function() {
                                @this.set('captcha', null);
                            };
                        </script>

                        {{-- Tombol di kanan --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-royal fw-bold px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove>LAPOR!</span>
                                <span wire:loading>Mengirim...</span>
                            </button>
                        </div>


                    </div>


                    {{-- Honeypot --}}
                    <input type="text" class="d-none" tabindex="-1" autocomplete="off" wire:model="website">
                </form>
            </div>
        </div>


    </section>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener("livewire:init", () => {
            const el = document.getElementById("raperda-select");
            if (!el) return;

            // Destroy kalau sudah ada (misal Livewire reinit)
            if (el.choices) {
                el.choices.destroy();
            }

            const choices = new Choices(el, {
                searchEnabled: true, // aktifkan pencarian (karena daftar raperda bisa panjang)
                itemSelectText: "",
                shouldSort: false,
                allowHTML: false,
                placeholder: true,
                placeholderValue: "Pilih Raperda",
            });
            el.choices = choices;

            // Sinkron ke Livewire ketika user pilih
            el.addEventListener("change", (e) => {
                Livewire.first().set("raperda_id", e.target.value || null);
            });

            // Set nilai awal dari Livewire -> ke Choices
            const currentVal = @this.get('raperda_id');
            if (currentVal) {
                choices.setChoiceByValue(String(currentVal));
            }

            // Kalau mau reset dari server
            Livewire.on("raperda:reset", () => {
                el.choices.clearStore();
            });
        });
    </script>
@endpush

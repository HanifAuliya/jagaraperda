@section('title', 'Lacak Status Aspirasi — JAGARPERDA KALSEL')
@section('meta_title', 'Lacak Aspirasi | JAGARPERDA KALSEL')
@section('meta_description', 'Masukkan nomor laporan atau kata kunci untuk melacak status aspirasi Anda. Transparansi
    proses legislasi Raperda Kalimantan Selatan.')
@section('canonical', route('aspirasi.tracking'))

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">

        {{-- ======= HEADER ======= --}}
        <header class="mb-3">
            <h1 class="h3 fw-bold mb-1 text-royal-ink">Lacak Status Laporan</h1>
            <div class="pg-underline"></div>
            <p class="text-muted mt-2 mb-0">
                Masukkan <span class="fw-semibold">nomor laporan</span>
                laporan Anda.
            </p>
        </header>

        {{-- ======= MODE: SEARCH ======= --}}
        @if ($mode === 'search')
            {{-- Form Pencarian (kata kunci saja) --}}
            <section class="panel-simple p-3 mb-4">
                <form class="row g-2 align-items-center" wire:submit.prevent="search" role="search" autocomplete="off">
                    <div class="col-12 col-md">
                        <label for="q" class="form-label small text-muted mb-1">Kata kunci masukkan</label>
                        <input id="q" type="search" class="form-control"
                            placeholder="Contoh : JRP-YYYY-MM-NNNNNN" enterkeyhint="search" wire:model.defer="q" />
                        {{-- catatan format nomor DIHAPUS agar tidak memancing orang cari nomor --}}
                        <div class="form-text">
                            Format nomor: <code>JRP-YYYY-MM-NNNNNN</code>
                        </div>
                    </div>
                    <div class="col-12 col-md-auto d-flex gap-2">
                        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled"
                            wire:target="search">
                            {{-- spinner saat loading --}}
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"
                                wire:loading wire:target="search"></span>

                            {{-- label normal (hilang saat loading) --}}
                            <span wire:loading.remove wire:target="search">
                                <i class="bi bi-search me-1"></i> Cari
                            </span>

                            {{-- label saat loading --}}
                            <span wire:loading wire:target="search">Mencari…</span>
                        </button>

                        <button class="btn btn-secondary" type="button" wire:click="resetSearch"
                            wire:loading.attr="disabled" wire:target="search">
                            Reset
                        </button>

                    </div>
                </form>
            </section>

            {{-- Ringkasan Hasil --}}
            @if ($q !== '')
                <div class="mb-2">
                    <div class="small text-muted">
                        Ditemukan <span class="fw-semibold">{{ $results ? $results->count() : 0 }}</span>
                        hasil untuk: <span class="fw-semibold">"{{ $q }}"</span>
                    </div>
                </div>
            @endif

            {{-- Daftar Hasil --}}
            <section class="results-stack">
                @forelse($results as $it)
                    @php
                        [$badgeClass, $badgeText] = $this->statusBadge($it->status);
                    @endphp
                    <article class="card lux-card overflow-hidden result-item mb-3">
                        <div class="card-body p-3 p-sm-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                <div>
                                    <a href="javascript:void(0)" class="text-decoration-none">
                                        <h2 class="h5 text-royal-ink card-title mb-0">{{ $it->judul }}</h2>
                                    </a>
                                    <div class="small text-muted mt-1">
                                        <span class="me-2">
                                            <i class="bi bi-hash"></i>
                                            <code class="fw-semibold">{{ $it->tracking_no }}</code>
                                        </span>
                                        <span class="me-2">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ $it->created_at->format('d M Y') }}
                                        </span>
                                        @if ($it->raperda)
                                            <span class="me-2">
                                                <i class="bi bi-journal-text"></i>
                                                {{ $it->raperda->judul }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                            </div>

                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($it->isi), 160) }}
                            </p>

                            {{-- Hanya tampilkan "Lihat Status" jika belum selesai/ditutup --}}
                            <button class="btn btn-outline-primary btn-sm"
                                wire:click="promptPin('{{ $it->tracking_no }}')">
                                Lihat Status
                            </button>

                        </div>
                    </article>
                @empty
                    @if ($q !== '')
                        <div class="alert alert-light border">Tidak ada hasil.</div>
                    @endif
                @endforelse
            </section>

        @endif

        {{-- Panel PIN (jika pengguna klik Lihat Status) --}}
        <div wire:ignore.self class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="pinModalLabel">Buka Status Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                            wire:click="closePinModal"></button>
                    </div>

                    <div class="modal-body">
                        @if ($candidateNo)
                            <div class="mb-2 small text-muted">
                                Memeriksa nomor laporan: <code class="fw-bold">{{ $candidateNo }}</code>
                            </div>
                        @endif

                        <div class="row g-2">
                            <div class="col-12 col-md-8">
                                <input type="password" class="form-control @error('tracking_pin') is-invalid @enderror"
                                    placeholder="PIN 6 digit" inputmode="numeric" maxlength="6"
                                    autocomplete="one-time-code" wire:model.defer="tracking_pin"
                                    wire:keydown.enter="openWithPin">
                                @error('tracking_pin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4 d-grid">
                                <button class="btn btn-primary d-inline-flex align-items-center"
                                    wire:click="openWithPin" wire:loading.attr="disabled" wire:target="openWithPin">

                                    {{-- spinner saat loading (pakai .delay biar gak kedip kalau cepat) --}}
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true" wire:loading.delay wire:target="openWithPin"></span>

                                    {{-- label normal --}}
                                    <span wire:loading.remove wire:target="openWithPin">
                                        <i class="bi bi-unlock me-1"></i> Buka Status
                                    </span>

                                    {{-- label saat loading --}}
                                    <span wire:loading.delay wire:target="openWithPin">
                                        Membuka…
                                    </span>
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="closePinModal">
                            Batal
                        </button>
                    </div>

                </div>
            </div>
        </div>


        {{-- ======= MODE: DETAIL ======= --}}
        @if ($mode === 'detail' && $aspirasi)
            @php
                [$badgeClass, $badgeText] = $this->statusBadge($aspirasi->status);

                // Map tahapan ke angka (data-step) buat progress CSS kamu
                $step = match ($aspirasi->status) {
                    'baru', 'terverifikasi' => 2,
                    'menunggu_tindak_lanjut' => 3,
                    'ditanggapi' => 4,
                    'selesai', 'ditolak', 'kadaluwarsa' => 5,
                    default => 2,
                };
            @endphp

            {{-- Notifikasi nomor laporan --}}
            <div class="alert d-flex align-items-start gap-2"
                style="background: rgba(21,61,138,.08); border:1px solid var(--blue-700); color:var(--ink); border-radius:12px;">
                <i class="bi bi-info-circle-fill" style="font-size:1.1rem;color:var(--blue-700)"></i>
                <div>
                    <div class="fw-semibold">
                        @if ($aspirasi->status === 'selesai')
                            Laporan telah selesai ditangani.
                        @elseif($aspirasi->status === 'balasan_pelapor')
                            Tanggapan Anda terekam. Menunggu Kesimpulan Akhir.
                        @elseif($aspirasi->status === 'ditanggapi')
                            Laporan dalam tahap tanggapan.
                        @elseif($aspirasi->status === 'menunggu_tindak_lanjut')
                            Laporan sedang ditindaklanjuti.
                        @elseif($aspirasi->status === 'terverifikasi' || $aspirasi->status === 'baru')
                            Laporan berhasil dikirim.
                        @elseif($aspirasi->status === 'ditolak')
                            Laporan ditolak/dibatalkan.
                        @elseif($aspirasi->status === 'kadaluwarsa')
                            Laporan kadaluwarsa (melewati tenggat).
                        @endif
                    </div>
                    <div>No. Laporan: <code class="fw-bold" id="noLaporan">{{ $aspirasi->tracking_no }}</code></div>
                    <small class="text-muted">Simpan nomor ini untuk pelacakan status.</small>
                </div>
                <div class="ms-auto">

                    <button type="button"
                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center copy-btn ms-2"
                        data-copy-target="#noLaporan" data-bs-toggle="tooltip" data-bs-title="Salin nomor laporan">
                        <span class="when-idle"><i class="bi bi-clipboard me-1"></i> Salin</span>
                        <span class="when-copied d-none"><i class="bi bi-check2 me-1"></i> Tersalin</span>
                    </button>

                </div>
            </div>

            {{-- Kartu status + rincian --}}
            <div class="card lux-card overflow-hidden">
                <div class="p-3"
                    style="background:linear-gradient(180deg,var(--blue-900),var(--blue-700));color:#fff;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h4 mb-0">
                            @if (in_array($aspirasi->status, ['baru', 'terverifikasi']))
                                Proses Verifikasi
                            @elseif($aspirasi->status === 'menunggu_tindak_lanjut')
                                Proses Tindak Lanjut
                            @elseif(in_array($aspirasi->status, ['ditanggapi', 'balasan_pelapor']))
                                Beri Tanggapan
                            @elseif($aspirasi->status === 'selesai')
                                Selesai
                            @elseif($aspirasi->status === 'ditolak')
                                Ditolak
                            @elseif($aspirasi->status === 'kadaluwarsa')
                                Kadaluwarsa
                            @endif
                        </h2>

                        <span class="badge {{ $badgeClass }}">Status: {{ $badgeText }}</span>
                    </div>
                    <p class="mb-0 opacity-75">
                        @if (in_array($aspirasi->status, ['baru', 'terverifikasi']))
                            Dalam 3 hari kerja, laporan Anda diverifikasi.
                        @elseif($aspirasi->status === 'menunggu_tindak_lanjut')
                            Dalam 5 hari kerja, instansi menindaklanjuti dan membalas.
                        @elseif($aspirasi->status === 'ditanggapi')
                            Anda dapat menanggapi balasan dari instansi dalam 10 hari sejak laporan dibuat (maks 1
                            kali).
                        @elseif($aspirasi->status === 'balasan_pelapor')
                            Tanggapan Anda sudah diterima. Menunggu keputusan akhir dari admin.
                        @elseif($aspirasi->status === 'selesai')
                            Laporan Anda ditindaklanjuti hingga terselesaikan.
                        @elseif($aspirasi->status === 'ditolak')
                            Laporan ditutup oleh admin. Terima kasih atas partisipasinya.
                        @elseif($aspirasi->status === 'kadaluwarsa')
                            Laporan melewati tenggat dan ditutup otomatis.
                        @endif
                    </p>

                </div>

                <div class="card-body">
                    {{-- Rincian Aspirasi --}}
                    <div class="panel-simple p-3 mb-3">
                        @php
                            $mode = $aspirasi->mode_privasi;
                            $isAnon = $mode === 'anonim';
                            $isSecret = $mode === 'rahasia';
                            $showIdentity = $mode === 'normal'; // hanya normal yang ditampilkan
                        @endphp

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="small text-muted mb-1">Raperda</div>
                                <div class="fw-semibold">{{ $aspirasi->raperda?->judul ?? '—' }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="small text-muted mb-1">Tanggal Masukkan</div>
                                <div class="fw-semibold">
                                    {{ $aspirasi->created_at->format('d M Y H:i') }}
                                </div>

                            </div>

                            <div class="col-12">
                                <div class="small text-muted mb-1">Judul Masukkan</div>
                                <div class="fw-semibold">{{ $aspirasi->judul }}</div>
                            </div>
                            <div class="col-12">
                                <div class="small text-muted mb-1">Isi Masukkan</div>
                                <div class="text-muted">{!! nl2br(e($aspirasi->isi)) !!}</div>
                            </div>

                            {{-- Mode Privasi --}}
                            <div class="col-12 col-md-6">
                                <div class="small text-muted mb-1">Mode Privasi</div>
                                <div class="fw-semibold">{{ ucfirst($mode) }}</div>
                            </div>

                            {{-- Identitas Pelapor (tampil hanya saat normal) --}}
                            <div class="col-12 col-md-6">
                                <div class="small text-muted mb-1">Pelapor</div>
                                <div class="fw-semibold">
                                    @if ($showIdentity)
                                        {{ $aspirasi->nama ?? '—' }}
                                    @elseif($isAnon)
                                        Anonim
                                    @elseif($isSecret)
                                        Dirahasiakan
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>

                            {{-- Kontak (email/telepon) hanya saat normal --}}
                            <div class="col-12 col-md-6">
                                <div class="small text-muted mb-1">Kontak</div>
                                @if ($showIdentity)
                                    <div class="fw-semibold text-truncate">
                                        {{ $aspirasi->email ?? '—' }}
                                        @if (!empty($aspirasi->telepon))
                                            <span class="text-muted"> • </span>{{ $aspirasi->telepon }}
                                        @endif
                                    </div>
                                @else
                                    <div class="fw-semibold text-muted">Tidak ditampilkan</div>
                                @endif
                            </div>

                            {{-- Alamat hanya saat normal (opsional jika ada kolomnya) --}}
                            @if ($showIdentity)
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted mb-1">Alamat</div>
                                    <div class="text-muted">{{ $aspirasi->alamat ?? '—' }}</div>
                                </div>
                            @endif

                            {{-- Lampiran --}}
                            <div class="col-12">
                                <div class="small text-muted mb-1">Lampiran</div>
                                <ul class="list-unstyled mb-0 small">
                                    @forelse($aspirasi->files as $f)
                                        @php $rel = \Illuminate\Support\Str::after($f->path, "aspirasi/{$aspirasi->id}/"); @endphp
                                        <li>
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            <a href="{{ route('aspirasi.file', ['aspirasi' => $aspirasi->id, 'path' => $rel]) }}"
                                                target="_blank">
                                                {{ $f->original_name }}
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-muted">Tidak ada lampiran</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        {{-- Catatan privasi dinamis --}}
                        <div class="alert alert-light border mt-3 mb-0" role="alert" style="border-color:#e5e7eb">
                            <i class="bi bi-shield-check me-2" style="color:var(--blue-700)"></i>
                            <span>
                                @if ($showIdentity)
                                    Identitas Anda dapat ditampilkan di halaman ini.
                                @elseif($isSecret)
                                    Identitas Anda hanya diketahui admin dan tidak ditampilkan di publik.
                                @else
                                    Anda mengirim sebagai anonim; identitas tidak dikumpulkan/ditampilkan.
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Balasan Instansi Terbaru (jika ada) --}}
                    @php
                        $lastAdmin = $aspirasi->tanggapan()->where('actor', 'admin')->latest()->first();
                    @endphp
                    @if ($lastAdmin)
                        <div class="panel-simple p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-bold">Balasan Instansi</h6>
                                <span class="badge bg-light text-muted small">
                                    Diterima: {{ $lastAdmin->created_at->format('d M Y H:i') }}
                                </span>

                            </div>
                            <div class="mt-2">
                                <p class="text-muted mb-2">{!! nl2br(e($lastAdmin->isi)) !!}</p>
                                @if ($lastAdmin->file_path)
                                    @php $rel = \Illuminate\Support\Str::after($lastAdmin->file_path, "aspirasi/{$aspirasi->id}/"); @endphp
                                    <ul class="list-unstyled small mb-0">
                                        <li>
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            <a href="{{ route('aspirasi.file', ['aspirasi' => $aspirasi->id, 'path' => $rel]) }}"
                                                target="_blank">Lampiran</a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif

                    @php
                        $reporterReply = $aspirasi->tanggapan()->where('actor', 'pelapor')->latest()->first();
                    @endphp

                    {{-- Balasan Anda --}}
                    @if ($reporterReply && (in_array($aspirasi->status, ['balasan_pelapor', 'selesai']) || $this->reporterAlreadyReplied))
                        <div class="panel-simple p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-bold">Balasan Anda</h6>
                                <span class="badge bg-light text-muted small">
                                    Dikirim: {{ $reporterReply->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <p class="mb-2">{!! nl2br(e($reporterReply->isi)) !!}</p>

                                @if ($reporterReply->file_path)
                                    @php
                                        $rel = \Illuminate\Support\Str::after(
                                            $reporterReply->file_path,
                                            "aspirasi/{$aspirasi->id}/",
                                        );
                                        $name = basename($reporterReply->file_path);
                                    @endphp
                                    <ul class="list-unstyled small mb-0">
                                        <li>
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            <a href="{{ route('aspirasi.file', ['aspirasi' => $aspirasi->id, 'path' => $rel]) }}"
                                                target="_blank">
                                                {{ $name }}
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            </div>

                            {{-- info status --}}
                            @if ($aspirasi->status === 'balasan_pelapor')
                                <div class="alert alert-light border mt-3 mb-0" role="alert"
                                    style="border-color:#e5e7eb">
                                    <div class="d-flex">
                                        <i class="bi bi-clock-history me-2"
                                            style="font-size:1.1rem;color:var(--blue-700)"></i>
                                        <div>
                                            <div class="fw-semibold">Anda sudah membalas</div>
                                            <div class="text-muted small">Menunggu keputusan akhir dari admin.</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif


                    {{-- FORM TANGGAPAN (pelapor 1x saja) --}}
                    @if ($aspirasi->status === 'ditanggapi' && !$this->reporterAlreadyReplied)
                        <form class="mt-3" wire:submit.prevent="sendReply">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggapan Anda *</label>
                                <textarea class="form-control" rows="4" placeholder="Tulis tanggapan Anda..." wire:model.defer="reply_body"></textarea>
                                @error('reply_body')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Lampiran (opsional)</label>

                                {{-- Input file --}}
                                <input type="file" class="form-control" wire:model="reply_file"
                                    @if ($reply_file) disabled @endif>
                                <small class="text-muted">PDF/JPG/PNG, maks 10 MB.</small>

                                @error('reply_file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                {{-- Loading indicator --}}
                                <div wire:loading wire:target="reply_file" class="mt-1">
                                    <div class="text-primary small d-flex align-items-center gap-1">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                        <span>Mengunggah file...</span>
                                    </div>
                                </div>

                                {{-- Tombol hapus/ganti file --}}
                                @if ($reply_file)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            wire:click="$set('reply_file', null)">
                                            <i class="bi bi-x-circle me-1"></i> Hapus/Ganti File
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Kirim Tanggapan
                                </button>
                            </div>
                        </form>
                    @elseif ($aspirasi->status === 'ditanggapi' && $this->reporterAlreadyReplied)
                        <div class="alert alert-light border mt-3" role="alert" style="border-color:#e5e7eb">
                            <div class="d-flex">
                                <i class="bi bi-check2-circle me-2"
                                    style="font-size:1.1rem;color:var(--blue-700)"></i>
                                <div>
                                    <div class="fw-semibold">Anda sudah mengirim satu balasan</div>
                                    <div class="text-muted small">Menunggu tindak lanjut admin.</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @php
                        $finalAdmin = null;
                        if ($aspirasi->status === 'selesai') {
                            // ambil admin reply paling akhir (asumsi sebagai kesimpulan)
                            $finalAdmin = $aspirasi->tanggapan()->where('actor', 'admin')->latest()->first();
                        }
                    @endphp

                    @if ($finalAdmin)
                        <div class="panel-simple p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-bold">Kesimpulan Akhir</h6>
                                <span class="badge bg-light text-muted small">
                                    Ditutup:
                                    {{ $aspirasi->closed_at?->format('d M Y H:i') ?? $finalAdmin->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <p class="mb-2">{!! nl2br(e($finalAdmin->isi)) !!}</p>

                                @if ($finalAdmin->file_path)
                                    @php
                                        $rel = \Illuminate\Support\Str::after(
                                            $finalAdmin->file_path,
                                            "aspirasi/{$aspirasi->id}/",
                                        );
                                        $name = basename($finalAdmin->file_path);
                                    @endphp
                                    <ul class="list-unstyled small mb-0">
                                        <li>
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            <a href="{{ route('aspirasi.file', ['aspirasi' => $aspirasi->id, 'path' => $rel]) }}"
                                                target="_blank">
                                                {{ $name }}
                                            </a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div
                        wire:key="survey-{{ $aspirasi->id }}-{{ (int) ($this->surveySubmitted || $this->hasFeedback) }}">
                        @if ($aspirasi && $aspirasi->status === 'selesai')
                            @if (!($this->surveySubmitted || $this->hasFeedback))
                                <div class="alert alert-light border mt-3" role="alert"
                                    style="border-color:#e5e7eb">
                                    <div class="fw-semibold mb-2">Bagaimana penanganan laporan Anda?</div>

                                    <div
                                        class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                                        <div class="btn-group" role="group" aria-label="Kepuasan">
                                            <input type="radio" class="btn-check" name="kepuasan" id="puas"
                                                wire:model="feedback_rating" value="puas" />
                                            <label class="btn btn-outline-primary" for="puas">Puas</label>

                                            <input type="radio" class="btn-check" name="kepuasan" id="cukup"
                                                wire:model="feedback_rating" value="cukup" />
                                            <label class="btn btn-outline-primary" for="cukup">Cukup</label>

                                            <input type="radio" class="btn-check" name="kepuasan" id="tidak"
                                                wire:model="feedback_rating" value="tidak" />
                                            <label class="btn btn-outline-primary" for="tidak">Tidak Puas</label>
                                        </div>

                                        <button type="button" class="btn btn-royal ms-sm-2"
                                            wire:click="submitFeedback" wire:loading.attr="disabled"
                                            wire:target="submitFeedback">
                                            <span wire:loading.remove wire:target="submitFeedback">Kirim Ulasan</span>
                                            <span wire:loading wire:target="submitFeedback">Mengirim...</span>
                                        </button>
                                    </div>

                                    @error('feedback_rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('feedback_comment')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @else
                                <div class="alert alert-success-subtle border mt-3 small">
                                    Terima kasih, umpan balik Anda tercatat:
                                    <strong>{{ ucfirst($aspirasi->feedback->rating) }}</strong>
                                    @if ($aspirasi->feedback->comment)
                                        — “{{ $aspirasi->feedback->comment }}”
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>



                    {{-- TIMELINE (pakai step mapping di atas) --}}
                    <section class="my-2" id="tahapan" data-step="{{ $aspirasi->step }}">
                        {{-- taruh markup timeline CSS/JS kamu di sini; kita tinggal set data-step --}}
                        <div class="lapor-steps">
                            <div class="steps-line">
                                <div class="steps-progress" id="stepsProgress"></div>
                            </div>
                            <div class="steps-list" role="list">
                                <div class="step" role="listitem">
                                    <div class="step-dot"><i class="bi bi-pencil"></i></div>
                                    <div class="step-text">
                                        <h6 class="fw-bold mb-1">Tulis Laporan</h6>
                                        <p class="text-muted">Laporan diterima.</p>
                                    </div>
                                </div>
                                <div class="step" role="listitem">
                                    <div class="step-dot"><i class="bi bi-arrow-repeat"></i></div>
                                    <div class="step-text">
                                        <h6 class="fw-bold mb-1">Proses Verifikasi</h6>
                                        <p class="text-muted">Pemeriksaan kelengkapan.</p>
                                    </div>
                                </div>
                                <div class="step" role="listitem">
                                    <div class="step-dot"><i class="bi bi-file-text"></i></div>
                                    <div class="step-text">
                                        <h6 class="fw-bold mb-1">Proses Tindak Lanjut</h6>
                                        <p class="text-muted">Instansi menindaklanjuti.</p>
                                    </div>
                                </div>
                                <div class="step" role="listitem">
                                    <div class="step-dot"><i class="bi bi-chat-dots"></i></div>
                                    <div class="step-text">
                                        <h6 class="fw-bold mb-1">Beri Tanggapan</h6>
                                        <p class="text-muted">Konfirmasi pelapor.</p>
                                    </div>
                                </div>
                                <div class="step" role="listitem">
                                    <div class="step-dot"><i class="bi bi-check2"></i></div>
                                    <div class="step-text">
                                        <h6 class="fw-bold mb-1">Selesai</h6>
                                        <p class="text-muted">Ditutup.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Flash OK --}}
                    @if (session('ok'))
                        <div class="alert alert-success mt-3">{{ session('ok') }}</div>
                    @endif

                    {{-- Aksi navigasi --}}
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('aspirasi.tracking') }}" class="btn btn-outline-primary">Kembali ke
                            Pelacakan</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- ======= PREVIEW: ASPIRASI SUKSES ======= --}}
        @if ($mode === 'search' && $successItems->count())
            <section class="section-aspirasi-sukses mt-5">
                {{-- ======= HEADER ======= --}}
                <header class="mb-3">
                    <h1 class="h3 fw-bold mb-1 text-royal-ink">Aspirasi <span class="text-success">Tersampaikan
                        </span>
                    </h1>
                    <div class="pg-underline"></div>
                </header>
                <div class="row g-3 g-md-4">
                    @foreach ($successItems->take(4) as $it)
                        @php
                            $isNormal = $it->mode_privasi === 'normal';
                            $publicNama = $isNormal
                                ? $it->nama ?? '—'
                                : ($it->mode_privasi === 'anonim'
                                    ? 'Anonim'
                                    : 'Dirahasiakan');
                        @endphp

                        <div class="col-12 col-md-6">
                            <article class="card h-100 position-relative shadow-sm hover-lift">
                                <div class="card-body">
                                    {{-- Judul --}}

                                    <h3 class="h6 fw-bold mb-1 text-royal-ink">{{ $it->judul }}</h3>

                                    {{-- Snippet isi --}}
                                    <p class="text-muted mb-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($it->isi), 120) }}
                                    </p>

                                    {{-- Raperda --}}
                                    <div class="small text-muted mb-2">
                                        <i class="bi bi-journal-text me-1"></i>
                                        {{ $it->raperda?->judul ?? '—' }}
                                    </div>

                                    {{-- Identitas --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">{{ mb_substr($publicNama, 0, 1) }}</div>
                                        <span class="small">{{ $publicNama }}</span>
                                    </div>
                                </div>

                                {{-- Stempel Selesai --}}
                                <div class="stamp-selesai">SELESAI</div>
                            </article>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol selengkapnya di tengah bawah --}}
                <div class="text-center mt-5">
                    <a class="btn btn-outline-primary" href="{{ route('aspirasi.sukses') }}">
                        Lihat selengkapnya
                    </a>
                </div>
            </section>
        @endif


    </div>
</div>

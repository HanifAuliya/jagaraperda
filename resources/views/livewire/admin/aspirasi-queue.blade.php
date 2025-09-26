@section('page_title', 'JAGARAPERDA')
@section('title', 'Dashboard - Aspirasi Masuk')

<div>
    {{-- FILTER --}}
    <div class="container-fluid px-0">
        {{-- Header --}}
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="bi bi-inbox me-2"></i> Aspirasi Masuk

            </h4>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- ===== Row 1: Status | Raperda ===== --}}
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-12">
                        <label class="small text-muted d-block mb-1">Pilih Raperda</label>

                        {{-- Wrap dengan kelas khusus biar CSS ter-scope --}}
                        <div class="raperda-filter" wire:ignore>
                            <select id="raperda-filter" class="form-select">
                                <option value="">Semua Raperda</option>
                                @foreach ($raperdaOptions as $r)
                                    <option value="{{ $r->id }}">{{ $r->tahun }} — {{ $r->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                </div>

                {{-- ===== Row 2: Urutkan | Reset + Info Halaman ===== --}}
                <div class="row g-2 align-items-center mt-2">
                    <div class="col-12 col-md-3">
                        <label class="small text-muted d-block mb-1">Status</label>
                        <select class="form-select form-select-sm" wire:model.live="status">
                            <option value="baru">Baru Diajukan</option>
                            <option value="tindak">Terverifikasi & Menunggu Tindak Lanjut</option>
                            <option value="ditanggapi">Ditanggapi</option>
                            <option value="balasan_pelapor">Balasan Pelapor</option>
                            <option value="selesai">Selesai</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="kadaluwarsa">Kadaluwarsa</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="small text-muted d-block mb-1">Urutkan</label>
                        <select class="form-select form-select-sm" wire:model.live="sort">
                            <option value="desc">Terbaru</option>
                            <option value="asc">Terlama</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-md-end align-items-center gap-2 mt-2 mt-md-0">
                        <span class="small text-muted">
                            Halaman {{ $items->currentPage() }} / {{ $items->lastPage() }}
                        </span>
                    </div>
                </div>




                {{-- TABEL --}}
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:56px;" class="d-none d-sm-table-cell">No</th>
                                <th style="width:180px;" class="d-none d-md-table-cell">Detail</th>
                                <th>Judul Aspirasi &amp; Raperda</th>
                                <th style="width:140px;" class="d-none d-sm-table-cell">Status</th>
                                <th style="width:140px;" class="d-none d-lg-table-cell">Tenggat</th>
                                <th style="width:100px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse ($items as $i => $it)
                                @php
                                    [$badgeClass, $badgeText] = $this->statusBadge($it->status);
                                    $rowNo = ($items->firstItem() ?? 1) + $i;
                                    $dl = $this->deadlineInfo($it);
                                    $deadlineAt = $dl['at'];
                                    $deadlineLabel = $dl['label'];
                                    $deadlineNice = $deadlineAt ? $deadlineAt->format('d M Y H:i') : null;
                                    $deadlineHuman = $deadlineAt ? $deadlineAt->diffForHumans() : null;
                                    $deadlineClass = $deadlineAt
                                        ? (now()->greaterThan($deadlineAt)
                                            ? 'text-danger fw-semibold'
                                            : 'text-muted')
                                        : 'text-muted';
                                @endphp

                                <tr wire:key="asp-{{ $it->id }}" class="align-top">
                                    {{-- No (hidden on xs) --}}
                                    <td class="text-muted py-1 pe-2 d-none d-sm-table-cell">{{ $rowNo }}</td>

                                    {{-- Detail (hidden on < md) --}}
                                    <td class="py-1 pe-2 lh-sm d-none d-md-table-cell">
                                        <div><code class="fw-semibold">{{ $it->tracking_no }}</code></div>
                                        <div class="text-muted small">{{ $it->created_at->format('d M Y H:i') }}</div>
                                    </td>

                                    {{-- Judul + Raperda + Snippet + (Mobile meta) --}}
                                    <td class="py-1 pe-2">
                                        <div class="fw-semibold lh-sm text-break clamp-2">{{ $it->judul }}</div>

                                        <div class="d-flex align-items-center gap-1 lh-sm text-break">
                                            @if ($it->raperda)
                                                <i class="bi bi-journal-text text-muted me-1"></i>
                                                @php
                                                    $href = null;
                                                    if (!empty($it->raperda->berkas)) {
                                                        $raw = $it->raperda->berkas;
                                                        $href = \Illuminate\Support\Str::startsWith($raw, [
                                                            'http://',
                                                            'https://',
                                                        ])
                                                            ? $raw
                                                            : Storage::disk('public')->url($raw);
                                                    }
                                                @endphp
                                                @if ($href)
                                                    <a href="{{ $href }}" target="_blank" rel="noopener"
                                                        class="text-decoration-none link-primary clamp-2 small">{{ $it->raperda->judul }}</a>
                                                @else
                                                    <span class="text-muted clamp-2">{{ $it->raperda->judul }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>

                                        @php
                                            $snippet = \Illuminate\Support\Str::limit(
                                                trim(strip_tags($it->isi ?? '')),
                                                180,
                                            );
                                        @endphp
                                        @if ($snippet !== '')
                                            <div class="text-muted small mt-2 clamp-2">{{ $snippet }}</div>
                                        @endif

                                        {{-- MOBILE META: tampilkan Status & Tenggat di bawah judul (sembunyikan di ≥ lg) --}}
                                        <div class="d-lg-none mt-2 small">
                                            <div>
                                                @php $text = strtolower($badgeText) === 'baru' ? 'Baru diajukan' : $badgeText; @endphp
                                                <span
                                                    class="badge bg-{{ $badgeClass }} fw-normal py-1 px-2">{{ $text }}</span>
                                            </div>
                                            <div class="mt-1 text-muted">
                                                @if ($deadlineAt)
                                                    <span class="me-1">{{ $deadlineLabel }}</span>
                                                    <span class="{{ $deadlineClass }}">
                                                        {{ \Carbon\Carbon::parse($deadlineAt)->format('d M Y H:i') }}
                                                        ({{ $deadlineHuman }})
                                                    </span>
                                                @else
                                                    —
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Status (hidden on < sm) --}}
                                    <td class="py-1 pe-2 d-none d-sm-table-cell">
                                        @php $text = strtolower($badgeText) === 'baru' ? 'Baru diajukan' : $badgeText; @endphp
                                        <span
                                            class="badge bg-{{ $badgeClass }} fw-normal py-1 px-2">{{ $text }}</span>
                                    </td>

                                    {{-- Tenggat (hidden on < lg) --}}
                                    <td class="py-1 pe-2 lh-sm d-none d-lg-table-cell">
                                        <div class="text-muted small">{{ $deadlineLabel }}</div>
                                        <div class="{{ $deadlineClass }} text-muted small">
                                            @if ($deadlineAt)
                                                {{ \Carbon\Carbon::parse($deadlineAt)->format('d M Y H:i') }}
                                                <span>({{ $deadlineHuman }})</span>
                                            @else
                                                —
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Aksi: dropdown di mobile, grid di ≥ sm --}}
                                    <td class="py-1 pe-2 text-end">
                                        {{-- Desktop/Tablet (≥ sm): compact btn-group, icon + teks (teks tampil mulai lg) --}}
                                        <div class="d-none d-sm-inline-block">
                                            <div class="btn-group flex-wrap" role="group" aria-label="Aksi">
                                                <button
                                                    class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold"
                                                    data-bs-toggle="tooltip" data-bs-title="Detail" aria-label="Detail"
                                                    wire:click="openDetail({{ $it->id }})">
                                                    <i class="bi bi-eye me-lg-1"></i>
                                                    <span class="d-none d-lg-inline">Detail</span>
                                                </button>

                                                @if ($status === 'baru')
                                                    <button class="btn btn-success btn-sm rounded-pill fw-semibold mt-2"
                                                        data-bs-toggle="tooltip" data-bs-title="Terima"
                                                        aria-label="Terima"
                                                        wire:click="$dispatch('confirm-verify', { id: {{ $it->id }} })">
                                                        <i class="bi bi-check2-circle me-lg-1"></i>
                                                        <span class="d-none d-lg-inline">Terima</span>
                                                    </button>
                                                    <button
                                                        class="btn btn-outline-danger btn-sm rounded-pill fw-semibold mt-2"
                                                        data-bs-toggle="tooltip" data-bs-title="Tolak"
                                                        aria-label="Tolak"
                                                        wire:click="$dispatch('confirm-reject', { id: {{ $it->id }} })">
                                                        <i class="bi bi-x-circle me-lg-1"></i>
                                                        <span class="d-none d-lg-inline">Tolak</span>
                                                    </button>
                                                @endif

                                                @if (in_array($status, ['terverifikasi', 'menunggu_tindak_lanjut', 'tindak']))
                                                    <button
                                                        class="btn btn-outline-primary btn-sm rounded-pill fw-semibold mt-2"
                                                        data-bs-toggle="tooltip" data-bs-title="Tanggapi"
                                                        aria-label="Tanggapi"
                                                        wire:click="openRespond({{ $it->id }})">
                                                        <i class="bi bi-chat-dots me-lg-1"></i>
                                                        <span class="d-none d-lg-inline">Tanggapi</span>
                                                    </button>
                                                @endif

                                                @if ($status === 'balasan_pelapor')
                                                    <button
                                                        class="btn btn-outline-primary btn-sm rounded-pill fw-semibold mt-2"
                                                        data-bs-toggle="tooltip" data-bs-title="Kesimpulan & Tutup"
                                                        aria-label="Kesimpulan dan Tutup"
                                                        wire:click="openRespond({{ $it->id }})">
                                                        <i class="bi bi-flag-checkered me-lg-1"></i>
                                                        <span class="d-none d-lg-inline">Kesimpulan & Tutup</span>
                                                    </button>
                                                @endif

                                                @if (in_array($status, ['ditanggapi', 'balasan_pelapor']))
                                                    <button
                                                        class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold mt-2"
                                                        data-bs-toggle="tooltip" data-bs-title="Tutup"
                                                        aria-label="Tutup"
                                                        wire:click="openClose({{ $it->id }})">
                                                        <i class="bi bi-archive me-lg-1"></i>
                                                        <span class="d-none d-lg-inline">Tutup</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Mobile (< sm): vertikal, semua tombol sama bentuk --}}
                                        <div class="d-sm-none">
                                            <div class="btn-group-vertical w-100" role="group" aria-label="Aksi">
                                                <button
                                                    class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold text-start"
                                                    wire:click="openDetail({{ $it->id }})">
                                                    <i class="bi bi-eye me-2"></i> Detail
                                                </button>

                                                @if ($status === 'baru')
                                                    <button
                                                        class="btn btn-outline-success btn-sm rounded-pill fw-semibold text-start"
                                                        wire:click="$dispatch('confirm-verify', { id: {{ $it->id }} })">
                                                        <i class="bi bi-check2-circle me-2"></i> Terima
                                                    </button>
                                                    <button
                                                        class="btn btn-outline-danger btn-sm rounded-pill fw-semibold text-start"
                                                        wire:click="$dispatch('confirm-reject', { id: {{ $it->id }} })">
                                                        <i class="bi bi-x-circle me-2"></i> Tolak
                                                    </button>
                                                @endif

                                                @if (in_array($status, ['terverifikasi', 'menunggu_tindak_lanjut', 'ditanggapi', 'tindak']))
                                                    <button
                                                        class="btn btn-outline-primary btn-sm rounded-pill fw-semibold text-start"
                                                        wire:click="openRespond({{ $it->id }})">
                                                        <i class="bi bi-chat-dots me-2"></i> Tanggapi
                                                    </button>
                                                @endif

                                                @if ($status === 'balasan_pelapor')
                                                    <button
                                                        class="btn btn-outline-primary btn-sm rounded-pill fw-semibold text-start"
                                                        wire:click="openRespond({{ $it->id }})">
                                                        <i class="bi bi-flag-checkered me-2"></i> Kesimpulan &amp;
                                                        Tutup
                                                    </button>
                                                @endif

                                                @if (in_array($status, ['ditanggapi', 'balasan_pelapor']))
                                                    <button
                                                        class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold text-start"
                                                        wire:click="openClose({{ $it->id }})">
                                                        <i class="bi bi-archive me-2"></i> Tutup
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>



                                    {{-- Mobile (< sm): satu tombol menu --}}
                                    <div class="dropdown d-inline-block d-sm-none actions-mobile">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item"
                                                    wire:click="openDetail({{ $it->id }})">
                                                    <i class="bi bi-eye me-2"></i>Detail
                                                </button>
                                            </li>

                                            @if ($status === 'baru')
                                                <li>
                                                    <button class="dropdown-item"
                                                        wire:click="$dispatch('confirm-verify', { id: {{ $it->id }} })">Terima</button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item"
                                                        wire:click="$dispatch('confirm-reject', { id: {{ $it->id }} })">Tolak</button>
                                                </li>
                                            @endif

                                            @if (in_array($status, ['terverifikasi', 'menunggu_tindak_lanjut', 'ditanggapi', 'tindak']))
                                                <li>
                                                    <button class="dropdown-item"
                                                        wire:click="openRespond({{ $it->id }})">Tanggapi</button>
                                                </li>
                                            @endif

                                            @if ($status === 'balasan_pelapor')
                                                <li>
                                                    <button class="dropdown-item"
                                                        wire:click="openRespond({{ $it->id }})">Kesimpulan &
                                                        Tutup</button>
                                                </li>
                                            @endif

                                            @if (in_array($status, ['ditanggapi', 'balasan_pelapor']))
                                                <li>
                                                    <button class="dropdown-item"
                                                        wire:click="openClose({{ $it->id }})">Tutup</button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-4">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="mt-3">{{ $items->links() }}</div>

            </div>
        </div>
    </div>
    {{-- ================== MODALS ================== --}}

    {{-- Detail Modal --}}
    <div wire:ignore.self class="modal fade asp-modal" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">
                    <div class="w-100 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="asp-title">
                                <span>Detail Aspirasi</span>
                                @if ($active)
                                    <span class="asp-chip">#{{ $active->tracking_no }}</span>
                                    @if ($active->status ?? null)
                                        @php
                                            $statusLabel = [
                                                'baru' => ['text' => 'Baru', 'class' => 'text-secondary'],
                                                'proses' => ['text' => 'Diproses', 'class' => 'text-warning'],
                                                'selesai' => ['text' => 'Selesai', 'class' => 'text-success'],
                                                'ditolak' => ['text' => 'Ditolak', 'class' => 'text-danger'],
                                            ][$active->status] ?? [
                                                'text' => ucfirst($active->status),
                                                'class' => 'text-muted',
                                            ];
                                        @endphp
                                        <span
                                            class="asp-chip {{ $statusLabel['class'] }}">{{ $statusLabel['text'] }}</span>
                                    @endif
                                @endif
                            </div>

                            @if ($active)
                                <div class="asp-meta">
                                    <span class="item"><i class="bi bi-calendar-event"></i>Dibuat:
                                        {{ $active->created_at?->format('d M Y H:i') }}</span>
                                    @if ($active->updated_at && $active->updated_at->ne($active->created_at))
                                        <span class="item"><i class="bi bi-arrow-repeat"></i>Diperbarui:
                                            {{ $active->updated_at->format('d M Y H:i') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                            wire:click="$set('activeId', null)"></button>
                    </div>
                </div>

                {{-- Body --}}
                <div class="modal-body asp-body">
                    {{-- Loading --}}
                    <div class="asp-loading" wire:loading.flex>
                        <div>
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Memuat…
                        </div>
                    </div>

                    <div wire:loading.remove>
                        @if ($active)
                            {{-- Informasi Utama --}}
                            <div class="asp-card">
                                <div class="asp-card-h">Informasi Utama</div>
                                <div class="asp-card-b">
                                    <div class="asp-field">
                                        <div class="asp-label">Judul</div>
                                        <div class="asp-value">{{ $active->judul }}</div>
                                    </div>

                                    <div class="asp-field">
                                        <div class="asp-label">Raperda</div>
                                        <div class="asp-value">{{ $active->raperda?->judul ?? '—' }}</div>
                                    </div>
                                    <div class="asp-field-row row">
                                        <div class="col-md-6">
                                            <div class="asp-field">
                                                <div class="asp-label">Nama Pengirim</div>
                                                <div class="asp-value">{{ $active->nama ?? '—' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="asp-field">
                                                <div class="asp-label">Alamat</div>
                                                <div class="asp-value">{{ $active->alamat ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="asp-field-row row">
                                        <div class="col-md-6">
                                            <div class="asp-field">
                                                <div class="asp-label">Email</div>
                                                <div class="asp-value">{{ $active->email ?? '—' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="asp-field">
                                                <div class="asp-label">Mode</div>
                                                <div class="asp-value">
                                                    {{ $active->mode === 'anonim' ? 'Anonim' : 'Terbuka' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="asp-field mb-0">
                                        <div class="asp-label">Isi</div>
                                        <div class="asp-content">{!! nl2br(e($active->isi)) !!}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Lampiran Pengajuan (punya pelapor) --}}
                            <div class="asp-card">
                                <div class="asp-card-h d-flex justify-content-between align-items-center">
                                    <span>Lampiran Pengajuan</span>
                                    @if ($active->files?->count())
                                        <span class="badge text-bg-light">{{ $active->files->count() }}</span>
                                    @endif
                                </div>
                                <div class="asp-card-b">
                                    @if ($active->files?->count())
                                        <ul class="asp-attach-list">
                                            @foreach ($active->files as $f)
                                                @php
                                                    $rel = \Illuminate\Support\Str::after(
                                                        $f->path,
                                                        "aspirasi/{$active->id}/",
                                                    );
                                                    $ext = strtolower(pathinfo($f->original_name, PATHINFO_EXTENSION));
                                                    $icon = match ($ext) {
                                                        'pdf' => 'bi-file-earmark-pdf',
                                                        'xls', 'xlsx', 'csv' => 'bi-file-earmark-spreadsheet',
                                                        'doc', 'docx' => 'bi-file-earmark-word',
                                                        'jpg', 'jpeg', 'png', 'gif', 'webp' => 'bi-file-earmark-image',
                                                        'zip', 'rar', '7z' => 'bi-file-earmark-zip',
                                                        default => 'bi-file-earmark-text',
                                                    };
                                                @endphp
                                                <li class="asp-attach-item">
                                                    <i class="bi {{ $icon }}"></i>
                                                    <div>
                                                        <a class="fw-semibold text-decoration-none"
                                                            href="{{ route('aspirasi.file', ['aspirasi' => $active->id, 'path' => $rel]) }}"
                                                            target="_blank">lampiran pengajuan</a>
                                                        <div class="meta">
                                                            {{ number_format(($f->size ?? 0) / 1024, 1) }} KB</div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-muted fst-italic small">Tidak ada lampiran pengajuan.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Thread (timeline) --}}
                            <div class="asp-card">
                                <div class="asp-card-h d-flex justify-content-between align-items-center">
                                    <span>Thread</span>
                                    <span class="badge text-bg-light">{{ $active->tanggapan()->count() }}</span>
                                </div>
                                <div class="asp-card-b">
                                    @php
                                        $items = $active
                                            ->tanggapan()
                                            ->withoutGlobalScopes() // kalau ada global scope order
                                            ->reorder() // buang semua ORDER BY sebelumnya
                                            ->orderBy('created_at', 'asc')
                                            ->orderBy('id', 'asc') // jaga-jaga kalau created_at sama detik
                                            ->get();
                                    @endphp

                                    @forelse ($items as $t)
                                        @php $isAdmin = strtoupper($t->actor) !== 'PELAPOR'; @endphp
                                        <div class="asp-tl">
                                            <div class="asp-tl-item">
                                                <span class="dot"></span>
                                                <div class="asp-bubble {{ $isAdmin ? 'admin' : '' }}">
                                                    <div class="asp-head">
                                                        <span class="asp-actor {{ $isAdmin ? 'admin' : 'user' }}">
                                                            <i
                                                                class="bi {{ $isAdmin ? 'bi-shield-check' : 'bi-person' }}"></i>
                                                            {{ strtoupper($t->actor) }}
                                                        </span>
                                                        <span
                                                            class="asp-time">{{ $t->created_at->format('d M Y H:i') }}</span>
                                                    </div>
                                                    <div class="asp-text">{!! nl2br(e($t->isi)) !!}</div>
                                                    @if ($t->file_path)
                                                        @php $relFile = \Illuminate\Support\Str::after($t->file_path, "aspirasi/{$active->id}/"); @endphp
                                                        <div class="asp-file">
                                                            <i class="bi bi-paperclip me-1"></i>
                                                            <a class="text-decoration-none"
                                                                href="{{ route('aspirasi.file', ['aspirasi' => $active->id, 'path' => $relFile]) }}"
                                                                target="_blank">Lampiran</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted">Belum ada tanggapan.</div>
                                    @endforelse


                                </div>
                            </div>

                            {{-- Lampiran Balasan (gabungan dari thread yang ada file) --}}
                            <div class="asp-card">
                                <div class="asp-card-h d-flex justify-content-between align-items-center">
                                    <span>Lampiran Balasan</span>
                                    @php $totalBalasanFile = $active->tanggapan()->whereNotNull('file_path')->count(); @endphp
                                    @if ($totalBalasanFile)
                                        <span class="badge text-bg-light">{{ $totalBalasanFile }}</span>
                                    @endif
                                </div>
                                <div class="asp-card-b">
                                    @php $withFiles = $active->tanggapan()->whereNotNull('file_path')->oldest()->get(); @endphp
                                    @if ($withFiles->count())
                                        <ul class="asp-attach-list">
                                            @foreach ($withFiles as $t)
                                                @php
                                                    $relFile = \Illuminate\Support\Str::after(
                                                        $t->file_path,
                                                        "aspirasi/{$active->id}/",
                                                    );
                                                    $name = basename($relFile);
                                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                                    $icon = match ($ext) {
                                                        'pdf' => 'bi-file-earmark-pdf',
                                                        'xls', 'xlsx', 'csv' => 'bi-file-earmark-spreadsheet',
                                                        'doc', 'docx' => 'bi-file-earmark-word',
                                                        'jpg', 'jpeg', 'png', 'gif', 'webp' => 'bi-file-earmark-image',
                                                        'zip', 'rar', '7z' => 'bi-file-earmark-zip',
                                                        default => 'bi-file-earmark-text',
                                                    };
                                                @endphp
                                                <li class="asp-attach-item">
                                                    <i class="bi {{ $icon }}"></i>
                                                    <div>
                                                        <a class="fw-semibold text-decoration-none"
                                                            href="{{ route('aspirasi.file', ['aspirasi' => $active->id, 'path' => $relFile]) }}"
                                                            target="_blank">lampiran balasan</a>
                                                        <div class="meta">
                                                            {{ strtoupper($t->actor) }} •
                                                            {{ $t->created_at->format('d M Y H:i') }}
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-muted fst-italic small">Tidak ada lampiran balasan.</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-muted">Memuat…</div>
                        @endif
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer">
                    @if ($active?->tracking_no)
                        <span class="me-auto small text-muted">
                            Nomor Laporan: <code class="fw-semibold">{{ $active->tracking_no }}</code>
                            &nbsp;|&nbsp; PIN: <code class="fw-semibold">{{ $active->tracking_pin }}</code>
                            Anda bisa cek tracking di halaman publik dengan mengklik:
                            <a href="{{ route('aspirasi.tracking', ['no' => $active->tracking_no]) }}"
                                class="link-primary fw-semibold" target="_blank" rel="noopener">
                                Halaman Tracking <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </span>
                    @endif

                    <button class="btn btn-dark" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>


    {{-- Respond Modal (balasan / kesimpulan) --}}
    <div wire:ignore.self class="modal fade" id="respondModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit.prevent="respondActive">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if ($active && $active->status === 'balasan_pelapor')
                                Kesimpulan & Tutup
                            @else
                                Tulis Tanggapan
                            @endif
                            @if ($active)
                                <small class="ms-2 text-muted">#{{ $active->tracking_no }}</small>
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                            wire:click="$set('activeId', null)"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Form Balasan --}}
                        <label class="form-label fw-semibold">Isi Balasan</label>
                        <p class="text-muted small mb-2">
                            Tulis balasan atau keterangan Anda pada laporan/aspirasi ini.
                            Gunakan bahasa yang jelas agar mudah dipahami.
                        </p>
                        <textarea class="form-control mb-2" rows="4" wire:model.defer="reply_body"
                            placeholder="{{ $active && $active->status === 'balasan_pelapor' ? 'Ringkas hasil/keputusan...' : 'Tulis balasan untuk pelapor...' }}"></textarea>
                        @error('reply_body')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <div class="mt-5">
                            {{-- Form Lampiran --}}
                            <label class="form-label fw-semibold">Lampiran File (Opsional)</label>
                            <p class="text-muted small mb-2">
                                Jika diperlukan, unggah dokumen pendukung (contoh: surat resmi, foto, atau bukti lain).
                                Format diperbolehkan <span class="fw-semibold">PDF, JPG, PNG</span> dengan ukuran
                                maksimal <span class="fw-semibold">10 MB</span>.
                            </p>
                            {{-- Input file --}}
                            <input type="file" class="form-control" wire:model="reply_file"
                                @if ($reply_file) disabled @endif>

                            @error('reply_file')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">PDF/JPG/PNG, maks 10 MB.</small>

                            {{-- Loading indicator --}}
                            <div wire:loading wire:target="reply_file" class="mt-1">
                                <div class="text-primary small d-flex align-items-center gap-1">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                    <span>Mengunggah file...</span>
                                </div>
                            </div>

                            {{-- Tombol hapus/ganti file (muncul setelah ada file) --}}
                            @if ($reply_file)
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        wire:click="$set('reply_file', null)">
                                        <i class="bi bi-x-circle me-1"></i> Hapus/Ganti File
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled"
                            wire:target="reply_file">
                            Kirim
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Close Modal --}}
    <div wire:ignore.self class="modal fade" id="closeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tutup Aspirasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                        wire:click="$set('activeId', null)"></button>
                </div>
                <div class="modal-body">
                    Tutup aspirasi ini sekarang?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-outline-secondary" wire:click="closeActive">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>
        /* ==== Lock width & cegah "memanjang" ==== */
        .raperda-filter .choices {
            display: block;
            width: 100%;
            max-width: 100%;
        }

        .raperda-filter .choices__inner {
            width: 100%;
            min-height: 2.25rem;
            display: flex;
            align-items: center;
        }

        .raperda-filter .choices__list--single {
            display: block;
            width: 100%;
        }

        .raperda-filter .choices__list--single .choices__item {
            display: block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* input internal jangan memicu resize */
        .raperda-filter .choices__input {
            width: 100% !important;
            min-width: 0 !important;
        }

        /* dropdown di atas elemen lain */
        .raperda-filter .choices__list--dropdown {
            z-index: 1080;
        }
    </style>
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener("livewire:init", () => {
            const el = document.getElementById("raperda-filter");
            if (!el) return;

            // destroy kalau sudah ada instance
            if (el.choices && typeof el.choices.destroy === "function") {
                el.choices.destroy();
                el.choices = null;
            }

            const choices = new Choices(el, {
                searchEnabled: true, // ✅ ada pencarian (karena daftar panjang)
                itemSelectText: "",
                shouldSort: false,
                allowHTML: false,
                placeholder: true,
                placeholderValue: "Semua Raperda",
            });
            el.choices = choices;

            // user pilih -> update Livewire
            el.addEventListener("change", (e) => {
                window.Livewire?.first()?.set("raperdaFilter", e.target.value || null);
            });

            // nilai awal Livewire -> UI
            const currentVal = @this.get("raperdaFilter");
            if (currentVal) {
                choices.setChoiceByValue(String(currentVal));
            } else {
                choices.removeActiveItems();
            }

            // reset event opsional (dipanggil dari server)
            Livewire.on("raperda-filter:reset", () => {
                el.choices.removeActiveItems();
                el.choices.setChoiceByValue(""); // kembali ke placeholder
            });
        });

        document.addEventListener("livewire:init", () => {
            const el = document.getElementById("status-filter");
            if (!el) return;

            // destroy kalau sudah ada
            if (el.choices && typeof el.choices.destroy === "function") {
                el.choices.destroy();
                el.choices = null;
            }

            const choices = new Choices(el, {
                searchEnabled: false, // ❌ status tidak perlu search
                itemSelectText: "",
                shouldSort: false,
                allowHTML: false,
                placeholder: true,
                placeholderValue: "Pilih Status",
            });
            el.choices = choices;

            // user -> Livewire
            el.addEventListener("change", (e) => {
                window.Livewire?.first()?.set("status", e.target.value || null);
            });

            // Livewire -> UI (nilai awal)
            const currentVal = @this.get("status");
            if (currentVal) {
                choices.setChoiceByValue(String(currentVal));
            } else {
                choices.removeActiveItems();
            }

            // opsional: reset dari server
            Livewire.on("status:reset", () => {
                el.choices.removeActiveItems();
                el.choices.setChoiceByValue("");
            });
        });
    </script>
@endpush

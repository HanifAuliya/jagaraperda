{{-- resources/views/livewire/public/aspirasi-sukses-show.blade.php --}}
<div class="container section py-4">
    <nav class="mb-3">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('aspirasi.sukses') }}">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </nav>

    <header class="mb-3">
        <span class="badge bg-success mb-2">Selesai</span>
        <h1 class="h3 fw-bold mb-2 text-royal-ink">{{ $aspirasi->judul }}</h1>
        <div class="text-muted small">
            <i class="bi bi-calendar-event me-1"></i> {{ $aspirasi->created_at->format('d M Y') }}
            @if ($aspirasi->raperda)
                <span class="mx-2">•</span>
                <i class="bi bi-journal-text me-1"></i> {{ $aspirasi->raperda->judul }}
            @endif
        </div>
    </header>

    @php
        $isNormal = $aspirasi->mode_privasi === 'normal';
        $publicNama = $isNormal
            ? $aspirasi->nama ?? '—'
            : ($aspirasi->mode_privasi === 'anonim'
                ? 'Anonim'
                : 'Dirahasiakan');
    @endphp

    <section class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-muted mb-1">Pelapor</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle">{{ mb_substr($publicNama, 0, 1) }}</div>
                        <div class="fw-semibold">{{ $publicNama }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="small text-muted mb-1">Isi Aspirasi</div>
                    <div class="text-muted">{!! nl2br(e($aspirasi->isi)) !!}</div>
                </div>

                {{-- Lampiran (opsional) --}}
                @if ($aspirasi->files && $aspirasi->files->count())
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Lampiran</div>
                        <ul class="list-unstyled mb-0 small">
                            @foreach ($aspirasi->files as $f)
                                @php $rel = \Illuminate\Support\Str::after($f->path, "aspirasi/{$aspirasi->id}/"); @endphp
                                <li>
                                    <i class="bi bi-file-earmark-text me-1"></i>
                                    <a href="{{ route('aspirasi.file', ['aspirasi' => $aspirasi->id, 'path' => $rel]) }}"
                                        target="_blank">
                                        {{ $f->original_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert alert-success d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-check2-circle"></i>
                    <div>Aspirasi ini telah diselesaikan oleh instansi terkait.</div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
    <style>
        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eef7ff;
            font-weight: 700;
            color: #1554a4;
        }
    </style>
@endpush

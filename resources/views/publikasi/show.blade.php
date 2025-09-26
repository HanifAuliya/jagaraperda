@extends('layouts.guest')

@section('title', $raperda->judul . ' — Raperda')

@section('content')
    <main class="container section">
        <nav aria-label="breadcrumb" class="pg-crumb mb-3">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('publikasi.index') }}">Publikasi</a></li>
                <li class="breadcrumb-item active">{{ $raperda->judul }}</li>
            </ol>
        </nav>

        <h2 class="pg-title">{{ $raperda->judul }}</h2>
        <div class="pg-underline mb-3"></div>

        <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
            {{-- Tahun (tanpa badge) --}}
            <span class="text-muted d-inline-flex align-items-center">
                <i class="bi bi-calendar3 me-1"></i>{{ $raperda->tahun ?? '—' }}
            </span>

            {{-- Status --}}
            @if ($raperda->status === 'final')
                <span class="badge rounded-pill bg-success-subtle text-success">
                    <i class="bi bi-check-circle me-1"></i> Final
                </span>
            @else
                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-hourglass-split me-1"></i> Draf
                </span>
            @endif


            {{-- Pemrakarsa (tanpa badge) --}}
            <span class="text-muted d-inline-flex align-items-center">
                <i class="bi bi-building me-1"></i>{{ $raperda->pemrakarsa ?? '—' }}
            </span>
        </div>



        @if ($raperda->berkas)
            <div class="mb-3">
                {{-- Preview inline pakai iframe --}}
                <div class="ratio ratio-16x9 border rounded">
                    <iframe src="{{ asset('storage/' . $raperda->berkas) }}#toolbar=1"
                        title="Preview PDF {{ $raperda->judul }}" allowfullscreen></iframe>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ asset('storage/' . $raperda->berkas) }}" class="btn btn-primary" download>
                    <i class="bi bi-download me-1"></i> Unduh PDF
                </a>
                <a href="{{ asset('storage/' . $raperda->berkas) }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
                </a>
            </div>
        @else
            <div class="alert alert-warning">Berkas PDF belum tersedia.</div>
        @endif
    </main>
@endsection

{{-- resources/views/livewire/public/aspirasi-sukses-list.blade.php --}}
<div class="container section py-4">
    <header class="mb-4">
        <h1 class="display-6 fw-bold">Aspirasi <span class="text-success">Tersampaikan</span></h1>
        <p class="text-muted mb-0">Kumpulan aspirasi yang telah ditindaklanjuti hingga selesai.</p>
        <div class="pg-underline"></div>
    </header>

    <section class="mb-3">
        <form class="row g-2 align-items-center" wire:submit.prevent="search">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="search" class="form-control" placeholder="Cari judul / isi / raperda…"
                        wire:model.defer="q" enterkeyhint="search" autocomplete="off">
                </div>
            </div>

            <div class="col-12 col-md-4">
                <select class="form-select" wire:model="raperdaId">
                    <option value="">Semua Raperda </option>
                    @foreach ($raperdaList as $r)
                        <option value="{{ $r->id }}">{{ $r->judul }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto ">
                <button type="submit" class="btn btn-primary d-flex align-items-center" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="search">
                        <i class="bi bi-search me-1"></i> Cari
                    </span>
                    <span wire:loading wire:target="search">
                        <span class="spinner-border spinner-border-sm me-1"></span> Mencari...
                    </span>
                </button>
            </div>
        </form>

        @if (trim($q) !== '' || $raperdaId !== '')
            <div class="small text-muted mt-2">
                Filter aktif:
                @if (trim($q) !== '')
                    <span class="fw-semibold">"{{ $q }}"</span>
                @endif
                @if ($raperdaId !== '')
                    pada <span class="fw-semibold">{{ $raperdaList->firstWhere('id', $raperdaId)?->judul }}</span>
                @endif
            </div>
        @endif
    </section>

    @if ($items->count() === 0)
        <div class="alert alert-light border">Belum ada data.</div>
    @else
        <div class="row g-3 g-md-3">
            @foreach ($items as $it)
                @php
                    $isNormal = $it->mode_privasi === 'normal';
                    $publicNama = $isNormal
                        ? $it->nama ?? '—'
                        : ($it->mode_privasi === 'anonim'
                            ? 'Anonim'
                            : 'Dirahasiakan');
                @endphp

                <div class="col-12"><!-- full width, 1 item per baris -->
                    <article class="card card-long position-relative hover-lift">
                        <div class="card-body">
                            {{-- Judul (tanpa link) --}}
                            <h2 class="h5 fw-bold mb-1 text-royal-ink">{{ $it->judul }}</h2>

                            {{-- Snippet isi (clamp 2 baris) --}}
                            <p class="text-muted mb-2 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($it->isi), 240) }}
                            </p>

                            {{-- Meta raperda --}}
                            <div class="d-flex align-items-center gap-3 small text-muted mb-2 flex-wrap">
                                <span>
                                    <i class="bi bi-journal-text me-1"></i>
                                    {{ $it->raperda?->judul ?? '—' }}
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $it->created_at->format('d M Y H:i') }}
                                </span>
                            </div>

                            {{-- Identitas --}}
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">{{ mb_substr($publicNama, 0, 1) }}</div>
                                <span class="small">{{ $publicNama }}</span>
                            </div>
                        </div>

                        {{-- Stempel SELESAI (bulat, soft) --}}
                        <div class="stamp-selesai"><span>SELESAI</span></div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $items->onEachSide(1)->links() }}
        </div>
    @endif
</div>

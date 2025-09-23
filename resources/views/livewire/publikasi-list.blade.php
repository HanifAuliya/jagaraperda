<div>
    <div class="pub-filter row g-2 align-items-center mb-3">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="search" class="form-control" placeholder="Cari judul atau kata kunci…"
                    wire:model.live.debounce.500ms="q">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <select class="form-select" wire:model.live="tahun">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-3">
            <select class="form-select" wire:model.live="status">
                <option value="">Semua Status</option>
                <option value="draf">Draf</option>
                <option value="final">Final</option>
            </select>
        </div>
    </div>

    <div id="pubList" class="row g-3 g-lg-4">
        @forelse ($raperdas as $r)
            <div class="col-12 col-md-6 col-lg-4 pub-item">
                <article class="card lux-card pub-card h-100 border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge rounded-pill bg-light text-muted">{{ $r->tahun ?? '—' }}</span>
                            @if ($r->status === 'final')
                                <span class="badge rounded-pill bg-success-subtle text-success">Final</span>
                            @else
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Draf</span>
                            @endif
                        </div>
                        <h3 class="h6 fw-bold mb-2 pub-title text-truncate-2">{{ $r->judul }}</h3>
                        <p class="text-muted small mb-3 text-truncate-3">{{ $r->ringkasan }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            @if ($r->berkas)
                                <a href="{{ route('publikasi.show', $r->slug) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-journal-text me-1"></i> Lihat
                                </a>
                                <a href="{{ asset('storage/' . $r->berkas) }}" class="btn btn-primary btn-sm" download>
                                    <i class="bi bi-download me-1"></i> Unduh PDF
                                </a>
                            @else
                                <span class="text-muted small">Belum ada berkas.</span>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-muted">Tidak ada raperda yang cocok dengan filter.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $raperdas->links() }}
    </div>
</div>

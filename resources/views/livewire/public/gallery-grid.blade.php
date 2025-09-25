<div>
    <div id="galleryGrid" class="row g-3 g-md-4">
        @forelse($photos as $p)
            <div class="col-12 col-sm-6 col-lg-4">
                <a class="gallery-link d-block text-decoration-none" href="{{ Storage::url($p->image) }}" target="_blank"
                    rel="noopener" aria-label="Lihat gambar {{ $p->title }}">

                    <div class="gallery-photo rounded-3" style="aspect-ratio:16/9;">
                        <img src="{{ Storage::url($p->image) }}" alt="{{ $p->title }}" loading="lazy"
                            decoding="async" />
                    </div>

                    <h3 class="gallery-title h6 fw-bold mt-2 mb-0 text-truncate" title="{{ $p->title }}">
                        {{ $p->title }}
                    </h3>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-muted">Belum ada foto.</div>
            </div>
        @endforelse
    </div>

    @if ($hasMore)
        <div class="d-flex justify-content-center mt-4">
            <button class="btn btn-outline-primary" wire:click="loadMore" wire:loading.attr="disabled"
                wire:target="loadMore">
                <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak</span>
                <span wire:loading wire:target="loadMore">Memuat…</span>
            </button>
        </div>
    @endif

</div>

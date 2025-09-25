<div>
    @php
        use Illuminate\Support\Str;
    @endphp

    <section class="row g-4">
        @forelse($items as $n)
            <article class="col-12 col-md-6 col-xl-4">
                <div class="card lux-card news-card h-100">
                    <a href="{{ route('news.show', $n->slug) }}" class="news-media ratio ratio-16x9">
                        <img src="{{ $n->image ? Storage::url($n->image) : '' }}" alt="{{ $n->title }}"
                            class="news-img">
                    </a>
                    <div class="card-body p-3 p-sm-4">
                        <div class="news-meta">
                            <span class="date">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ optional($n->date)->translatedFormat('d F Y') ?? optional($n->date)->format('d M Y') }}
                            </span>
                            @if ($n->place)
                                <span class="dot"></span>
                                <span class="place">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $n->place }}
                                </span>
                            @endif
                        </div>
                        <h3 class="h5 fw-bold news-title mt-2 mb-2">
                            <a href="{{ route('news.show', $n->slug) }}" class="stretched-link">
                                {{ $n->title }}
                            </a>
                        </h3>
                        <p class="text-muted mb-0">
                            {{ Str::limit($n->description, 140) }}
                        </p>
                    </div>
                    <div class="news-underline"></div>
                </div>
            </article>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-muted">Belum ada publikasi.</div>
            </div>
        @endforelse
    </section>

    <nav class="d-flex justify-content-center mt-4" aria-label="Navigasi halaman publikasi">
        {{ $items->links('pagination::bootstrap-5') }}
    </nav>
</div>

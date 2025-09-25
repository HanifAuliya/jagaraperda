@extends('layouts.guest')

@section('title', $news->title)

@section('content')
    <main id="content" class="container section">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                <nav aria-label="breadcrumb" class="pg-crumb mb-2">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Publikasi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ \Illuminate\Support\Str::limit($news->title, 40) }}</li>
                    </ol>
                </nav>

                <h1 class="pg-title">{{ $news->title }}</h1>
                <div class="pg-underline mb-3"></div>

                <div class="d-flex align-items-center gap-2 text-muted mb-3">
                    <i class="bi bi-calendar-event"></i>
                    <span>{{ optional($news->date)->translatedFormat('d F Y') ?? optional($news->date)->format('d M Y') }}</span>
                    @if ($news->place)
                        <span class="dot"
                            style="width:4px;height:4px;border-radius:999px;background:var(--gold);display:inline-block;"></span>
                        <i class="bi bi-geo-alt"></i>
                        <span>{{ $news->place }}</span>
                    @endif
                </div>

                @if ($news->image)
                    <div class="ratio ratio-16x9 mb-3">
                        <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}" class="w-100 h-100"
                            style="object-fit:cover;">
                    </div>
                @endif

                <article class="lead" style="white-space:pre-line;">
                    {{ $news->description }}
                </article>
            </div>
        </div>
    </main>
@endsection

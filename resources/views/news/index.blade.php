@extends('layouts.guest')

@section('title', 'Berita & Informasi — JAGARPERDA KALSEL')
@section('meta_title', 'Berita | JAGARPERDA KALSEL')
@section('meta_description', 'Ikuti berita terbaru seputar Raperda, kegiatan aspirasi publik, dan perkembangan legislasi
    di Kalimantan Selatan.')
@section('canonical', route('news.index'))


@section('content')
    <main id="content" class="container section">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-11">
                <div class="mb-4">
                    <nav aria-label="breadcrumb" class="pg-crumb">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active" aria-current="page">Publikasi</li>
                        </ol>
                    </nav>
                    <h2 class="pg-title mt-1">Berita Terbaru</h2>
                    <div class="pg-underline"></div>
                </div>

                @livewire('public.news-index')
            </div>
        </div>
    </main>
@endsection

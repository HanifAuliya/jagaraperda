@extends('layouts.guest')

@section('title', 'Galeri Dokumentasi — JAGARPERDA KALSEL')
@section('meta_title', 'Galeri | JAGARPERDA KALSEL')
@section('meta_description', 'Lihat dokumentasi kegiatan partisipasi publik, sosialisasi Raperda, dan momen penting lain
    di JAGARPERDA Kalimantan Selatan.')
@section('canonical', route('galeri.index'))


@section('content')
    <main id="content" class="container section">
        <div class="mb-4">
            <nav aria-label="breadcrumb" class="pg-crumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active" aria-current="page">Photo Gallery</li>
                </ol>
            </nav>
            <h2 class="pg-title mt-1">Photo Gallery</h2>
            <div class="pg-underline"></div>
        </div>

        @livewire('public.gallery-grid')
    </main>
@endsection

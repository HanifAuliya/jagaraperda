@extends('layouts.guest')

@section('title', 'Daftar Raperda ' . date('Y') . ' — JAGARPERDA KALSEL')
@section('meta_title', 'Daftar Raperda ' . date('Y') . ' | JAGARPERDA KALSEL')
@section('meta_description',
    'Lihat daftar Raperda ' .
    date('Y') .
    ' lengkap dengan naskah akademik. Ikuti
    perkembangannya dan sampaikan aspirasi Anda untuk membangun Kalimantan Selatan yang lebih baik.')
@section('canonical', route('publikasi.index'))



@section('content')
    <main id="content" class="container section">
        <div class="mb-4">
            <nav aria-label="breadcrumb" class="pg-crumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active" aria-current="page">Daftar</li>
                </ol>
            </nav>
            <h2 class="pg-title mt-1">Publikasi Raperda</h2>
            <div class="pg-underline"></div>
        </div>

        @livewire('publikasi-list')
    </main>
@endsection

@extends('layouts.app')

@section('title', 'Publikasi Raperda — JAGARPERDA KALSEL')

@section('content')
    <main id="content" class="container section">
        <div class="mb-4">
            <nav aria-label="breadcrumb" class="pg-crumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active" aria-current="page">Publikasi</li>
                </ol>
            </nav>
            <h2 class="pg-title mt-1">Publikasi Raperda</h2>
            <div class="pg-underline"></div>
        </div>

        @livewire('publikasi-list')
    </main>
@endsection

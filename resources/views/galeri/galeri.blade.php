@extends('layouts.guest')

@section('title', 'Photo Gallery')

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

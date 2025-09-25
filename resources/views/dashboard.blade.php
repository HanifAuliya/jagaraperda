@extends('layouts.app')

@section('title', 'Dashboard - JAGARPERDA')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Flash message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Statistik ringkas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <i class="bi bi-file-earmark-text fs-2 text-primary mb-2"></i>
                    <h5 class="fw-bold mb-1">Total Raperda</h5>
                    <p class="display-6 fw-semibold mb-0"></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <i class="bi bi-check-circle fs-2 text-success mb-2"></i>
                    <h5 class="fw-bold mb-1">Final</h5>
                    <p class="display-6 fw-semibold mb-0"></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <i class="bi bi-hourglass-split fs-2 text-warning mb-2"></i>
                    <h5 class="fw-bold mb-1">Draf</h5>
                    <p class="display-6 fw-semibold mb-0">{{ $drafRaperda ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

@endsection

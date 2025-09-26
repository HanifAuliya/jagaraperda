@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <main class="container section wave-touch">
        <div class="mb-4">
            <nav aria-label="breadcrumb" class="pg-crumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
            <h2 class="pg-title mt-1">Profil</h2>
            <div class="pg-underline"></div>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success border-0 shadow-sm mb-3" role="alert" id="flashSaved">
                <i class="bi bi-check-circle me-1"></i> Perubahan profil tersimpan.
            </div>
        @endif

        <div class="row g-3 g-lg-4">
            {{-- <!-- Info Profil -->
            <div class="col-12 col-lg-6">
                <div class="card panel-simple shadow-sm h-100">
                    <div class="card-body p-3 p-lg-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div> --}}

            <!-- Ubah Password -->
            <div class="col-12 col-lg-6">
                <div class="card panel-simple shadow-sm h-100">
                    <div class="card-body p-3 p-lg-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- <!-- Hapus Akun -->
            <div class="col-12">
                <div class="card panel-simple shadow-sm">
                    <div class="card-body p-3 p-lg-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div> --}}
        </div>
    </main>

    @push('scripts')
        <script>
            // auto-hide flash
            setTimeout(() => document.getElementById('flashSaved')?.remove(), 2400);
        </script>
    @endpush
@endsection

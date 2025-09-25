@extends('layouts.guest')

@section('title', 'Masuk - JAGARAPERDA KALSEL')
@section('main-class', 'wave-touch')

@section('hero')
    <div class="hero">
        <div class="container section pt-3 pb-4 text-center mt-4">
        </div>
    </div>

    {{-- Wave divider (tetap di header agar menyatu dengan hero) --}}
    <svg class="wave-bottom" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0,40 C180,70 360,85 540,70 C720,55 900,10 1080,22 C1260,34 1350,52 1440,64 L1440,120 L0,120 Z"
            fill="rgba(255,255,255,.45)"></path>
        <path d="M0,64 C200,96 400,96 600,72 C800,48 1000,0 1200,20 C1320,32 1380,44 1440,56 L1440,120 L0,120 Z"
            fill="var(--bg)"></path>
    </svg>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-lg-5">

                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo" style="height:48px">
                        <h1 class="h4 fw-bold mt-3">Masuk ke Akun</h1>
                        <p class="text-muted m-0">Silakan masukkan email dan kata sandi Anda</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="nama@contoh.id" autocomplete="email" required autofocus />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password + toggle --}}
                        <div class="mb-5">
                            <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                            <div class="input-group input-group-lg">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="password"
                                    autocomplete="current-password" required minlength="6" />
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="small" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                            @endif
                        </div> --}}

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold btn-lg">Masuk</button>
                        </div>
                    </form>

                    {{-- @if (Route::has('register'))
                        <div class="text-center mt-3">
                            <small>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></small>
                        </div>
                    @endif --}}

                </div>
            </div>
        </div>
    </div>
@endsection

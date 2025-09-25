@extends('layouts.guest')

@section('title', 'JAGARPERDA KALSEL — Memberi Makna Partisipasi')

@section('content')
    <div class="row justify-content-center">
        <section class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="card-header card-header-royal mb-3">
                        <h5 class="card-title-royal mb-0 fw-bold">
                            Sampaikan Masukkan Anda
                        </h5>
                    </div>

                    {{-- Nanti ganti pakai Livewire: <livewire:aspirasi-form /> --}}
                    {{-- Untuk sekarang, letakkan HTML form static-mu di sini dulu: --}}
                    @include('partials.form-aspirasi')

                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const tooltipTriggerList = [].slice.call(
                    document.querySelectorAll('[data-bs-toggle="tooltip"]')
                );
                tooltipTriggerList.map((el) => new bootstrap.Tooltip(el));
            });
        </script>
    @endpush
@endsection

<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JAGARPERDA KALSEL')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles

    @stack('styles') {{-- halaman boleh nyuntik style tambahan --}}


    {{-- Favicon & app icons --}}
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#153d8a">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    <meta name="theme-color" content="#153d8a">
    <meta name="msapplication-TileColor" content="#153d8a">

    {{-- (Opsional) favicon sesuai tema terang/gelap --}}
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-light-32.png') }}" media="(prefers-color-scheme: light)"> --}}
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-dark-32.png') }}"  media="(prefers-color-scheme: dark)"> --}}

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    {{-- choices --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

</head>

<body>
    {{-- Page Loader --}}
    <div id="appLoader" role="status" aria-live="polite" aria-label="Memuat">
        <div class="loader-box">
            <div class="loader-ring" aria-hidden="true"></div>
            <div class="loader-title">WEB JAGA RAPERDA</div>
            <div class="loader-sub">Memuat…</div>
        </div>

        {{-- wave dekor bawah (opsional) --}}
        <svg class="loader-wave" viewBox="0 0 1440 180" preserveAspectRatio="none" aria-hidden="true">
            <defs>
                <linearGradient id="waveGrad" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0" stop-color="var(--gold)" stop-opacity="0.35" />
                    <stop offset="1" stop-color="var(--bg)" stop-opacity="0" />
                </linearGradient>
            </defs>
            <path d="M0,80 C240,130 480,0 720,50 C960,100 1200,40 1440,80 L1440,180 L0,180 Z" fill="url(#waveGrad)" />
        </svg>
    </div>

    <noscript>
        <style>
            #appLoader {
                display: none !important
            }
        </style>
    </noscript>

    {{-- HEADER (navbar + wave + optional hero placeholder) --}}
    @include('partials.header')

    {{-- ==================== MAIN: BERANDA (simple, 3x3 triangle) ==================== --}}
    <main class="container section @yield('main-class')">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    @livewireScripts
    @stack('scripts') {{-- halaman boleh nyuntik script tambahan --}}
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    {{-- Load script reCAPTCHA v2 --}}
    {!! NoCaptcha::renderJs() !!}

    <script>
        (function() {
            const loader = document.getElementById('appLoader');
            const show = () => {
                if (!loader) return;
                loader.classList.remove('is-hidden');
                document.body.classList.add('is-loading');
            };
            const hide = () => {
                if (!loader) return;
                loader.classList.add('is-hidden');
                document.body.classList.remove('is-loading');
            };

            // Tampilkan saat awal (sebelum window load)
            show();
            // Sembunyikan setelah semua asset siap
            window.addEventListener('load', () => setTimeout(hide, 200));

            // Integrasi Livewire: tampilkan loader jika request > 400ms (anti flicker)
            document.addEventListener('livewire:load', () => {
                let t = null;
                Livewire.hook('message.sent', () => {
                    clearTimeout(t);
                    t = setTimeout(show, 400);
                });
                Livewire.hook('message.processed', () => {
                    clearTimeout(t);
                    hide();
                });
            });
        })();
    </script>
</body>

</html>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Admin')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @vite(['resources/sass/dashboard.scss', 'resources/js/app.js'])

    @stack('styles')
    {{-- Favicon & app icons --}}
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="mask-icon" href="{{ asset('favicon/safari-pinned-tab.svg') }}" color="#153d8a">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    {{-- choices --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    {{-- SEO minimal --}}
    @php
        $metaTitle = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', 'JAGARPERDA KALSEL')));
        $metaDesc = trim(
            $__env->yieldContent(
                'meta_description',
                'Portal aspirasi & partisipasi publik Raperda Kalimantan Selatan.',
            ),
        );
        $canonical = $__env->yieldContent('canonical', url()->current());
        $metaRobots = $__env->yieldContent('meta_robots', 'index,follow');
    @endphp

    <link rel="canonical" href="{{ $canonical }}" />
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="author" content="JAGARPERDA KALSEL">


</head>

<body class="{{ session('sidebar_collapsed') ? 'sidebar-collapsed' : '' }}">
    <div id="app" class="d-flex">

        {{-- Sidebar --}}
        <nav id="sidebar" class="flex-shrink-0 sidebar bg-dark text-white">
            <div class="sidebar-inner p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <a href="{{ route('admin.raperdas.index') }}"
                        class="d-flex align-items-center text-white text-decoration-none fw-bold">
                        <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo" class="me-2"
                            style="height:32px; width:auto;">
                        ADMIN PANEL
                    </a>
                    <button class="btn btn-sm btn-outline-light d-lg-none" id="sidebarClose" aria-label="Tutup sidebar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <hr class="sidebar-rule">
                <ul class="nav nav-pills flex-column gap-1 mb-auto">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="nav-link sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('admin.raperdas.index') }}"
                            class="nav-link sidebar-link {{ request()->is('admin/raperdas*') ? 'active' : '' }}">
                            <i class="bi bi-file-text me-2"></i> Raperda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.aspirasi.queue') }}"
                            class="nav-link sidebar-link {{ request()->routeIs('admin.aspirasi.queue') ? 'active' : '' }}">
                            <i class="bi bi-inboxes me-2"></i> Aspirasi Masuk
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.news') }}"
                            class="nav-link sidebar-link {{ request()->routeIs('admin.news') ? 'active' : '' }}">
                            <i class="bi bi-newspaper me-2"></i> Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.news') }}"
                            class="nav-link sidebar-link {{ request()->routeIs('admin.news') ? 'active' : '' }}">
                            <i class="bi bi-newspaper me-2"></i> Berita
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.galeri') }}"
                            class="nav-link sidebar-link {{ request()->routeIs('admin.galeri') ? 'active' : '' }}">
                            <i class="bi bi-images me-2"></i> Galeri
                        </a>
                    </li>


                    {{-- <li><a href="#" class="nav-link sidebar-link"><i class="bi bi-people me-2"></i> Pengguna</a>
                    </li> --}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link sidebar-link btn btn-link text-start w-100">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </nav>

        {{-- Backdrop untuk mobile --}}
        <div id="sidebarBackdrop" class="sidebar-backdrop d-lg-none" tabindex="-1" aria-hidden="true"></div>

        {{-- Konten utama --}}
        <div class="flex-grow-1 content-wrap">
            <header class="content-topbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-royal-ghost d-lg-none" id="sidebarOpen" aria-label="Buka sidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <h4 class="mb-0">@yield('page_title')</h4>
                </div>
                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm"
                    aria-label="Kembali ke halaman utama" rel="home">
                    <i class="bi bi-house-door me-1"></i> Halaman Utama
                </a>

            </header>

            <main class="main-content p-4">
                {{-- Flash toast --}}
                @if (session('success'))
                    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
                @endif

                @yield('content')

                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


</body>

</html>

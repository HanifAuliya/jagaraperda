<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Admin')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/sass/dashboard.scss', 'resources/js/app.js'])
    @stack('styles')
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
                    <li class="nav-item">
                        <a href="{{ route('admin.raperdas.index') }}"
                            class="nav-link sidebar-link {{ request()->is('admin/raperdas*') ? 'active' : '' }}">
                            <i class="bi bi-file-text me-2"></i> Raperda
                        </a>
                    </li>
                    <li><a href="#" class="nav-link sidebar-link"><i class="bi bi-people me-2"></i> Pengguna</a>
                    </li>
                    <li><a href="#" class="nav-link sidebar-link"><i class="bi bi-box-arrow-right me-2"></i>
                            Keluar</a></li>
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
                <div class="d-flex align-items-center gap-2">
                    {{-- area aksi global / user menu kalau nanti perlu --}}
                </div>
            </header>

            <main class="p-4">
                {{-- Flash toast --}}
                @if (session('success'))
                    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>

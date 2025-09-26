<header class="topbar">
    {{-- NAV --}}
    <nav class="navbar navbar-expand-lg navbar-dark topnav">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo" style="height: 40px; width:auto" />
                <span class="lh-1">
                    <strong class="d-none d-sm-inline">JAGARAPERDA</strong>
                    <strong class="d-inline d-sm-none">JAGARAPERDA</strong>
                    <small class="d-block opacity-75">Kalimantan Selatan</small>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-3 align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}"
                            @if (request()->is('/')) aria-current="page" @endif>
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('layanan/*') ? 'active active-parent' : '' }}"
                            href="#" id="navLayanan" role="button" data-bs-toggle="dropdown"
                            aria-expanded="{{ request()->is('layanan/*') ? 'true' : 'false' }}"
                            @if (request()->is('layanan/*')) aria-current="page" @endif>
                            Layanan
                            <i class="bi bi-chevron-down ms-1 dropdown-caret" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navLayanan">
                            <li>
                                <a class="dropdown-item {{ request()->is('layanan/tracking') ? 'active' : '' }}"
                                    href="{{ route('aspirasi.tracking') }}">
                                    <i class="bi bi-search me-2"></i> Tracking Masukkan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('layanan/ajukan') ? 'active' : '' }}"
                                    href="{{ route('aspirasi.form') }}">
                                    <i class="bi bi-megaphone me-2"></i> Ajukan Masukkan
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('publikasi.index') ? 'active' : '' }}"
                            href="{{ route('publikasi.index') }}"
                            @if (request()->routeIs('publikasi.index')) aria-current="page" @endif>
                            Raperda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('galeri.index') ? 'active' : '' }}"
                            href="{{ route('galeri.index') }}"
                            @if (request()->routeIs('galeri.index')) aria-current="page" @endif>
                            Galeri
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.index') ? 'active' : '' }}"
                            href="{{ route('news.index') }}"
                            @if (request()->routeIs('news.index')) aria-current="page" @endif>
                            Publikasi
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}"
                            href="{{ route('kontak') }}" @if (request()->routeIs('kontak')) aria-current="page" @endif>
                            Kontak
                        </a>
                    </li>

                    {{-- TAMU: tombol Masuk --}}
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                                    href="{{ route('login') }}"
                                    @if (request()->routeIs('login')) aria-current="page" @endif>
                                    Login
                                </a>
                            </li>
                        @endif
                    @endguest

                    {{-- LOGIN: tombol Dashboard --}}
                    @auth
                        @php
                            // pilih nama route dashboard yang ada di project-mu
                            $dashRoute = Route::has('admin.dashboard')
                                ? 'admin.dashboard'
                                : (Route::has('admin.dashboard.admin')
                                    ? 'admin.dashboard.admin'
                                    : (Route::has('dashboard')
                                        ? 'dashboard'
                                        : null));
                        @endphp

                        @if ($dashRoute)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs($dashRoute . '*') ? 'active' : '' }}"
                                    href="{{ route($dashRoute) }}"
                                    @if (request()->routeIs($dashRoute . '*')) aria-current="page" @endif>
                                    Dashboard
                                </a>
                            </li>
                        @endif
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    <div class="gold-rule"></div>

    {{-- HERO hanya muncul jika @section('hero') diisi; kalau tidak, lewati --}}
    @hasSection('hero')
        @yield('hero')
    @endif

</header>

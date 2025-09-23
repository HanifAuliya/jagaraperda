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
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                            href="{{ url('/') }}">Beranda</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('layanan/*') ? 'active' : '' }}"
                            href="#" id="navLayanan" role="button" data-bs-toggle="dropdown">
                            Layanan
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navLayanan">
                            <li>
                                <a class="dropdown-item {{ request()->is('layanan/tracking') ? 'active' : '' }}"
                                    href="{{ route('layanan.tracking') }}">
                                    <i class="bi bi-search me-2"></i> Tracking Masukkan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('layanan/ajukan') ? 'active' : '' }}"
                                    href="{{ route('layanan.ajukan') }}">
                                    <i class="bi bi-megaphone me-2"></i> Ajukan Masukkan
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#">Raperda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Publikasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kontak</a></li>
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

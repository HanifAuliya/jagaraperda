<footer class="footer-lux mt-5">
    <div class="gold-rule"></div>

    <div class="container-slim py-4 py-lg-5">
        <div class="row g-4">
            <div class="col-12 col-md-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Logo" style="height: 32px; width:auto" />
                    <div class="lh-1">
                        <strong>JAGARAPERDA</strong>
                        <small class="d-block opacity-75">Kalimantan Selatan</small>
                    </div>
                </div>
                <p class="footer-tagline mb-3">Wadah partisipasi publik untuk penyempurnaan Raperda.</p>
                <div class="d-flex gap-2">
                    <a class="social-pill" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a class="social-pill" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a class="social-pill" href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <h6 class="footer-head">Menu</h6>
                <ul class="list-unstyled m-0">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="#">Tentang</a></li>
                    <li><a href="{{ route('aspirasi.form') }}">Ajukan Masukkan</a></li>
                    <li><a href="{{ route('aspirasi.tracking') }}">Tracking</a></li>
                    <li><a href="#">Publikasi</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-4">
                <h6 class="footer-head">Kontak</h6>
                <ul class="list-unstyled m-0 small">
                    <li class="mb-1"><i class="bi bi-geo-alt me-1"></i>Jl. Lambung Mangkurat No. 18, Banjarmasin</li>
                    <li class="mb-1"><i class="bi bi-telephone me-1"></i>(0511)-3366351 - 3366352</li>
                    <li><i class="bi bi-envelope me-1"></i><a href="mailto:halo@jagarperda.id">halo@jagarperda.id</a>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="footer-hr my-3" />
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small">
            <span>© <span id="year"></span> JAGARAPERDA KALSEL</span>
            <div class="d-flex align-items-center gap-2">
                <a href="#">Kebijakan Privasi</a><span class="sep">•</span><a href="#">Kontak</a>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
@endpush

<section>
    <header class="mb-3">
        <h5 class="mb-1">Informasi Profil</h5>
        <p class="text-muted mb-0">Perbarui nama dan alamat email akun Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="row g-3">
        @csrf
        @method('patch')

        <div class="col-12">
            <label for="name" class="form-label fw-semibold">Nama</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" name="email" type="email"
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}"
                required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="alert alert-warning d-flex align-items-center gap-2 mt-2 mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div class="flex-grow-1">
                        Alamat email Anda belum terverifikasi.
                        <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
                            Kirim ulang email verifikasi
                        </button>
                    </div>
                </div>
                @if (session('status') === 'verification-link-sent')
                    <div class="text-success small mt-2">
                        <i class="bi bi-check-circle"></i> Tautan verifikasi baru telah dikirim.
                    </div>
                @endif
            @endif
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn btn-royal px-4" type="submit">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
        </div>
    </form>
</section>

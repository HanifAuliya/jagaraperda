<section>
    <header class="mb-3">
        <h5 class="mb-1">Ubah Kata Sandi</h5>
        <p class="text-muted mb-0">Pastikan kata sandi yang kuat dan berbeda dari sebelumnya.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="row g-3">
        @csrf
        @method('put')

        {{-- Sandi saat ini --}}
        @php
            $errBag = $errors->updatePassword ?? $errors; // fallback kalau bag tidak ada
        @endphp

        <div class="col-12">
            <label for="current_password" class="form-label fw-semibold">Kata sandi saat ini</label>
            <input id="current_password" name="current_password" type="password"
                class="form-control {{ $errBag->has('current_password') ? 'is-invalid' : '' }}"
                autocomplete="current-password" required>
            @if ($errBag->has('current_password'))
                <div class="invalid-feedback">{{ $errBag->first('current_password') }}</div>
            @endif
        </div>

        {{-- Sandi baru --}}
        <div class="col-12 col-md-6">
            <label for="password" class="form-label fw-semibold">Kata sandi baru</label>
            <input id="password" name="password" type="password"
                class="form-control {{ $errBag->has('password') ? 'is-invalid' : '' }}" autocomplete="new-password"
                required>
            @if ($errBag->has('password'))
                <div class="invalid-feedback">{{ $errBag->first('password') }}</div>
            @endif
        </div>

        {{-- Konfirmasi --}}
        <div class="col-12 col-md-6">
            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi kata sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                class="form-control {{ $errBag->has('password_confirmation') ? 'is-invalid' : '' }}"
                autocomplete="new-password" required>
            @if ($errBag->has('password_confirmation'))
                <div class="invalid-feedback">{{ $errBag->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn btn-royal px-4" type="submit">
                <i class="bi bi-shield-lock me-1"></i> Perbarui Password
            </button>
        </div>
    </form>

    {{-- SweetAlert sukses (redirect back dari Fortify membawa session status) --}}
    @if (session('status') === 'password-updated')
        <script>
            // panggil Swal langsung (tanpa Livewire) setelah render
            window.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Kata sandi berhasil diperbarui.',
                    icon: 'success',
                    timer: 1800,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                });
            });
        </script>
    @endif
</section>

<section>
    <header class="mb-3">
        <h5 class="mb-1 text-danger">Hapus Akun</h5>
        <p class="text-muted mb-0">Tindakan ini permanen. Semua data terkait akun akan dihapus.</p>
    </header>

    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        <i class="bi bi-trash me-1"></i> Hapus Akun
    </button>

    <!-- Modal konfirmasi -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">Masukkan kata sandi untuk melanjutkan penghapusan akun.</p>

                        <div class="mb-2">
                            <label for="delete_password" class="form-label fw-semibold">Kata sandi</label>
                            <input id="delete_password" name="password" type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="••••••••" required>
                            @if ($errors->userDeletion->has('password'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Hapus Permanen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

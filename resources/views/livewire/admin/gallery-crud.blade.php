<div>
    <div class="section-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-images me-2"></i> Manajemen Galeri Foto
        </h4>
        <button class="btn btn-primary" wire:click="openCreate">
            <i class="bi bi-plus-circle me-1"></i> Tambah Foto
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-2 mb-3 align-items-center">
                <div class="col-12 col-md-6">
                    <div class="input-group" style="max-width: 420px;">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Cari judul foto..."
                            wire:model.live.debounce.400ms="q">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th style="width:110px">Gambar</th>
                            <th>Judul</th>
                            <th style="width:110px">Aktif</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $it)
                            <tr>
                                <td>{{ $items->firstItem() + $loop->index }}</td>
                                <td>
                                    @if ($it->image)
                                        <img src="{{ Storage::url($it->image) }}" alt="{{ $it->title }}"
                                            class="rounded" style="width:96px;height:64px;object-fit:cover;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $it->title }}</td>
                                <td>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $it->active ? 'checked' : '' }}
                                            wire:click="toggleActive({{ $it->id }})">
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1"
                                        wire:click="openEdit({{ $it->id }})"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger" wire:click="askDelete({{ $it->id }})"><i
                                            class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada foto.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $items->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $mode === 'create' ? 'Tambah Foto' : 'Edit Foto' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                        wire:click="closeForm"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul *</label>
                        <input type="text" class="form-control" wire:model="title">
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar {{ $mode === 'create' || !$hasImage ? '*' : '' }}</label>
                        <input type="file" class="form-control" wire:model="image_upload" accept="image/*">
                        <div class="form-text">JPG/PNG/WebP, maks 5 MB.
                            {{ $mode === 'edit' && $hasImage ? 'Kosongkan jika tidak ingin mengganti.' : 'Wajib diunggah.' }}
                        </div>
                        @error('image_upload')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <div wire:loading wire:target="image_upload" class="small text-muted">Mengunggah...</div>
                    </div>

                    <div class="mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model="active" id="activeSwitch">
                            <label class="form-check-label"
                                for="activeSwitch">{{ $active ? 'Aktif' : 'Nonaktif' }}</label>
                        </div>
                    </div>

                    @if ($image_upload)
                        <div class="border rounded p-2 text-center">
                            <div class="small text-muted mb-1">Pratinjau</div>
                            <img src="{{ $image_upload->temporaryUrl() }}" class="img-fluid rounded"
                                style="max-height:200px;object-fit:cover;" />
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeForm">Batal</button>
                    <button class="btn btn-primary" wire:click="save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

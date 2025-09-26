<div>
    {{-- Flash toast --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    {{-- Header --}}
    <div class="section-header d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-text me-2"></i> Manajemen Raperda
        </h4>
        <button class="btn btn-primary" wire:click="openCreate">
            <i class="bi bi-plus-circle me-1"></i> Tambah Raperda
        </button>
    </div>


    {{-- Card tabel --}}
    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Search --}}
            <div class="input-group mb-3" style="max-width: 300px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" placeholder="Cari judul/tahun..."
                    wire:model.live.debounce.400ms="q">
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Judul</th>
                            <th>Pemrakarsa</th>
                            <th style="width:120px">Status</th>
                            <th style="width:100px">Tahun</th>
                            <th style="width:100px">Aktif</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i)
                            <tr>
                                <td>{{ $items->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $i->judul }}</div>
                                    @if ($i->ringkasan)
                                        <div class="small text-muted text-truncate" style="max-width: 420px;">
                                            {{ Str::limit($i->ringkasan, 120) }}
                                        </div>
                                    @endif

                                    @if ($i->berkas)
                                        <a href="{{ Storage::url($i->berkas) }}" target="_blank"
                                            class="small text-primary no-underline">
                                            <i class="bi bi-file-pdf me-1"></i> Lihat PDF
                                        </a>
                                    @endif

                                </td>
                                <td>{{ $i->pemrakarsa ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $i->status === 'final' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($i->status) }}
                                    </span>
                                </td>
                                <td>{{ $i->tahun ?? '—' }}</td>
                                <td>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" {{ $i->aktif ? 'checked' : '' }}
                                            wire:click="toggleAktif({{ $i->id }})">
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1"
                                        wire:click="openEdit({{ $i->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="askDelete({{ $i->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="raperdaModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $mode === 'create' ? 'Tambah Raperda' : 'Edit Raperda' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"
                        wire:click="closeForm"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Judul *</label>
                            <input type="text" class="form-control" wire:model="judul">
                            @error('judul')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Pemrakarsa {{-- * kalau ingin wajib --}}</label>
                            <input type="text" class="form-control" wire:model="pemrakarsa"
                                placeholder="Mis. OPD pengusul">
                            @error('pemrakarsa')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <select class="form-select" wire:model="tahun">
                                <option value="" disabled hidden>-- Pilih Tahun --</option>
                                @for ($y = date('Y'); $y >= 2000; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>

                            @error('tahun')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model="status">
                                <option value="draf">Draf</option>
                                <option value="final">Final</option>
                            </select>
                            @error('status')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Ringkasan *</label>
                            <textarea class="form-control" rows="3" wire:model="ringkasan" placeholder="Uraian singkat isi Raperda"></textarea>
                            @error('ringkasan')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Berkas PDF {{ $mode === 'create' || !$hasBerkas ? '*' : '' }}
                            </label>

                            {{-- Input file --}}
                            <input type="file" class="form-control" wire:model="berkas_upload"
                                accept="application/pdf" @if ($berkas_upload) disabled @endif>

                            <div class="form-text">
                                Maks. 10 MB.
                                {{ $mode === 'edit' && $hasBerkas ? 'Kosongkan jika tidak ingin mengganti.' : 'Wajib diunggah.' }}
                            </div>

                            {{-- Error validasi --}}
                            @error('berkas_upload')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror

                            {{-- Loading indikator saat unggah file --}}
                            <div wire:loading wire:target="berkas_upload" class="mt-1">
                                <div class="text-primary small d-flex align-items-center gap-1">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                    <span>Mengunggah berkas...</span>
                                </div>
                            </div>

                            {{-- Tombol hapus/ganti file --}}
                            @if ($berkas_upload)
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        wire:click="$set('berkas_upload', null)">
                                        <i class="bi bi-x-circle me-1"></i> Hapus/Ganti File
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="aktif" id="aktifSwitch">
                                <label class="form-check-label"
                                    for="aktifSwitch">{{ $aktif ? 'Aktif' : 'Nonaktif' }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeForm">Batal</button>
                    <button class="btn btn-primary" wire:click="save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

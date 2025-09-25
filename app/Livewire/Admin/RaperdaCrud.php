<?php

namespace App\Livewire\Admin;

use App\Models\Raperda;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;



class RaperdaCrud extends Component
{
    use WithPagination, WithFileUploads;

    public $raperdaId = null;
    public $judul, $tahun, $status = 'draf', $aktif = true, $ringkasan;
    public $berkas_upload;

    public $showForm = false;
    public $mode = 'create';
    public $q = '';
    public $hasBerkas = false; // penanda apakah data edit sudah punya PDF

    protected function rules()
    {
        // aturan dasar
        $rules = [
            'judul'      => 'required|string|max:200',
            'tahun'      => 'nullable|digits:4',
            'status'     => 'required|in:draf,final',
            'aktif'      => 'boolean',
            'ringkasan'  => 'required|string|min:20', // bisa disesuaikan
        ];

        // PDF: wajib saat create, dan saat edit kalau belum ada file
        $pdfRule = ($this->mode === 'create' || ($this->mode === 'edit' && !$this->hasBerkas))
            ? 'required|file|mimes:pdf|max:10240'
            : 'nullable|file|mimes:pdf|max:10240';

        $rules['berkas_upload'] = $pdfRule;
        return $rules;
    }

    public function render()
    {
        $items = Raperda::when(
            $this->q,
            fn($q) =>
            $q->where('judul', 'like', "%{$this->q}%")
                ->orWhere('tahun', 'like', "%{$this->q}%")
        )
            ->latest()
            ->paginate(5);

        return view('livewire.admin.raperda-crud', compact('items'));
    }

    /** Bangun nama file: 2025-judul-yang-aman.pdf */
    private function buildFilename(string $judul, ?string $tahun, string $ext = 'pdf'): string
    {
        $slug = Str::slug($judul, '-');
        $y = $tahun ?: date('Y');
        return "{$y}-{$slug}.{$ext}";
    }

    /** Pastikan path unik di disk public/raperdas; hormati $exceptPath agar tidak bentrok dengan file milik sendiri */
    private function uniquePath(string $filename, ?string $exceptPath = null): string
    {
        $disk = Storage::disk('public');
        $dir = 'raperdas';
        $path = "{$dir}/{$filename}";
        if ($exceptPath && $path === $exceptPath) {
            return $path; // sudah aman: sama dengan file lama
        }
        if (!$disk->exists($path)) {
            return $path;
        }
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $i = 2;
        do {
            $candidate = "{$dir}/{$name}-{$i}.{$ext}";
            $i++;
        } while ($candidate !== $exceptPath && $disk->exists($candidate));
        return $candidate;
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->mode = 'create';
        $this->hasBerkas = false;
        $this->tahun = date('Y'); // default tahun sekarang
        $this->dispatch('modal-show', id: 'raperdaModal');
    }

    public function openEdit($id)
    {
        $r = Raperda::findOrFail($id);
        $this->raperdaId   = $r->id;
        $this->judul       = $r->judul;
        $this->tahun       = $r->tahun;
        $this->status      = $r->status;
        $this->aktif       = (bool) $r->aktif;
        $this->ringkasan   = $r->ringkasan;
        $this->berkas_upload = null;

        $this->mode = 'edit';
        $this->hasBerkas = !empty($r->berkas);
        $this->dispatch('modal-show', id: 'raperdaModal');
    }

    public function closeForm()
    {
        $this->dispatch('modal-hide', id: 'raperdaModal');
    }

    public function save()
    {
        $data = $this->validate();

        // default: set aktif sesuai toggle
        $data['aktif'] = (bool) $this->aktif;

        // Ambil record lama kalau mode edit
        $old = null;
        $oldPath = null;
        if ($this->mode === 'edit' && $this->raperdaId) {
            $old = Raperda::findOrFail($this->raperdaId);
            $oldPath = $old->berkas; // bisa null
        }

        // ====== 1) Jika ADA upload baru ======
        if ($this->berkas_upload) {
            $ext = $this->berkas_upload->getClientOriginalExtension(); // sudah aman: mimes:pdf
            $filename = $this->buildFilename($this->judul, $this->tahun, $ext);
            // cari path unik; kalau sama dengan $oldPath biarkan (overwrite), kalau beda dan sudah ada → kasih suffix
            $targetPath = $this->uniquePath($filename, $oldPath);

            // Jika path lama beda dan ada file lama → hapus dulu supaya tidak menghapus file baru
            if ($oldPath && $oldPath !== $targetPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan file baru tepat ke targetPath
            $this->berkas_upload->storeAs('', $targetPath, 'public'); // '' karena targetPath sudah termasuk folder
            $data['berkas'] = $targetPath;

            // ====== 2) Jika TIDAK ada upload baru (EDIT SAJA) → rename bila judul/tahun berubah ======
        } elseif ($this->mode === 'edit' && $oldPath) {
            // hitung nama file yang seharusnya
            $currentExt = pathinfo($oldPath, PATHINFO_EXTENSION) ?: 'pdf';
            $desiredFilename = $this->buildFilename($this->judul, $this->tahun, $currentExt);
            $desiredPath = "raperdas/{$desiredFilename}";

            if ($desiredPath !== $oldPath) {
                // cari path unik (hindari tabrakan dengan file lain)
                $newPath = $this->uniquePath($desiredFilename, $oldPath);

                // rename/move di storage
                if (!Storage::disk('public')->exists($newPath)) {
                    Storage::disk('public')->move($oldPath, $newPath);
                    $data['berkas'] = $newPath;
                } else {
                    // fallback: kalau entah bagaimana sudah ada (harusnya tidak), buat suffix lagi
                    $name = pathinfo($desiredFilename, PATHINFO_FILENAME);
                    $ext  = pathinfo($desiredFilename, PATHINFO_EXTENSION);
                    $altPath = $this->uniquePath("{$name}-" . uniqid() . ".{$ext}", $oldPath);
                    Storage::disk('public')->move($oldPath, $altPath);
                    $data['berkas'] = $altPath;
                }
            }
            // else: nama sama → biarkan, tidak perlu set $data['berkas']
        }

        // Simpan DB
        if ($this->mode === 'create') {
            // Pastikan create selalu punya berkas karena rules 'required'
            Raperda::create($data);
            $this->dispatch('swal', title: 'Berhasil', text: 'Raperda ditambahkan.', icon: 'success');
        } else {
            $old->update($data);
            $this->dispatch('swal', title: 'Tersimpan', text: 'Raperda diperbarui.', icon: 'success');
        }

        $this->resetForm();
        $this->showForm = false;
        $this->resetPage();
        $this->dispatch('modal-hide', id: 'raperdaModal');
    }


    public function askDelete($id)
    {
        $this->dispatch('confirm-delete', id: $id, title: 'Hapus raperda?', text: 'Tindakan ini tidak bisa dibatalkan.');
    }

    #[On('delete-confirmed')]
    public function deleteConfirmed($id)
    {
        $r = Raperda::findOrFail($id);
        if ($r->berkas && Storage::disk('public')->exists($r->berkas)) {
            Storage::disk('public')->delete($r->berkas);
        }
        $r->delete();
        $this->dispatch('swal', title: 'Dihapus', text: 'Data berhasil dihapus.', icon: 'success');
        $this->resetPage();
    }

    public function toggleAktif($id)
    {
        $r = Raperda::findOrFail($id);
        $r->aktif = !$r->aktif;
        $r->save();
        $this->dispatch('swal', title: 'Tersimpan', text: 'Status aktif diperbarui.', icon: 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'raperdaId',
            'judul',
            'tahun',
            'status',
            'aktif',
            'ringkasan',
            'berkas_upload',
            'hasBerkas'
        ]);
        $this->status = 'draf';
        $this->aktif = true;
        $this->hasBerkas = false;
        $this->resetValidation();
    }
}

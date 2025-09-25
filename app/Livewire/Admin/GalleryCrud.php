<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\GalleryPhoto;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class GalleryCrud extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $photoId = null;
    public $title = '';
    public $active = true;
    public $image_upload; // uploaded file

    public $q = '';
    public $mode = 'create';
    public $hasImage = false;

    protected function rules()
    {
        $imgRule = ($this->mode === 'create' || ($this->mode === 'edit' && !$this->hasImage))
            ? 'required|image|mimes:jpg,jpeg,png,webp|max:5120' // 5 MB
            : 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';

        return [
            'title' => 'required|string|max:200',
            'active' => 'boolean',
            'image_upload' => $imgRule,
        ];
    }

    public function render()
    {
        $items = GalleryPhoto::when($this->q, function ($q) {
            $q->where('title', 'like', "%{$this->q}%");
        })
            ->latest()
            ->paginate(9);

        return view('livewire.admin.gallery-crud', compact('items'))->layout('layouts.app');
    }

    // Build filename like: 2025-09-25-judul-unik.jpg
    private function buildFilename(string $title, string $ext): string
    {
        $slug = Str::slug($title, '-');
        return now()->format('Y-m-d') . '-' . $slug . '.' . $ext;
    }

    private function uniquePath(string $filename, ?string $exceptPath = null): string
    {
        $disk = Storage::disk('public');
        $dir = 'gallery';
        $path = "$dir/$filename";
        if ($exceptPath && $path === $exceptPath) return $path;
        if (!$disk->exists($path)) return $path;
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $i = 2;
        do {
            $candidate = "$dir/{$name}-{$i}.{$ext}";
            $i++;
        } while ($candidate !== $exceptPath && $disk->exists($candidate));
        return $candidate;
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->mode = 'create';
        $this->active = true;
        $this->dispatch('modal-show', id: 'galleryModal');
    }

    public function openEdit($id)
    {
        $p = GalleryPhoto::findOrFail($id);
        $this->photoId = $p->id;
        $this->title = $p->title;
        $this->active = (bool) $p->active;
        $this->image_upload = null;
        $this->mode = 'edit';
        $this->hasImage = !empty($p->image);
        $this->dispatch('modal-show', id: 'galleryModal');
    }

    public function closeForm()
    {
        $this->dispatch('modal-hide', id: 'galleryModal');
    }

    public function save()
    {
        $data = $this->validate();
        $data['active'] = (bool) $this->active;

        $old = null;
        $oldPath = null;
        if ($this->mode === 'edit' && $this->photoId) {
            $old = GalleryPhoto::findOrFail($this->photoId);
            $oldPath = $old->image;
        }

        if ($this->image_upload) {
            $ext = strtolower($this->image_upload->getClientOriginalExtension());
            $filename = $this->buildFilename($this->title, $ext);
            $targetPath = $this->uniquePath($filename, $oldPath);

            if ($oldPath && $oldPath !== $targetPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            // store under public/gallery
            $this->image_upload->storeAs('', $targetPath, 'public');
            $data['image'] = $targetPath;
        }

        if ($this->mode === 'create') {
            GalleryPhoto::create([
                'title' => $data['title'],
                'active' => $data['active'],
                'image' => $data['image'] ?? null,
            ]);
            $this->dispatch('swal', title: 'Berhasil', text: 'Foto ditambahkan.', icon: 'success');
        } else {
            $old->update([
                'title' => $data['title'],
                'active' => $data['active'],
                'image' => $data['image'] ?? $old->image,
            ]);
            $this->dispatch('swal', title: 'Tersimpan', text: 'Foto diperbarui.', icon: 'success');
        }

        $this->resetForm();
        $this->resetPage();
        $this->dispatch('modal-hide', id: 'galleryModal');
    }

    public function askDelete($id)
    {
        $this->dispatch('confirm-delete', id: $id, title: 'Hapus foto?', text: 'Tindakan ini tidak bisa dibatalkan.');
    }

    #[On('delete-confirmed')]
    public function deleteConfirmed($id)
    {
        $p = GalleryPhoto::findOrFail($id);
        if ($p->image && Storage::disk('public')->exists($p->image)) {
            Storage::disk('public')->delete($p->image);
        }
        $p->delete();
        $this->dispatch('swal', title: 'Dihapus', text: 'Foto berhasil dihapus.', icon: 'success');
        $this->resetPage();
    }

    public function toggleActive($id)
    {
        $p = GalleryPhoto::findOrFail($id);
        $p->active = !$p->active;
        $p->save();
        $this->dispatch('swal', title: 'Tersimpan', text: 'Status aktif diperbarui.', icon: 'success');
    }

    public function resetForm()
    {
        $this->reset(['photoId', 'title', 'active', 'image_upload', 'hasImage']);
        $this->active = true;
        $this->resetValidation();
    }
}

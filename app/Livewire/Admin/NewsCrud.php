<?php

namespace App\Livewire\Admin;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class NewsCrud extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $newsId = null;
    public $title = '';
    public $description = '';
    public $date;
    public $place = '';
    public $active = true;
    public $image_upload; // file upload

    public $mode = 'create';
    public $hasImage = false;
    public $q = '';

    protected function rules()
    {
        $imgRule = ($this->mode === 'create' || ($this->mode === 'edit' && !$this->hasImage))
            ? 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
            : 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';

        return [
            'title'       => 'required|string|max:200',
            'description' => 'required|string|min:20',
            'date'        => 'required|date',
            'place'       => 'nullable|string|max:150',
            'active'      => 'boolean',
            'image_upload' => $imgRule,
        ];
    }

    public function render()
    {
        $items = News::when($this->q, function ($q) {
            $q->where('title', 'like', "%{$this->q}%")
                ->orWhere('place', 'like', "%{$this->q}%");
        })
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('livewire.admin.news-crud', compact('items'))->layout('layouts.app');
    }

    private function buildFilename(string $title, string $ext): string
    {
        $slug = Str::slug($title, '-');
        return now()->format('YmdHis') . '-' . $slug . '.' . $ext;
    }

    private function uniquePath(string $filename, ?string $exceptPath = null): string
    {
        $disk = Storage::disk('public');
        $dir  = 'news';
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
        $this->date = now()->format('Y-m-d'); // default hari ini
        $this->dispatch('modal-show', id: 'newsModal');
    }

    public function openEdit($id)
    {
        $n = News::findOrFail($id);
        $this->newsId      = $n->id;
        $this->title       = $n->title;
        $this->description = $n->description;
        $this->date        = optional($n->date)->format('Y-m-d');
        $this->place       = $n->place;
        $this->active      = (bool) $n->active;
        $this->image_upload = null;

        $this->mode     = 'edit';
        $this->hasImage = !empty($n->image);

        $this->dispatch('modal-show', id: 'newsModal');
    }

    public function closeForm()
    {
        $this->dispatch('modal-hide', id: 'newsModal');
    }

    public function save()
    {
        $data = $this->validate();
        $data['active'] = (bool) $this->active;

        $old = null;
        $oldPath = null;
        if ($this->mode === 'edit' && $this->newsId) {
            $old = News::findOrFail($this->newsId);
            $oldPath = $old->image;
        }

        if ($this->image_upload) {
            $ext = strtolower($this->image_upload->getClientOriginalExtension());
            $filename = $this->buildFilename($this->title, $ext);
            $targetPath = $this->uniquePath($filename, $oldPath);

            if ($oldPath && $oldPath !== $targetPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $this->image_upload->storeAs('', $targetPath, 'public');
            $data['image'] = $targetPath;
        }

        if ($this->mode === 'create') {
            News::create([
                'title'       => $data['title'],
                'description' => $data['description'],
                'date'        => $data['date'],
                'place'       => $data['place'] ?? null,
                'active'      => $data['active'],
                'image'       => $data['image'] ?? null,
            ]);
            $this->dispatch('swal', title: 'Berhasil', text: 'Berita ditambahkan.', icon: 'success');
        } else {
            $old->update([
                'title'       => $data['title'],
                'description' => $data['description'],
                'date'        => $data['date'],
                'place'       => $data['place'] ?? null,
                'active'      => $data['active'],
                'image'       => $data['image'] ?? $old->image,
            ]);
            $this->dispatch('swal', title: 'Tersimpan', text: 'Berita diperbarui.', icon: 'success');
        }

        $this->resetForm();
        $this->resetPage();
        $this->dispatch('modal-hide', id: 'newsModal');
    }

    public function askDelete($id)
    {
        $this->dispatch('confirm-delete', id: $id, title: 'Hapus berita?', text: 'Tindakan ini tidak bisa dibatalkan.');
    }

    #[On('delete-confirmed')]
    public function deleteConfirmed($id)
    {
        $n = News::findOrFail($id);
        if ($n->image && Storage::disk('public')->exists($n->image)) {
            Storage::disk('public')->delete($n->image);
        }
        $n->delete();
        $this->dispatch('swal', title: 'Dihapus', text: 'Berita berhasil dihapus.', icon: 'success');
        $this->resetPage();
    }

    public function toggleActive($id)
    {
        $n = News::findOrFail($id);
        $n->active = !$n->active;
        $n->save();
        $this->dispatch('swal', title: 'Tersimpan', text: 'Status aktif diperbarui.', icon: 'success');
    }

    public function resetForm()
    {
        $this->reset([
            'newsId',
            'title',
            'description',
            'date',
            'place',
            'active',
            'image_upload',
            'hasImage',
            'mode'
        ]);
        $this->active = true;
        $this->resetValidation();
    }
}

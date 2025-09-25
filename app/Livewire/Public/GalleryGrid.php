<?php

namespace App\Livewire\Public;

use App\Models\GalleryPhoto;
use Livewire\Component;

class GalleryGrid extends Component
{
    public int $perPage = 9;   // 3 x 3
    public bool $hasMore = false;

    public function render()
    {
        $query  = GalleryPhoto::active()->latest();
        $total  = (clone $query)->count();
        $photos = $query->limit($this->perPage)->get();

        $this->hasMore = $total > $this->perPage;

        return view('livewire.public.gallery-grid', compact('photos'));
    }

    public function loadMore()
    {
        $this->perPage += 9; // tambah 3 baris setiap klik
    }
}

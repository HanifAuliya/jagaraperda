<?php

namespace App\Livewire\Public;

use App\Models\Aspirasi;
use App\Models\Raperda;
use Livewire\Component;
use Livewire\WithPagination;

class AspirasiSuksesList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $q = '';
    public int $perPage = 9;
    public string $raperdaId = ''; // filter raperda

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function updatingRaperdaId()
    {
        $this->resetPage();
    }

    public function search()
    {
        $this->q = trim($this->q);
        if (mb_strlen($this->q) < 2 && $this->q !== '') {
            // contoh: bisa kasih toast warning
            // session()->flash('warn', 'Minimal 2 huruf');
        }
        $this->resetPage();
    }

    public function updatedRaperdaId($value)
    {
        $this->raperdaId = is_array($value) ? ($value[0] ?? '') : (string) $value;
    }

    public function render()
    {
        $items = Aspirasi::query()
            ->with('raperda')
            ->where('status', 'selesai')
            ->when(trim($this->q) !== '', function ($q) {
                $term = trim($this->q);
                $q->where(function ($qq) use ($term) {
                    $qq->where('judul', 'like', "%{$term}%")
                        ->orWhere('isi', 'like', "%{$term}%")
                        ->orWhereHas('raperda', fn($r) => $r->where('judul', 'like', "%{$term}%"));
                });
            })
            ->when($this->raperdaId !== '', function ($q) {
                $q->where('raperda_id', $this->raperdaId);
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        $raperdaList = Raperda::orderBy('judul')->get();

        return view('livewire.public.aspirasi-sukses-list', compact('items', 'raperdaList'))
            ->layout('layouts.guest');
    }
}

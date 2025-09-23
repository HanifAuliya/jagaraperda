<?php

namespace App\Livewire;

use App\Models\Raperda;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PublikasiList extends Component
{
    use WithPagination;
    protected string $paginationTheme = 'bootstrap';

    // simpan filter di URL (v3 style)
    #[Url] public $q = '';
    #[Url] public $tahun = '';
    #[Url] public $status = ''; // draf|final

    // reset halaman saat filter berubah
    public function updated($field)
    {
        if (in_array($field, ['q', 'tahun', 'status'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $raperdas = Raperda::query()
            ->where('aktif', true)
            ->when(
                $this->q !== '',
                fn($qr) =>
                $qr->where('judul', 'like', "%{$this->q}%")
            )
            ->when(
                $this->tahun !== '',
                fn($qr) =>
                $qr->where('tahun', $this->tahun)
            )
            ->when(
                in_array($this->status, ['draf', 'final'], true),
                fn($qr) =>
                $qr->where('status', $this->status)
            )
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->paginate(9);

        $tahunList = Raperda::where('aktif', true)
            ->whereNotNull('tahun')
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('livewire.publikasi-list', compact('raperdas', 'tahunList'));
    }
}

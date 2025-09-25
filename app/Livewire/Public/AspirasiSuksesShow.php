<?php
// app/Livewire/Public/AspirasiSuksesShow.php

namespace App\Livewire\Public;

use App\Models\Aspirasi;
use Livewire\Component;

class AspirasiSuksesShow extends Component
{
    public Aspirasi $aspirasi;

    public function mount(Aspirasi $aspirasi)
    {
        abort_if($aspirasi->status !== 'selesai', 404);
        $this->aspirasi = $aspirasi->load(['raperda', 'files', 'tanggapan' => fn($q) => $q->latest()]);
    }

    public function render()
    {
        return view('livewire.public.aspirasi-sukses-show')
            ->layout('layouts.guest');
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Aspirasi;
use App\Models\TanggapanAspirasi;
use App\Models\Raperda; // <-- tambahkan
use Illuminate\Support\Facades\Auth;

class AspirasiQueue extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    // FILTERS
    public string $status = 'baru';
    public ?int $raperdaFilter = null;   // <-- tambahkan
    public string $sort = 'desc';         // <-- tambahkan (desc=terbaru, asc=terlama)


    // Modal state
    public ?int $activeId = null;
    public ?Aspirasi $active = null;

    // Form balasan
    public ?string $reply_body = null;
    public $reply_file = null;

    protected $listeners = [
        'verify-approved' => 'verifyApproved',
        'reject-approved' => 'rejectApproved', // <— TAMBAH INI
    ];

    public function updatedStatus(): void
    {
        $this->resetPage();
        $this->closeAll();
    }

    // kalau ganti raperda/sort, reset halaman juga
    public function updatedRaperdaFilter(): void
    {
        $this->resetPage();
    }
    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->raperdaFilter = null;
        $this->sort = 'desc';
        $this->resetPage();
        // sinkronkan dropdown tomselect di front-end
        $this->dispatch('raperda-filter:reset');
    }

    private function loadActive(int $id): void
    {
        $this->activeId = $id;
        $this->active   = Aspirasi::with(['raperda', 'files', 'tanggapan'])->findOrFail($id);
    }

    private function closeAll(): void
    {
        $this->activeId   = null;
        $this->active     = null;
        $this->reply_body = null;
        $this->reply_file = null;

        $this->dispatch('modal:hide', id: 'detailModal');
        $this->dispatch('modal:hide', id: 'respondModal');
        $this->dispatch('modal:hide', id: 'closeModal');
        $this->dispatch('modal:hide', id: 'rejectModal');
    }

    // Open modals
    public function openDetail(int $id): void
    {
        $this->loadActive($id);
        $this->dispatch('modal:show', id: 'detailModal');
    }

    public function openRespond(int $id): void
    {
        $this->loadActive($id);
        // Block jika sudah closed
        if (in_array($this->active->status, ['selesai', 'ditolak', 'kadaluwarsa'])) {
            $this->dispatch('swal', title: 'Tidak bisa', text: 'Aspirasi sudah ditutup.', icon: 'warning');
            return;
        }

        $this->reply_body = null;
        $this->reply_file = null;
        $this->dispatch('modal:show', id: 'respondModal');
    }

    public function openClose(int $id): void
    {
        $this->loadActive($id);
        $this->dispatch('modal:show', id: 'closeModal');
    }

    public function openReject(int $id): void
    {
        $this->loadActive($id);
        $this->dispatch('modal:show', id: 'rejectModal');
    }

    public function verifyApproved($id): void
    {
        // dukung kalau ada yang ngirim {id: ...} sebagai array juga
        if (is_array($id) && isset($id['id'])) $id = $id['id'];
        $this->verify((int) $id);
    }

    public function rejectApproved($id): void
    {
        if (is_array($id) && isset($id['id'])) $id = $id['id'];
        $this->reject((int) $id);
    }

    private function verify(int $id): void
    {
        $asp = Aspirasi::findOrFail($id);

        $asp->update([
            'status'                   => 'terverifikasi',
            'verified_at'              => now(),
            'admin_reply_deadline_at'  => now()->addDays(5), // ≤5 hari setelah verifikasi
            // final_deadline_at sudah diset saat submit; fallback kalau null:
            'final_deadline_at'        => $asp->final_deadline_at ?? $asp->created_at->copy()->addDays(18),
        ]);

        $this->dispatch('swal', title: 'Terverifikasi', text: 'Aspirasi dipindah ke Tindak Lanjut.');
        $this->resetPage(); // opsional refresh tabel
    }

    // Tolak
    public function rejectActive(): void
    {
        if (!$this->activeId) return;

        Aspirasi::whereKey($this->activeId)->update([
            'status'    => 'ditolak',
            'closed_at' => now(),
        ]);

        $this->closeAll();
        $this->dispatch('swal', title: 'Ditolak', icon: 'warning', text: 'Aspirasi ditandai ditolak.');
    }

    // Balas / Kesimpulan
    public function respondActive(): void
    {
        if (!$this->active) return;

        if (in_array($this->active->status, ['selesai', 'ditolak', 'kadaluwarsa'])) {
            $this->dispatch('swal', title: 'Tidak bisa', text: 'Aspirasi sudah ditutup.', icon: 'warning');
            return;
        }

        $this->validate([
            'reply_body' => ['required', 'string', 'min:5'],
            'reply_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $path = null;
        if ($this->reply_file) {
            $path = $this->reply_file->store("aspirasi/{$this->active->id}/thread", 'public');
        }

        TanggapanAspirasi::create([
            'aspirasi_id' => $this->active->id,
            'actor'       => 'admin',
            'user_id'     => Auth::id(),
            'isi'         => $this->reply_body,
            'file_path'   => $path,
        ]);

        // Transisi status & SLA
        if (in_array($this->active->status, ['terverifikasi', 'menunggu_tindak_lanjut'])) {
            // Balasan pertama admin
            $this->active->status                 = 'ditanggapi';
            $this->active->admin_replied_at       = now();
            $this->active->user_reply_deadline_at = now()->addDays(10);
        } elseif ($this->active->status === 'ditanggapi') {
            // Balasan lanjutan admin (tetap di 'ditanggapi'), jangan ubah deadline pelapor
            $this->active->admin_replied_at = $this->active->admin_replied_at ?? now();
        } elseif ($this->active->status === 'balasan_pelapor') {
            // Kesimpulan akhir → tutup
            $this->active->status    = 'selesai';
            $this->active->closed_at = now();
            $this->active->admin_replied_at = $this->active->admin_replied_at ?? now();
        }

        $this->active->save();

        $this->closeAll();
        $this->dispatch('swal', title: 'Tersimpan', text: 'Tanggapan berhasil dikirim.');
    }



    // Tutup tanpa balasan
    public function closeActive(): void
    {
        if (!$this->activeId) return;

        Aspirasi::whereKey($this->activeId)->update([
            'status'    => 'selesai',
            'closed_at' => now(),
        ]);

        $this->closeAll();
        $this->dispatch('swal', title: 'Ditutup', text: 'Aspirasi ditutup.');
    }

    // UI helpers
    public function statusBadge(string $status): array
    {
        return match ($status) {
            'baru'                   => ['secondary', 'Baru'],
            'terverifikasi'          => ['warning text-dark', 'Verifikasi'],
            'menunggu_tindak_lanjut' => ['primary', 'Tindak Lanjut'],
            'ditanggapi'             => ['info text-dark', 'Menunggu Tanggapan Pelapor'],
            'balasan_pelapor'        => ['secondary', 'Balasan Pelapor'],
            'selesai'                => ['success', 'Selesai'],
            'ditolak'                => ['danger', 'Ditolak'],
            'kadaluwarsa'            => ['dark', 'Kadaluwarsa'],
            default                  => ['secondary', $status],
        };
    }

    public function deadlineInfo(Aspirasi $a): array
    {
        // helper kecil
        $or = fn($date, $fallback) => $date ?: $fallback;

        return match ($a->status) {
            // 1) Baru → admin harus verifikasi
            'baru' => [
                'label' => 'Verifikasi sebelum',
                'at'    => $or(
                    $a->verify_deadline_at,
                    $a->created_at?->copy()->addDays(3)
                ),
            ],

            // 2) Terverifikasi / Menunggu TL → instansi harus menindaklanjuti
            'terverifikasi', 'menunggu_tindak_lanjut' => [
                'label' => 'Tindak lanjuti sebelum',
                'at'    => $or(
                    $a->admin_reply_deadline_at,
                    // fallback: kalau sudah verif → +5 hari, kalau belum → +8 hari dari created
                    ($a->verified_at
                        ? $a->verified_at->copy()->addDays(5)
                        : $a->created_at?->copy()->addDays(8)
                    )
                ),
            ],

            // 3) Ditanggapi → tunggu balasan pelapor
            'ditanggapi' => [
                'label' => 'Pelapor membalas sebelum',
                'at'    => $or(
                    $a->user_reply_deadline_at,
                    $a->admin_replied_at?->copy()->addDays(10)
                ),
            ],

            // 4) Balasan Pelapor → admin finalisasi
            'balasan_pelapor' => [
                'label' => 'Finalisasi & tutup sebelum',
                'at'    => $or(
                    $a->final_deadline_at,
                    $a->created_at?->copy()->addDays(18)
                ),
            ],

            // 5) Closed states → tidak tampilkan SLA
            default => [
                'label' => '—',
                'at'    => null,
            ],
        };
    }


    public function render()
    {
        // ambil opsi raperda untuk dropdown
        $raperdaOptions = Raperda::orderBy('tahun', 'desc')
            ->orderBy('judul')
            ->get(['id', 'judul', 'tahun', 'berkas']);

        $q = Aspirasi::query()->with(['raperda', 'files']);

        // status filter (gabungan 'tindak' = terverifikasi + menunggu_tindak_lanjut)
        if ($this->status === 'tindak') {
            $q->whereIn('status', ['terverifikasi', 'menunggu_tindak_lanjut']);
        } else {
            $q->where('status', $this->status);
        }

        // filter raperda (jika dipilih)
        if ($this->raperdaFilter) {
            $q->where('raperda_id', $this->raperdaFilter);
        }

        // sort waktu
        $direction = $this->sort === 'asc' ? 'asc' : 'desc';
        $q->orderBy('created_at', $direction);

        $items = $q->paginate(5);

        return view('livewire.admin.aspirasi-queue', compact('items', 'raperdaOptions'))
            ->layout('layouts.app');
    }
}

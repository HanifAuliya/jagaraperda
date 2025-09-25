<?php

namespace App\Livewire\Public;

use App\Models\Aspirasi;
use App\Models\TanggapanAspirasi;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\AspirasiFeedback;

class AspirasiTracking extends Component
{
    use WithFileUploads;

    // MODE: search / detail
    public string $mode = 'search';

    // Pencarian
    public string $q = '';
    /** @var \Illuminate\Support\Collection<int,\App\Models\Aspirasi> */
    public $results = [];

    // PIN flow
    public string $tracking_no = '';
    public string $tracking_pin = '';
    public ?string $candidateNo = null;

    // Detail aktif
    public ?Aspirasi $aspirasi = null;

    // Balas (pelapor hanya 1x)
    public ?string $reply_body = null;
    public $reply_file;

    /** Status yang dianggap tertutup/closed */
    private const CLOSED_STATUSES = ['selesai', 'kadaluwarsa', 'ditolak'];

    /** Regex: JGR-2025-000123 atau JRP-2025-09-000123 (prefix 3 huruf) */
    private const TRACKING_REGEX = '/^[A-Z]{3}-\d{4}(?:-\d{2})?-\d{5,6}$/';

    // ===== Survey feedback props =====
    public string $feedback_rating = 'puas'; // default
    public ?string $feedback_comment = null;
    public bool $surveySubmitted = false; // <-- tambahkan

    // ============== PENCARIAN ==============
    public function search(): void
    {
        $term = trim($this->q);

        if ($term === '') {
            $this->results = collect();
            return;
        }

        $isNo = (bool) preg_match(self::TRACKING_REGEX, strtoupper($term));

        $query = Aspirasi::query()
            ->with(['raperda'])
            ->orderByDesc('created_at');


        $query->where('tracking_no', strtoupper($term));

        $this->results = $query->limit(10)->get();

        // pastikan tetap di mode search
        $this->mode = 'search';
        $this->aspirasi = null;
        $this->candidateNo = null;
        $this->tracking_pin = '';
        $this->resetErrorBag();
    }

    public function resetSearch(): void
    {
        $this->reset(['q']);
        $this->results = collect();   // kosongkan list
        $this->resetErrorBag();
        // optional: juga reset state lain
        $this->aspirasi = null;
        $this->candidateNo = null;
        $this->tracking_pin = '';
    }


    // Tambahan properti
    public bool $showPinModal = false;

    // Ubah promptPin jadi buka modal
    public function promptPin(string $trackingNo): void
    {
        $this->candidateNo = $trackingNo;
        $this->tracking_pin = '';
        $this->resetValidation(['tracking_pin']);

        // tampilkan modal
        $this->dispatch('show-pin-modal'); // event utk JS Bootstrap
        $this->showPinModal = true;
    }

    // Tutup modal dari Livewire
    public function closePinModal(): void
    {
        $this->candidateNo = null;
        $this->tracking_pin = '';
        $this->resetValidation(['tracking_pin']);
        $this->showPinModal = false;

        $this->dispatch('hide-pin-modal');
    }


    // Validasi PIN → masuk ke halaman detail
    public function openWithPin(): void
    {
        $this->validate([
            'candidateNo'  => ['required', 'string', 'max:30'],
            'tracking_pin' => ['required', 'digits:6'],
        ]);

        // Rate limit per kombinasi nomor + IP
        $key = sprintf('pin:%s|%s', $this->candidateNo, request()->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('tracking_pin', 'Terlalu banyak percobaan. Coba lagi beberapa menit lagi.');
            return;
        }

        $asp = Aspirasi::where('tracking_no', $this->candidateNo)
            ->where('tracking_pin', $this->tracking_pin)
            ->first();

        if (!$asp) {
            RateLimiter::hit($key, 300); // 5 menit
            $this->addError('tracking_pin', 'PIN salah untuk nomor tersebut.');
            return;
        }

        RateLimiter::clear($key);

        $this->aspirasi = $asp->load(['raperda', 'tanggapan', 'feedback']);
        $this->surveySubmitted = (bool) $this->aspirasi->feedback; // <-- tambahkan
        $this->mode = 'detail';
        $this->tracking_no = $asp->tracking_no;
        $this->tracking_pin = '';
        $this->resetErrorBag();
        $this->dispatch('hide-pin-modal');
        $this->showPinModal = false;

        // 👉 Event untuk trigger ulang JS tahapan
        $this->dispatch('tahapan:refresh');
    }


    // Form lama: lacak langsung dgn nomor+PIN
    public function find(): void
    {
        $this->validate([
            'tracking_no'  => ['required', 'string', 'max:30'],
            'tracking_pin' => ['required', 'digits:6'],
        ]);

        $key = sprintf('pin:%s|%s', $this->tracking_no, request()->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('tracking_no', 'Terlalu banyak percobaan. Coba lagi beberapa menit lagi.');
            return;
        }

        $asp = Aspirasi::where('tracking_no', $this->tracking_no)
            ->where('tracking_pin', $this->tracking_pin)
            ->first();

        if (!$asp) {
            RateLimiter::hit($key, 300);
            $this->addError('tracking_no', 'Kombinasi Tracking No / PIN tidak ditemukan.');
            return;
        }

        RateLimiter::clear($key);

        $this->aspirasi = $asp->load(['raperda', 'tanggapan', 'feedback']);
        $this->surveySubmitted = (bool) $this->aspirasi->feedback; // <-- tambahkan
        $this->mode = 'detail';
        $this->candidateNo = null;
        $this->tracking_pin = '';
        $this->resetErrorBag();
    }

    // Properti komputasi: pelapor sudah balas?
    public function getReporterAlreadyRepliedProperty(): bool
    {
        if (!$this->aspirasi) return false;

        return $this->aspirasi->tanggapan()
            ->where('actor', 'pelapor')
            ->exists();
    }

    // Properti komputasi: status closed?
    public function getIsClosedProperty(): bool
    {
        return $this->aspirasi
            ? in_array($this->aspirasi->status, self::CLOSED_STATUSES, true)
            : false;
    }

    public function sendReply(): void
    {
        if (!$this->aspirasi) return;

        if ($this->isClosed) {
            $this->addError('reply_body', 'Status tidak mengizinkan balasan.');
            return;
        }

        if ($this->reporterAlreadyReplied) {
            $this->addError('reply_body', 'Anda sudah mengirim satu balasan. Menunggu tindak lanjut admin.');
            return;
        }

        $this->validate([
            'reply_body' => ['required', 'string', 'min:5'],
            'reply_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $path = null;
        if ($this->reply_file) {
            // Simpan dengan nama unik yang aman
            $path = $this->reply_file->store(
                'aspirasi/' . $this->aspirasi->id . '/thread',
                'public'
            );
        }

        TanggapanAspirasi::create([
            'aspirasi_id' => $this->aspirasi->id,
            'actor'       => 'pelapor',
            'user_id'     => null,
            'isi'         => $this->reply_body,
            'file_path'   => $path,
        ]);

        $this->aspirasi->update([
            'user_replied_at' => now(),
            'status'          => 'balasan_pelapor', // ⬅️ sebelumnya 'menunggu_tindak_lanjut'
        ]);

        $this->reset(['reply_body', 'reply_file']);
        session()->flash('ok', 'Tanggapan terkirim. Anda tidak bisa membalas lagi.');
    }

    // Helper: label status untuk badge
    public function statusBadge(string $status): array
    {
        return match ($status) {
            'baru'                    => ['bg-secondary', 'Baru'],
            'terverifikasi'           => ['bg-warning text-dark', 'Verifikasi'],
            'menunggu_tindak_lanjut'  => ['bg-primary', 'Tindak Lanjut'],
            'ditanggapi'              => ['bg-info text-dark', 'Menunggu Tanggapan Anda'],
            'balasan_pelapor'         => ['bg-purple text-light', 'Menunggu Keputusan Admin'],
            'selesai'                 => ['bg-success', 'Selesai'],
            'ditolak', 'kadaluwarsa'  => ['bg-dark', ucfirst($status)],
            default                   => ['bg-secondary', '-'],
        };
    }

    // Helper: sudah memberi feedback?
    public function getHasFeedbackProperty(): bool
    {
        return (bool) $this->aspirasi?->feedback;
    }


    // Submit survey
    public function submitFeedback(): void
    {
        if (!$this->aspirasi) {
            $this->addError('feedback_rating', 'Laporan tidak ditemukan.');
            return;
        }

        if ($this->aspirasi->status !== 'selesai') {
            $this->addError('feedback_rating', 'Survey hanya untuk laporan yang sudah selesai.');
            return;
        }

        if ($this->hasFeedback) {
            $this->addError('feedback_rating', 'Anda sudah mengirimkan umpan balik untuk laporan ini.');
            return;
        }


        $this->validate([
            'feedback_rating'  => ['required', Rule::in(['puas', 'cukup', 'tidak'])],
            'feedback_comment' => ['nullable', 'string', 'max:500'],
        ]);

        // Simpan dan set relasi di memori (hemat 1 query)
        $feedback = $this->aspirasi->feedback()->create([
            'rating'          => $this->feedback_rating,
            'comment'         => $this->feedback_comment,
            'submitted_by_ip' => request()->ip(),
            'user_agent'      => substr(request()->userAgent() ?? '', 0, 255),
        ]);

        // Kunci: tandai sudah submit + set relasi agar hasFeedback jadi true
        $this->surveySubmitted = true;                  // <-- instant hide
        $this->aspirasi->setRelation('feedback', $feedback);

        // (opsional kalau masih ngeyel) paksa re-render
        // $this->dispatch('$refresh');

        // Swal (pakai named args agar payload ke JS benar)
        $this->dispatch('swal', title: 'Terima kasih!', text: 'Umpan balik Anda tersimpan.', icon: 'success');

        $this->reset(['feedback_comment']);
        $this->feedback_rating = 'puas';
    }

    public int $successPreviewLimit = 4; // jumlah kartu preview di halaman tracking

    public function mount(): void
    {
        $no = request()->query('no');
        if ($no) {
            // prefill no dan tampilkan PIN modal
            $this->candidateNo = strtoupper($no);
            $this->tracking_pin = '';
            $this->resetValidation(['tracking_pin']);
            $this->dispatch('show-pin-modal');
            $this->showPinModal = true;
        }
    }


    public function render()
    {
        $successItems = \App\Models\Aspirasi::query()
            ->with('raperda')
            ->where('status', 'selesai')
            ->orderByDesc('created_at')
            ->limit($this->successPreviewLimit)
            ->get();

        return view('livewire.public.aspirasi-tracking', [
            'successItems' => $successItems,
        ])->layout('layouts.guest');
    }
}

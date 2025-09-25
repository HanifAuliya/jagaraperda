<?php

namespace App\Livewire\Public;

use App\Models\Aspirasi;
use App\Models\AspirasiFile;
use App\Models\Raperda;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class AspirasiForm extends Component
{
    use WithFileUploads;

    public $raperdas = [];
    public $raperda_id;
    public $nama;
    public $alamat;
    public $email;
    public $judul;
    public $isi;
    public $mode_privasi = 'normal';
    public $files = [];

    public $captcha; // <-- tempat nyimpen token dari JS

    // tambahan
    public $consent = false;
    public $website;   // honeypot
    public $loaded_at; // min submit time

    protected function rules(): array
    {
        $base = [
            // raperda sekarang WAJIB
            'raperda_id'   => ['required', 'exists:raperdas,id'],
            'judul'        => ['required', 'string', 'max:150'],
            'isi'          => ['required', 'string', 'min:20'],

            // opsional
            'files.*'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'email'        => ['nullable', 'email', 'max:150'],
            'alamat'       => ['nullable', 'string', 'max:255'],

            'mode_privasi' => ['required', 'in:normal,anonim,rahasia'],
            'consent'      => ['accepted'],
        ];

        // Nama wajib untuk NORMAL dan RAHASIA, tidak wajib untuk ANONIM
        if ($this->mode_privasi === 'normal' || $this->mode_privasi === 'rahasia') {
            $base['nama'] = ['required', 'string', 'max:120'];
        } else { // anonim
            $base['nama'] = ['nullable']; // akan kita kosongkan saat submit
            // email & alamat juga boleh kosong, sudah nullable di atas
        }
        return $base;
    }

    public function mount(): void
    {
        $this->raperdas = Raperda::query()
            ->where('aktif', true)
            ->orderByDesc('tahun')
            ->get(['id', 'judul', 'tahun']);
        $this->loaded_at = now()->timestamp;
    }

    public function updatedModePrivasi(): void
    {
        if ($this->mode_privasi === 'anonim') {
            $this->nama = $this->alamat = $this->email = null;
        }
    }

    public function submit()
    {

        // anti-spam: honeypot + min 3s
        if (!empty($this->website) || (now()->timestamp - (int)$this->loaded_at) < 3) {
            $this->dispatch('swal-validation', messages: ['Deteksi spam. Coba lagi.']);
            return;
        }

        // throttle IP + title
        $key = 'aspirasi:' . request()->ip() . ':' . md5((string)$this->judul);
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 3)) {
            $this->dispatch('swal-validation', messages: ['Terlalu sering. Coba lagi nanti.']);
            return;
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        // validasi + SweetAlert jika gagal
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('swal-validation', messages: $e->validator->errors()->all());
            throw $e;
        }

        // 2) BARU validasi CAPTCHA menggunakan token yg sudah masuk ke $this->captcha
        try {
            Validator::make(
                ['g-recaptcha-response' => $this->captcha],
                ['g-recaptcha-response' => ['required', 'captcha']],
                ['g-recaptcha-response.required' => 'Mohon centang verifikasi “I’m not a robot”.']
            )->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // tampilkan error singkat di Swal
            $this->dispatch('swal-validation', messages: $e->validator->errors()->all());
            // opsional: juga kirim ke error bag supaya muncul di bawah widget
            $this->addError('captcha', 'Mohon centang verifikasi “I’m not a robot”.');
            return;
        }

        // sanitasi privasi
        // di submit()
        $nama   = $this->mode_privasi === 'anonim' ? null : $this->nama;
        $alamat = $this->mode_privasi === 'anonim' ? null : $this->alamat;
        $email  = $this->mode_privasi === 'anonim' ? null : $this->email;


        // >>> anchor waktu satu kali
        $now = now();

        $asp = new \App\Models\Aspirasi();
        $asp->fill([
            'raperda_id'   => $this->raperda_id,
            'nama'         => $nama,
            'alamat'       => $alamat,
            'email'        => $email,
            'judul'        => $this->judul,
            'isi'          => $this->isi,
            'mode_privasi' => $this->mode_privasi,
            'tracking_no'  => \App\Models\Aspirasi::generateTrackingNo(),
            'tracking_pin' => \App\Models\Aspirasi::generatePin(),
            'status'       => 'baru',

            // >>> SLA AWAL (placeholder):
            'verify_deadline_at'       => $now->copy()->addDays(3),   // ≤ 3 hari verifikasi
            'admin_reply_deadline_at'  => $now->copy()->addDays(8),   // 3 + 5 hari
            'final_deadline_at'        => $now->copy()->addDays(18),  // payung total ~18 hari
            // 'user_reply_deadline_at' => null, // masih null sampai admin balasan pertama

            'submit_ip'    => request()->ip(),
        ]);
        $asp->save();

        // simpan lampiran
        foreach ($this->files ?? [] as $f) {
            if (!$f->isValid()) continue;
            $path = $f->store('aspirasi/' . $asp->id, 'public');

            \App\Models\AspirasiFile::create([
                'aspirasi_id'   => $asp->id,
                'path'          => $path,
                'original_name' => $f->getClientOriginalName(),
                'size'          => $f->getSize(),
            ]);
        }

        $this->dispatch(
            'swal-success',
            tracking_no: $asp->tracking_no,
            pin: $asp->tracking_pin,
            go_url: route('aspirasi.tracking') // ← tambahkan ini
        );
        // reset form
        $this->reset(['raperda_id', 'nama', 'alamat', 'email', 'judul', 'isi', 'mode_privasi', 'files', 'consent', 'captcha']);
        $this->mode_privasi = 'normal';
        $this->loaded_at = now()->timestamp;
    }


    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files); // reindex array
        }
    }


    public function render()
    {
        return view('livewire.public.aspirasi-form')
            ->layout('layouts.guest'); // atau 'components.layouts.app' jika layout kamu berbasis komponen
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Aspirasi extends Model
{
    use HasFactory;


    protected $fillable = [
        'raperda_id',
        'nama',
        'alamat',
        'email',
        'judul',
        'isi',
        'mode_privasi',
        'tracking_no',
        'tracking_pin',
        'status',
        'verified_at',
        'admin_replied_at',
        'user_replied_at',
        'closed_at',
        'verify_deadline_at',
        'admin_reply_deadline_at',
        'final_deadline_at',
        'submit_ip',
    ];

    protected $casts = [
        'verified_at'              => 'datetime',
        'admin_replied_at'         => 'datetime',
        'user_replied_at'          => 'datetime',
        'closed_at'                => 'datetime',
        'verify_deadline_at'       => 'datetime',
        'admin_reply_deadline_at'  => 'datetime',
        'user_reply_deadline_at'   => 'datetime',
        'final_deadline_at'        => 'datetime',
    ];

    protected $appends = ['step']; // otomatis include 'step' saat model diubah ke array/json

    public function getStepAttribute(): int
    {
        // Mapping dasar
        $step = match ($this->status) {
            'baru', 'terverifikasi'        => 2,
            'menunggu_tindak_lanjut'       => 3,
            'ditanggapi', 'balasan_pelapor' => 4,
            'selesai', 'ditolak', 'kadaluwarsa' => 5,
            default => 2,
        };

        // Extra check → naikkan step jika ada data lain di DB
        if ($this->tanggapan()->where('actor', 'admin')->exists()) {
            $step = max($step, 4);
        }

        if (!is_null($this->user_replied_at)) {
            $step = max($step, 4);
        }

        return $step;
    }



    public function raperda()
    {
        return $this->belongsTo(Raperda::class);
    }
    public function files()
    {
        return $this->hasMany(AspirasiFile::class);
    }
    public function tanggapan()
    {
        return $this->hasMany(TanggapanAspirasi::class)->latest();
    }

    // app/Models/Aspirasi.php
    public function feedback()
    {
        return $this->hasOne(\App\Models\AspirasiFeedback::class);
    }


    public static function generateTrackingNo(): string
    {
        // Format: JRP-YYYY-MM-XXXXXX (increment acak)
        $prefix = 'ASP-' . now()->format('Y-m');
        do {
            $serial = str_pad((string)random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $no = $prefix . '-' . $serial;
        } while (self::where('tracking_no', $no)->exists());
        return $no;
    }


    public static function generatePin(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Aspirasi;
use App\Models\AspirasiFeedback;
use App\Models\Raperda;
use App\Models\TanggapanAspirasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AspirasiSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan Raperda 2025 sudah ada
        $titles = [
            'Rencana Pembangunan Jangka Menengah Daerah Provinsi Kalimantan Selatan Tahun 2025-2029',
            'Pedoman Pembiayaan Tahun Jamak',
            'Penyelenggaraan Penanaman Modal',
            'Pengelolaan Usaha Pertambangan Mineral dan Batubara',
            'Pembentukan Produk Hukum Daerah',
            'Penyelenggaraan Perdagangan di Kalimantan Selatan',
            'Penyelenggaraan Pangan di Kalimantan Selatan',
            'Penyelenggaraan Kesehatan',
        ];

        $rmap = [];
        foreach ($titles as $t) {
            $r = Raperda::where('tahun', 2025)->where('judul', $t)->first();
            if ($r) $rmap[$t] = $r->id;
        }
        if (empty($rmap)) {
            $this->command?->warn('Raperda 2025 belum ada. Jalankan RaperdaSeeder dulu.');
            return;
        }

        // 10 aspirasi (judul ringkas, mode, target raperda)
        $items = [
            ['t' => $titles[0], 'mode' => 'normal',  'nama' => 'Andi Saputra', 'email' => 'andi@example.com', 'alamat' => 'Banjarmasin', 'judul' => 'Perbaikan indikator RPJMD', 'isi' => 'Mohon penyesuaian indikator outcome agar realistis, terutama target akses air bersih pedesaan.'],
            ['t' => $titles[1], 'mode' => 'normal',  'nama' => 'Siti Rahma', 'email' => 'siti@example.com', 'alamat' => 'Banjarbaru',   'judul' => 'Risiko skema multi years', 'isi' => 'Perlu mitigasi risiko keterlambatan proyek dan lonjakan harga material di pedoman pembiayaan tahun jamak.'],
            ['t' => $titles[2], 'mode' => 'rahasia', 'judul' => 'Transparansi SLA perizinan', 'isi' => 'Mohon portal perizinan memuat SLA tiap layanan dan statistik waktu proses bulanan.'],
            ['t' => $titles[3], 'mode' => 'normal',  'nama' => 'Rahmat Hidayat', 'email' => 'rahmat@example.com', 'alamat' => 'Tanah Bumbu', 'judul' => 'Reklamasi pascatambang', 'isi' => 'Perlu penguatan jaminan reklamasi dan sanksi tegas bila komitmen pascatambang tidak dipenuhi.'],
            ['t' => $titles[4], 'mode' => 'anonim',  'judul' => 'Partisipasi publik', 'isi' => 'Cantumkan kewajiban uji publik dan publikasi naskah akademik minimal 7 hari kerja sebelum rapat.'],
            ['t' => $titles[5], 'mode' => 'normal',  'nama' => 'Nur Aini', 'email' => 'aini@example.com', 'alamat' => 'HSS', 'judul' => 'Etalase UMKM di ritel modern', 'isi' => 'Usul etalase khusus produk UMKM lokal dan percepatan sertifikasi halal.'],
            ['t' => $titles[6], 'mode' => 'rahasia', 'judul' => 'Cadangan pangan daerah', 'isi' => 'Atur cadangan pangan dan mekanisme operasi pasar saat gejolak harga.'],
            ['t' => $titles[7], 'mode' => 'normal',  'nama' => 'Aulia Zahra', 'email' => 'aulia@example.com', 'alamat' => 'Banjar', 'judul' => 'SPM layanan kesehatan', 'isi' => 'Tegaskan SPM, pelaporan waktu tunggu IGD dan ketersediaan obat esensial.'],
            ['t' => $titles[2], 'mode' => 'anonim',  'judul' => 'Insentif investasi hijau', 'isi' => 'Berikan preferensi perizinan & retribusi untuk energi terbarukan dan pengolahan limbah.'],
            ['t' => $titles[5], 'mode' => 'normal',  'nama' => 'Dewi Lestari', 'email' => 'dewi@example.com', 'alamat' => 'Tapin', 'judul' => 'Marketplace daerah & kurir lokal', 'isi' => 'Perlu payung hukum kolaborasi marketplace daerah dengan kurir lokal serta perlindungan konsumen.'],
        ];

        // Pola thread bervariasi (akan diputar)
        // A: admin → pelapor → admin(final)
        // B: admin → admin(final)
        // C: admin → pelapor (tutup tanpa final formal)
        // D: admin → admin (klarifikasi) → pelapor (jawab) → admin(final)
        // E: admin (panjang) → tutup (admin final ringkas)
        $patterns = ['A', 'B', 'C', 'D', 'E', 'A', 'C', 'D', 'B', 'A'];

        // Distribusi feedback: 8 'puas', 2 'cukup' => 80% puas
        $ratings = array_merge(array_fill(0, 8, 'puas'), array_fill(0, 2, 'cukup'));
        shuffle($ratings);

        // Variasi tanggal di September 2025
        $base = Carbon::create(2025, 9, 1, 8, 20, 0);
        $dayOffsets = [2, 4, 5, 8, 10, 13, 16, 19, 22, 24]; // tersebar di bulan
        $seq = 1;

        foreach ($items as $i => $it) {
            if (!isset($rmap[$it['t']])) continue;

            $submittedAt = (clone $base)
                ->addDays($dayOffsets[$i])
                ->addHours(($i % 6) + 1)
                ->addMinutes(($i * 11) % 55);

            // Stempel dasar proses
            $verifiedAt        = (clone $submittedAt)->addHours(6 + ($i % 3)); // 6..8 jam
            $adminAt           = (clone $submittedAt)->addDay()->addHours(1 + ($i % 2));
            $userAt            = (clone $submittedAt)->addDays(2)->addHours(1 + ($i % 3));
            $closedAt          = (clone $submittedAt)->addDays(3)->addHours(2 + ($i % 2));

            $verifyDeadline    = (clone $submittedAt)->addDays(2);
            $adminDeadline     = (clone $submittedAt)->addDays(3);
            $userReplyDeadline = (clone $submittedAt)->addDays(4);
            $finalDeadline     = (clone $submittedAt)->addDays(7);

            // Tracking unik
            $month = str_pad((string)$submittedAt->month, 2, '0', STR_PAD_LEFT);
            $num   = str_pad((string)$seq, 6, '0', STR_PAD_LEFT);
            $trackingNo  = "JRP-{$submittedAt->year}-{$month}-{$num}";
            $trackingPin = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Buat/Update Aspirasi
            $asp = Aspirasi::updateOrCreate(
                ['tracking_no' => $trackingNo],
                [
                    'raperda_id' => $rmap[$it['t']],
                    'nama'       => ($it['mode'] === 'normal') ? ($it['nama'] ?? null) : null,
                    'alamat'     => ($it['mode'] === 'normal') ? ($it['alamat'] ?? null) : null,
                    'email'      => ($it['mode'] === 'normal') ? ($it['email'] ?? null) : null,
                    'judul'      => $it['judul'],
                    'isi'        => $it['isi'],
                    'mode_privasi' => $it['mode'],
                    'tracking_pin' => $trackingPin,
                    'status'       => 'selesai',
                    'verified_at'       => $verifiedAt,
                    'admin_replied_at'  => $adminAt,
                    'user_replied_at'   => null,     // akan diisi kalau ada balasan pelapor
                    'closed_at'         => $closedAt,
                    'verify_deadline_at'       => $verifyDeadline,
                    'admin_reply_deadline_at'  => $adminDeadline,
                    'user_reply_deadline_at'   => $userReplyDeadline,
                    'final_deadline_at'        => $finalDeadline,
                    'submit_ip' => $this->fakeIp(),
                    'created_at' => $submittedAt,
                    'updated_at' => $closedAt,
                ]
            );

            // Bersihkan thread lama agar idempotent
            TanggapanAspirasi::where('aspirasi_id', $asp->id)->delete();

            // Bangun thread bervariasi
            $p = $patterns[$i % count($patterns)];
            switch ($p) {
                case 'A': // admin → pelapor → admin (final)
                    $this->msgAdmin($asp->id, $adminAt, 'Terima kasih. Masukan dicatat untuk pembahasan tim penyusun.');
                    $this->msgPelapor($asp->id, $userAt, 'Siap, saya bisa kirim data tambahan jika diperlukan.');
                    $this->msgAdmin($asp->id, $closedAt, 'Kesimpulan: Masukan terakomodasi di draf revisi. Lanjut tahap berikut.');
                    $asp->update(['user_replied_at' => $userAt]);
                    break;

                case 'B': // admin → admin (final)
                    $this->msgAdmin($asp->id, $adminAt, 'Terima kasih. Akan kami bawa ke desk study internal.');
                    $this->msgAdmin($asp->id, $closedAt, 'Ditutup: usulan diadopsi sebagian, lampiran non-substantif.');
                    break;

                case 'C': // admin → pelapor
                    $this->msgAdmin($asp->id, $adminAt, 'Terima kasih. Perlu konfirmasi data pendukung, kami hubungi via email.');
                    $this->msgPelapor($asp->id, $userAt, 'Baik, data sudah saya kirim melalui email.');
                    $asp->update(['user_replied_at' => $userAt]);
                    break;

                case 'D': // admin → admin (klarifikasi) → pelapor → admin (final)
                    $this->msgAdmin($asp->id, $adminAt, 'Masukan diterima. Kami klarifikasi pasal terkait.');
                    $this->msgAdmin($asp->id, (clone $adminAt)->addHours(6), 'Klarifikasi: pasal 12 ayat (3) butuh penyesuaian redaksional.');
                    $this->msgPelapor($asp->id, $userAt, 'Catatan diterima, mohon tetap mempertimbangkan dampak ke pelaksana.');
                    $this->msgAdmin($asp->id, $closedAt, 'Final: redaksi diperbaiki. Masukan terserap.');
                    $asp->update(['user_replied_at' => $userAt]);
                    break;

                case 'E': // admin (panjang) → admin (final ringkas)
                default:
                    $this->msgAdmin(
                        $asp->id,
                        $adminAt,
                        'Terima kasih. Ringkasan: kami kompilasi isu, bandingkan regulasi pusat, dan sinkronkan dengan RPJMD.'
                    );
                    $this->msgAdmin($asp->id, $closedAt, 'Final: dicatat untuk pembahasan tahap selanjutnya.');
                    break;
            }

            // Feedback (80% puas)
            $rating  = $ratings[$i];
            $comment = $this->feedbackComment($rating);
            AspirasiFeedback::updateOrCreate(
                ['aspirasi_id' => $asp->id],
                [
                    'rating'           => $rating,
                    'comment'          => $comment,
                    'submitted_by_ip'  => $this->fakeIp(),
                    'user_agent'       => $this->randomUA(),
                    'created_at'       => (clone $closedAt)->addMinutes(15),
                    'updated_at'       => (clone $closedAt)->addMinutes(15),
                ]
            );

            $seq++;
        }
    }

    private function msgAdmin(int $aspirasiId, Carbon $at, string $isi): void
    {
        TanggapanAspirasi::create([
            'aspirasi_id' => $aspirasiId,
            'actor'       => 'admin',
            'user_id'     => null,
            'isi'         => $isi,
            'file_path'   => null,
            'created_at'  => $at,
            'updated_at'  => $at,
        ]);
    }

    private function msgPelapor(int $aspirasiId, Carbon $at, string $isi): void
    {
        TanggapanAspirasi::create([
            'aspirasi_id' => $aspirasiId,
            'actor'       => 'pelapor',
            'user_id'     => null,
            'isi'         => $isi,
            'file_path'   => null,
            'created_at'  => $at,
            'updated_at'  => $at,
        ]);
    }

    private function feedbackComment(string $rating): ?string
    {
        $map = [
            'puas'  => ['Terima kasih, prosesnya jelas.', 'Cepat dan komunikatif.', 'Masukan saya diakomodasi.'],
            'cukup' => ['Cukup baik, masih bisa ditingkatkan.', 'Beberapa poin belum ditindaklanjuti.'],
            'tidak' => ['Kurang responsif.', 'Perlu kejelasan tindak lanjut.'],
        ];

        if (!isset($map[$rating]) || empty($map[$rating])) {
            return null;
        }

        $opts = $map[$rating];
        return $opts[array_rand($opts)]; // selalu string
    }


    private function fakeIp(): string
    {
        return sprintf('%d.%d.%d.%d', random_int(36, 125), random_int(0, 255), random_int(0, 255), random_int(1, 254));
    }

    private function randomUA(): string
    {
        $uas = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Linux; Android 14; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Mobile Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
        ];
        return $uas[array_rand($uas)];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Raperda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class RaperdaSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Data mentah
        $rows = [

            // ===== 2025 =====
            ['tahun' => 2025, 'judul' => 'Rencana Pembangunan Jangka Menengah Daerah Provinsi Kalimantan Selatan Tahun 2025-2029', 'pemrakarsa' => 'Badan Perencanaan dan Pembangunan Daerah'],
            ['tahun' => 2025, 'judul' => 'Pedoman Pembiayaan Tahun Jamak', 'pemrakarsa' => 'Badan Perencanaan dan Pembangunan Daerah'],
            ['tahun' => 2025, 'judul' => 'Penyelenggaraan Penanaman Modal', 'pemrakarsa' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu'],
            ['tahun' => 2025, 'judul' => 'Pengelolaan Usaha Pertambangan Mineral dan Batubara', 'pemrakarsa' => 'Dinas Energi dan Sumber Daya Mineral'],
            ['tahun' => 2025, 'judul' => 'Pembentukan Produk Hukum Daerah', 'pemrakarsa' => 'Komisi I'],
            ['tahun' => 2025, 'judul' => 'Penyelenggaraan Perdagangan di Kalimantan Selatan', 'pemrakarsa' => 'Komisi II'],
            ['tahun' => 2025, 'judul' => 'Penyelenggaraan Pangan di Kalimantan Selatan', 'pemrakarsa' => 'Komisi II'],
            ['tahun' => 2025, 'judul' => 'Penyelenggaraan Kesehatan', 'pemrakarsa' => 'Komisi IV'],


            // ===== 2024 =====
            ['tahun' => 2024, 'judul' => 'Rencana Pembangunan Jangka Panjang Daerah Provinsi Kalimantan Selatan Tahun 2025-2045', 'pemrakarsa' => 'Badan Perencanaan Pembangunan Daerah'],
            ['tahun' => 2024, 'judul' => 'Grand Desain Pembangunan Kependudukan', 'pemrakarsa' => 'Dinas Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga Berencana'],
            ['tahun' => 2024, 'judul' => 'Pemberdayaan Organisasi Kemasyarakatan', 'pemrakarsa' => 'Komisi I'],
            ['tahun' => 2024, 'judul' => 'Perubahan Bentuk Perseroan Terbatas Penjaminan Kredit Daerah Kalimantan Selatan Menjadi Perseroan Terbatas Penjaminan Kredit Daerah Kalimantan Selatan Perseroda', 'pemrakarsa' => 'Biro Perekonomian'],
            ['tahun' => 2024, 'judul' => 'Penambahan Penyertaan Modal Pemerintah Provinsi Kalimantan Selatan kepada Perseroan Terbatas Penjaminan Kredit Daerah Kalimantan Selatan Perseroda', 'pemrakarsa' => 'Biro Perekonomian'],
            ['tahun' => 2024, 'judul' => 'Hasil Pengelolaan Kekayaan Daerah Yang Dipisahkan Dan Lain-Lain Pendapatan Asli Daerah Yang Sah', 'pemrakarsa' => 'Bapenda'],

            // ===== 2023 =====
            ['tahun' => 2023, 'judul' => 'Pajak dan Retribusi Daerah', 'pemrakarsa' => 'Badan Keuangan Daerah'],
            ['tahun' => 2023, 'judul' => 'Rencana Tata Ruang Wilayah Provinsi Kalimantan Selatan Tahun 2023-2043', 'pemrakarsa' => 'Dinas Pekerjaan Umum dan Penataan Ruang'],
            ['tahun' => 2023, 'judul' => 'Fasilitasi Pencegahan dan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika', 'pemrakarsa' => 'Inisiatif Komisi I'],
            ['tahun' => 2023, 'judul' => 'Inovasi Daerah', 'pemrakarsa' => 'Inisiatif Komisi III'],
            ['tahun' => 2023, 'judul' => 'Keperpustakaan dan Pembudayaan Literasi', 'pemrakarsa' => 'Inisiatif Komisi IV'],


        ];

        // Siapkan untuk upsert (slug unik per judul+tahun)
        $data = [];
        foreach ($rows as $r) {
            $slug = Str::slug($r['judul']) . '-' . $r['tahun']; // pastikan unik lintas tahun
            $data[] = [
                'slug'        => $slug,
                'judul'       => $r['judul'],
                'pemrakarsa'  => $r['pemrakarsa'],
                'tahun'       => $r['tahun'],
                'status'      => 'draf',   // default; ubah manual kalau perlu
                'ringkasan'   => null,
                'berkas'      => null,
                'aktif'       => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        // Upsert berdasarkan slug (unik)
        Raperda::upsert(
            $data,
            ['slug'], // key unik
            ['judul', 'pemrakarsa', 'tahun', 'status', 'ringkasan', 'berkas', 'aktif', 'updated_at']
        );
    }
}

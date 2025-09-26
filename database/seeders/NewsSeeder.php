<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Data berita
        $rows = [
            [
                'title' => '31 Raperda Masuk Dalam Prolegda DPRD Provinsi Kalsel 2024-2029',
                'date'  => '2024-11-25',
                'place' => 'Banjarmasin',
                'description' => <<<TXT
Badan Pembentukan Peraturan Daerah (Bapemperda) DPRD Provinsi Kalimantan Selatan (Kalsel) sepakat memasukkan 31 Rancangan Peraturan Daerah (Raperda) pada Program Legislasi Daerah (Prolegda) tahun 2024–2029. Hal ini disampaikan Ketua Bapemperda, H. Gusti Iskandar Sukma Alamsyah, usai rapat finalisasi pada Senin, 18/11/2024.

“Alhamdulillah sesuai agenda kita bahwa hari ini kita finalisasi tentang prolegda untuk tahun masa sidang 2024–2029. Sudah disepakati, dan kita menginginkan lahirnya raperda yang berkualitas,” ujar Iskandar.

Sebanyak 14 Raperda berasal dari inisiatif dewan, sisanya usulan pemerintah provinsi. Prioritas pembahasan masa sidang 2024–2025 diperkirakan 19 raperda (termasuk kumulatif keuangan daerah).

Pembahasan akan dioptimalkan melalui pembentukan Pansus komisi maupun Pansus gabungan sesuai urgensi. Bapemperda juga menyiapkan fleksibilitas terhadap kemungkinan perubahan SOTK nasional yang berimplikasi ke daerah.
TXT,
            ],
            [
                'title' => 'Bapemperda DPRD Prov. Kalsel Laksanakan Rapat Harmonisasi Konsepsi Naskah Akademik dan Raperda',
                'date'  => '2025-08-22',
                'place' => 'Banjarmasin',
                'description' => <<<TXT
Bapemperda DPRD Provinsi Kalimantan Selatan bersama komisi inisiator dan OPD pemrakarsa menggelar rapat harmonisasi konsepsi naskah akademik dan naskah rancangan Raperda, Jumat 22/08/2025.

Beberapa Raperda yang dibahas antara lain: Penyelenggaraan Perdagangan (Komisi II), Penyelenggaraan Kesehatan (Komisi IV), Pengelolaan Barang Milik Daerah (BPKAD), dan Penambahan Penyertaan Modal ke PT Bank Pembangunan Daerah Kalsel (BPKAD).

Ketua Bapemperda, H. Gusti Iskandar Sukma Alamsyah, menegaskan pentingnya forum harmonisasi untuk mematangkan penyusunan naskah akademik sebelum pembahasan tingkat lanjut. Rapat dihadiri tenaga ahli ULM dan Biro Hukum untuk memperkuat kualitas materi dan aspek legal.
TXT,
            ],
            [
                'title' => 'Pansus I DPRD Kalsel Laksanakan Finalisasi Raperda Pedoman Pembentukan Produk Hukum Daerah',
                'date'  => '2025-07-01',
                'place' => 'Banjarmasin',
                'description' => <<<TXT
Pansus I DPRD Provinsi Kalimantan Selatan merampungkan pembahasan Raperda tentang Pedoman Pembentukan Produk Hukum Daerah melalui rapat finalisasi pada Selasa, 01/07/2025, bersama Biro Hukum Setda Provinsi.

Ketua Pansus I, M. Syaripuddin, S.E., M.A.P., menyampaikan rancangan siap dibawa ke rapat paripurna untuk ditetapkan menjadi Perda. Regulasi ini diharapkan memperkuat peran Biro Hukum dan Bapemperda serta menjadi pedoman teknis-substantif penyusunan perda agar lebih tertib, partisipatif, dan sesuai ketentuan perundang-undangan.
TXT,
            ],
            [
                'title' => 'Raperda Penyelenggaraan Penyiaran Masuk Tahap Uji Publik, Fahruri: Semoga Segera Disahkan',
                'date'  => '2024-03-13',
                'place' => 'Banjarmasin',
                'description' => <<<TXT
Pansus I DPRD Provinsi Kalsel yang diketuai Fahruri, S.T., melaksanakan seminar uji publik Raperda Penyelenggaraan Penyiaran pada Rabu, 13/03/2024. Kegiatan dihadiri Biro Hukum, Diskominfo provinsi/kabupaten/kota, KPID Kalsel, dan sejumlah lembaga lainnya.

Fahruri berharap Raperda segera disahkan agar ada petunjuk tetap bagi KPID. Ketua KPID Kalsel, Dr. Ir. H. M. Farid Saoufian, MS, menekankan perlunya muatan lokal minimal 10% dan penguatan ekosistem industri penyiaran di Kalsel.
TXT,
            ],
            [
                'title' => 'DPRD Kalsel Tetapkan Raperda RPJMD 2025–2029 Menjadi Perda',
                'date'  => '2025-05-26',
                'place' => 'Banjarmasin',
                'description' => <<<TXT
DPRD Provinsi Kalimantan Selatan mengesahkan Raperda tentang RPJMD 2025–2029 menjadi Perda dalam rapat paripurna pada Senin, 26/05/2025. Paripurna dipimpin Ketua DPRD, Dr. H. Supian HK, S.H., M.H., dan dihadiri Gubernur Kalsel H. Muhidin beserta jajaran.

Pansus III pembahas RPJMD, H. Gusti Iskandar Sukma Alamsyah, menyampaikan pembahasan dilakukan menyeluruh bersama Bappeda, perangkat daerah, dan tim penyusun, termasuk FGD dengan Kemendagri. Dokumen RPJMD memuat visi, misi, isu strategis, dan program prioritas pembangunan hingga 2029 dan akan dievaluasi Kemendagri sebelum diundangkan.
TXT,
            ],
        ];

        // Susun untuk upsert berdasarkan slug unik
        $data = [];
        foreach ($rows as $r) {
            $slug = Str::slug($r['title']);
            $data[] = [
                'title'       => $r['title'],
                'slug'        => $slug,
                'date'        => $r['date'],        // YYYY-MM-DD
                'place'       => $r['place'] ?? null,
                'description' => trim($r['description']),
                'image'       => null,
                'active'      => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        // Upsert: jika slug sudah ada → update konten
        DB::table('news')->upsert(
            $data,
            ['slug'],
            ['title', 'date', 'place', 'description', 'image', 'active', 'updated_at']
        );
    }
}

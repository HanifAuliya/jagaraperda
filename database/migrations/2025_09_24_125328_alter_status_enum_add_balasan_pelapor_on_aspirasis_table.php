<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE aspirasis
            MODIFY COLUMN status ENUM(
                'baru',
                'terverifikasi',
                'menunggu_tindak_lanjut',
                'ditanggapi',
                'balasan_pelapor',
                'selesai',
                'kadaluwarsa',
                'ditolak'
            ) DEFAULT 'baru'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tanpa 'balasan_pelapor'
        DB::statement("
            ALTER TABLE aspirasis
            MODIFY COLUMN status ENUM(
                'baru',
                'terverifikasi',
                'menunggu_tindak_lanjut',
                'ditanggapi',
                'selesai',
                'kadaluwarsa',
                'ditolak'
            ) DEFAULT 'baru'
        ");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            if (!Schema::hasColumn('aspirasis', 'user_reply_deadline_at')) {
                $table->timestamp('user_reply_deadline_at')->nullable()->after('admin_reply_deadline_at');
            }
        });

        // MySQL enum update (sesuaikan jika DB-mu MySQL)
        DB::statement("
            ALTER TABLE aspirasis
            MODIFY COLUMN status ENUM(
                'baru',
                'terverifikasi',
                'menunggu_tindak_lanjut',
                'ditanggapi',
                'balasan_pelapor',
                'selesai',
                'ditolak',
                'kadaluwarsa'
            ) NOT NULL DEFAULT 'baru'
        ");
    }

    public function down(): void
    {
        // Optional: rollback enum ke set lama
        // DB::statement("ALTER TABLE aspirasis MODIFY COLUMN status ENUM('baru','terverifikasi','ditolak','menunggu_tindak_lanjut','ditanggapi','selesai','kadaluwarsa') NOT NULL DEFAULT 'baru'");
        Schema::table('aspirasis', function (Blueprint $table) {
            if (Schema::hasColumn('aspirasis', 'user_reply_deadline_at')) {
                $table->dropColumn('user_reply_deadline_at');
            }
        });
    }
};

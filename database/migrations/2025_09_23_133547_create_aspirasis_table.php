<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aspirasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raperda_id')->nullable()->constrained('raperdas')->nullOnDelete();


            // Identitas pelapor (opsional tergantung mode_privasi)
            $table->string('nama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('email')->nullable();


            // Konten inti
            $table->string('judul', 150);
            $table->text('isi');


            // Privasi: normal|anonim|rahasia
            $table->enum('mode_privasi', ['normal', 'anonim', 'rahasia'])->default('normal');


            // Tracking & keamanan
            $table->string('tracking_no', 20)->unique(); // contoh: JRP-2025-09-000123
            $table->string('tracking_pin', 6); // PIN 6 digit


            // Status tahapan
            $table->enum('status', [
                'baru',
                'terverifikasi',
                'ditolak',
                'menunggu_tindak_lanjut',
                'ditanggapi',
                'selesai',
                'kadaluwarsa'
            ])->default('baru')->index();


            // Stempel waktu & SLA
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('admin_replied_at')->nullable();
            $table->timestamp('user_replied_at')->nullable();
            $table->timestamp('closed_at')->nullable();


            $table->timestamp('verify_deadline_at')->nullable()->index(); // +2 hari
            $table->timestamp('admin_reply_deadline_at')->nullable()->index(); // +3 hari total
            $table->timestamp('final_deadline_at')->nullable()->index(); // +7 hari total


            $table->ipAddress('submit_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirasis');
    }
};

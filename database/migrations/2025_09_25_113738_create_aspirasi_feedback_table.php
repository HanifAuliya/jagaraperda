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
        Schema::create('aspirasi_feedback', function (Blueprint $table) {
            $table->id();

            // Kaitkan ke aspirasi (agar tidak "lepas" dari laporan yang diselesaikan)
            $table->foreignId('aspirasi_id')
                ->constrained('aspirasis') // sesuaikan nama tabel kalau beda
                ->cascadeOnDelete();

            // Nilai kepuasan
            $table->enum('rating', ['puas', 'cukup', 'tidak']);

            // Opsional catatan singkat dari pelapor
            $table->string('comment', 500)->nullable();

            // Metadata (opsional tapi berguna)
            $table->string('submitted_by_ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamps();

            // Satu feedback per aspirasi
            $table->unique('aspirasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirasi_feedback');
    }
};

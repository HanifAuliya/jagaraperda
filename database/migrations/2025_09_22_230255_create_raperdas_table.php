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
        Schema::create('raperdas', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);              // judul raperda
            $table->string('slug')->unique();
            $table->year('tahun')->nullable();         // tahun
            $table->enum('status', ['draf', 'final'])->default('draf');
            $table->text('ringkasan')->nullable();
            $table->string('berkas')->nullable();      // path file pdf
            $table->boolean('aktif')->default(true);   // true = tampilkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raperdas');
    }
};

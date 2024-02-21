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
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('kelompok_id');
            $table->foreignId('subkelompok_id');
            $table->foreignId('status_id');
            $table->string('nama');
            $table->float('anggaran_kegiatan', 16, 2)->default(0);
            $table->float('target_keuangan', 16, 2)->default(0);
            $table->float('realisasi_keuangan', 16, 2)->default(0);
            $table->float('target_fisik', 16, 2)->default(0);
            $table->float('realisasi_fisik', 16, 2)->default(0);
            $table->json('dones')->nullable();
            $table->json('problems')->nullable();
            $table->json('follow_up')->nullable();
            $table->json('todos')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};

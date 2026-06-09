<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_laporan_aktivitas', function (Blueprint $table) {
            $table->id('aktivitas_id');
            $table->uuid('aktivitas_uid')->unique();

            $table->unsignedBigInteger('aktivitas_kegiatan_id');

            $table->string('aktivitas_nama');
            $table->text('aktivitas_uraian')->nullable();

            $table->date('aktivitas_tanggal_mulai');
            $table->date('aktivitas_tanggal_selesai');

            $table->string('aktivitas_triwulan');

            $table->unsignedBigInteger('aktivitas_user_id')->nullable();
            $table->string('aktivitas_user_nama')->nullable();
            $table->string('aktivitas_user_nip')->nullable();

            $table->unsignedBigInteger('aktivitas_bidang_id')->nullable();
            $table->string('aktivitas_bidang_nama')->nullable();

            $table->string('aktivitas_status')->default('Aktif');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('aktivitas_kegiatan_id')
                ->references('laporan_kegiatan_id')
                ->on('saplarin_laporan_kegiatan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_laporan_aktivitas');
    }
};
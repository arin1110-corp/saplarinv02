<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_laporan_kegiatan', function (Blueprint $table) {
            $table->id('laporan_kegiatan_id');
            $table->uuid('laporan_kegiatan_uid')->unique();

            $table->year('laporan_kegiatan_tahun');

            $table->string('laporan_kegiatan_nama');
            $table->text('laporan_kegiatan_deskripsi')->nullable();

            $table->unsignedBigInteger('laporan_kegiatan_bidang_id')->nullable();
            $table->string('laporan_kegiatan_bidang_nama')->nullable();

            $table->unsignedBigInteger('laporan_kegiatan_user_id')->nullable();
            $table->string('laporan_kegiatan_user_nama')->nullable();
            $table->string('laporan_kegiatan_user_nip')->nullable();

            $table->string('laporan_kegiatan_status')->default('Aktif');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_laporan_kegiatan');
    }
};
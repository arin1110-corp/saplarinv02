<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_sub_kegiatan_laporan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->uuid('laporan_uid')->unique();
            $table->unsignedBigInteger('laporan_sub_kegiatan_id');
            $table->unsignedTinyInteger('laporan_bulan');
            $table->year('laporan_tahun');
            $table->string('laporan_status', 50)->default('Aktif');
            $table->string('laporan_created_by')->nullable();
            $table->string('laporan_created_by_nama')->nullable();
            $table->string('laporan_created_by_nip')->nullable();
            $table->timestamps();

            $table->foreign('laporan_sub_kegiatan_id', 'fk_skl_sub')->references('sub_kegiatan_id')->on('saplarin_sub_kegiatan')->cascadeOnDelete();

            $table->unique(['laporan_sub_kegiatan_id', 'laporan_bulan', 'laporan_tahun'], 'uk_skl_sub_bulan_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_sub_kegiatan_laporan');
    }
};
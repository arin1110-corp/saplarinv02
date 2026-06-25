<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_sub_kegiatan_tindak_lanjut', function (Blueprint $table) {
            $table->id('tindak_lanjut_id');
            $table->unsignedBigInteger('tindak_lanjut_laporan_id');
            $table->longText('tindak_lanjut_uraian');
            $table->timestamps();

            $table->foreign('tindak_lanjut_laporan_id', 'fk_sktl_laporan')->references('laporan_id')->on('saplarin_sub_kegiatan_laporan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_sub_kegiatan_tindak_lanjut');
    }
};
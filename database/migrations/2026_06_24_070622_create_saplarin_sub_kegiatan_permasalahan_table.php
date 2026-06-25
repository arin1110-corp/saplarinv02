<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_sub_kegiatan_permasalahan', function (Blueprint $table) {
            $table->id('permasalahan_id');
            $table->unsignedBigInteger('permasalahan_laporan_id');
            $table->longText('permasalahan_uraian');
            $table->timestamps();

            $table->foreign('permasalahan_laporan_id', 'fk_skp_laporan')->references('laporan_id')->on('saplarin_sub_kegiatan_laporan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_sub_kegiatan_permasalahan');
    }
};
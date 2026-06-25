<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_sub_kegiatan_solusi', function (Blueprint $table) {
            $table->id('solusi_id');
            $table->unsignedBigInteger('solusi_laporan_id');
            $table->longText('solusi_uraian');
            $table->timestamps();

            $table->foreign('solusi_laporan_id', 'fk_sks_laporan')->references('laporan_id')->on('saplarin_sub_kegiatan_laporan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_sub_kegiatan_solusi');
    }
};
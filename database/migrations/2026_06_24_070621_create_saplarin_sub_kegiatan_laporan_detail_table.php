<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_sub_kegiatan_laporan_detail', function (Blueprint $table) {
            $table->id('detail_id');

            $table->unsignedBigInteger('detail_laporan_id');
            $table->unsignedBigInteger('detail_indikator_id');

            $table->text('detail_indikator_nama');
            $table->decimal('detail_target', 18, 2)->default(0);
            $table->decimal('detail_realisasi', 18, 2)->default(0);
            $table->string('detail_satuan', 100);

            $table->timestamps();

            $table->foreign('detail_laporan_id')
                ->references('laporan_id')
                ->on('saplarin_sub_kegiatan_laporan')
                ->cascadeOnDelete();

            $table->foreign('detail_indikator_id')
                ->references('indikator_id')
                ->on('saplarin_sub_kegiatan_indikator')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_sub_kegiatan_laporan_detail');
    }
};
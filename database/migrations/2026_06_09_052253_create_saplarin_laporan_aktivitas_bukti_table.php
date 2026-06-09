<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_laporan_aktivitas_bukti', function (Blueprint $table) {
            $table->id('bukti_id');

            $table->unsignedBigInteger('bukti_aktivitas_id');

            $table->string('bukti_file');
            $table->string('bukti_nama_file')->nullable();

            $table->timestamps();

            $table->foreign('bukti_aktivitas_id')
                ->references('aktivitas_id')
                ->on('saplarin_laporan_aktivitas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_laporan_aktivitas_bukti');
    }
};
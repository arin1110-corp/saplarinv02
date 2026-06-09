<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_kinerja_progress_bukti', function (Blueprint $table) {
            $table->id('bukti_id');

            $table->unsignedBigInteger('bukti_progress_id');

            $table->string('bukti_file');
            $table->string('bukti_nama_file')->nullable();

            $table->timestamps();

            $table->foreign('bukti_progress_id')
                ->references('progress_id')
                ->on('saplarin_kinerja_progress')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_kinerja_progress_bukti');
    }
};
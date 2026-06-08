<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_progress_bukti', function (Blueprint $table) {
            $table->id('bukti_id');

            $table->unsignedBigInteger('bukti_progress_id');
            $table->string('bukti_file');
            $table->string('bukti_nama_file')->nullable();

            $table->enum('bukti_tipe', ['admin', 'user'])->default('user');

            $table->unsignedBigInteger('bukti_upload_by')->nullable();
            $table->string('bukti_upload_by_nama')->nullable();

            $table->timestamps();

            $table->foreign('bukti_progress_id')
                ->references('progress_id')
                ->on('saplarin_progress_kinerja')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_progress_bukti');
    }
};
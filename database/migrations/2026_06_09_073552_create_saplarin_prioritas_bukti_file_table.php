<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_prioritas_bukti_file', function (Blueprint $table) {
            $table->id('file_id');

            $table->unsignedBigInteger('file_bukti_id');

            $table->string('file_path');
            $table->string('file_nama_asli')->nullable();

            $table->timestamps();

            $table->foreign('file_bukti_id')
                ->references('bukti_id')
                ->on('saplarin_prioritas_bukti')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_prioritas_bukti_file');
    }
};
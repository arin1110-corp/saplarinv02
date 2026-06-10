<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sadarin_program_prioritas_capaian_file', function (Blueprint $table) {
            $table->id('file_id');

            $table->unsignedBigInteger('file_capaian_id');

            $table->string('file_path');
            $table->string('file_nama_asli')->nullable();

            $table->timestamps();

            $table->foreign('file_capaian_id')
                ->references('capaian_id')
                ->on('sadarin_program_prioritas_capaian')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sadarin_program_prioritas_capaian_file');
    }
};
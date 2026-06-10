<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sadarin_program_prioritas_capaian', function (Blueprint $table) {
            $table->id('capaian_id');
            $table->uuid('capaian_uid')->unique();

            $table->unsignedBigInteger('capaian_rencana_id');

            $table->string('capaian_judul');
            $table->text('capaian_deskripsi');

            $table->date('capaian_tanggal_mulai');
            $table->date('capaian_tanggal_selesai');

            $table->unsignedBigInteger('capaian_user_id')->nullable();
            $table->string('capaian_user_nama')->nullable();
            $table->string('capaian_user_nip')->nullable();

            $table->unsignedBigInteger('capaian_bidang_id')->nullable();
            $table->string('capaian_bidang_nama')->nullable();

            $table->string('capaian_status')->default('Aktif');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('capaian_rencana_id')
                ->references('rencana_id')
                ->on('sadarin_program_prioritas_rencana')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sadarin_program_prioritas_capaian');
    }
};
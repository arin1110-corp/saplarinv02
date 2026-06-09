<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_prioritas_bukti', function (Blueprint $table) {
            $table->id('bukti_id');
            $table->uuid('bukti_uid')->unique();

            $table->unsignedBigInteger('bukti_prioritas_id');

            $table->string('bukti_op_id')->nullable();
            $table->string('bukti_op_bidang')->nullable();

            $table->text('bukti_deskripsi_kegiatan');
            $table->date('bukti_tanggal_kegiatan');

            $table->unsignedBigInteger('bukti_user_id')->nullable();
            $table->string('bukti_user_nama')->nullable();
            $table->string('bukti_user_nip')->nullable();

            $table->unsignedBigInteger('bukti_bidang_id')->nullable();
            $table->string('bukti_bidang_nama')->nullable();

            $table->string('bukti_status')->default('Aktif');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bukti_prioritas_id')
                ->references('prioritas_id')
                ->on('saplarin_prioritas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_prioritas_bukti');
    }
};
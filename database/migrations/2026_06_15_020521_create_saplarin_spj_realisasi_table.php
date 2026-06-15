<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_spj_realisasi', function (Blueprint $table) {
            $table->id('spj_id');
            $table->uuid('spj_uid')->unique();

            $table->unsignedBigInteger('spj_pagu_id');

            $table->text('spj_uraian');
            $table->decimal('spj_nominal', 18, 2);
            $table->date('spj_tanggal');
            $table->timestamp('spj_tanggal_input')->nullable();

            $table->string('spj_file')->nullable();

            $table->unsignedBigInteger('spj_operator_id')->nullable();
            $table->string('spj_operator_nama')->nullable();
            $table->string('spj_operator_nip')->nullable();

            $table->unsignedBigInteger('spj_bidang_id')->nullable();
            $table->string('spj_bidang_nama')->nullable();

            $table->string('spj_status')->default('Aktif');

            $table->timestamps();

            $table->foreign('spj_pagu_id')
                ->references('spj_pagu_id')
                ->on('saplarin_spj_pagu')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_spj_realisasi');
    }
};
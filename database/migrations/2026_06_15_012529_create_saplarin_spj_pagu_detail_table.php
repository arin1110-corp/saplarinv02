<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_spj_pagu_detail', function (Blueprint $table) {
            $table->id('spj_pagu_detail_id');
            $table->unsignedBigInteger('spj_pagu_detail_pagu_id');

            $table->string('spj_pagu_detail_jenis');
            $table->integer('spj_pagu_detail_urutan')->default(0);
            $table->decimal('spj_pagu_detail_nominal', 18, 2)->default(0);
            $table->date('spj_pagu_detail_tanggal')->nullable();
            $table->text('spj_pagu_detail_keterangan')->nullable();

            $table->timestamps();

            $table->foreign('spj_pagu_detail_pagu_id')
                ->references('spj_pagu_id')
                ->on('saplarin_spj_pagu')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_spj_pagu_detail');
    }
};
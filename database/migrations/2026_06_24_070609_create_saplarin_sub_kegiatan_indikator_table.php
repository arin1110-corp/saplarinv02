<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saplarin_sub_kegiatan_indikator', function (Blueprint $table) {
            $table->id('indikator_id');
            $table->uuid('indikator_uid')->unique();
            $table->unsignedBigInteger('indikator_sub_kegiatan_id');
            $table->text('indikator_nama');
            $table->decimal('indikator_target', 18, 2)->default(0);
            $table->string('indikator_satuan', 100);
            $table->boolean('indikator_status')->default(1);
            $table->timestamps();

            $table->foreign('indikator_sub_kegiatan_id', 'fk_ski_sub')->references('sub_kegiatan_id')->on('saplarin_sub_kegiatan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_sub_kegiatan_indikator');
    }
};
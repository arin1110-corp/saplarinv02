<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saplarin_spj_pagu', function (Blueprint $table) {
            $table->id('spj_pagu_id');
            $table->uuid('spj_pagu_uid')->unique();

            $table->unsignedBigInteger('spj_pagu_program_id');
            $table->unsignedBigInteger('spj_pagu_kegiatan_id');
            $table->unsignedBigInteger('spj_pagu_sub_kegiatan_id');

            $table->year('spj_pagu_tahun');
            $table->decimal('spj_pagu_final', 18, 2)->default(0);

            $table->tinyInteger('spj_pagu_status')->default(1);

            $table->unsignedBigInteger('spj_pagu_created_by')->nullable();
            $table->string('spj_pagu_created_by_nama')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saplarin_spj_pagu');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sadarin_program_prioritas', function (Blueprint $table) {
            $table->id('prioritas_id');
            $table->uuid('prioritas_uid')->unique();

            $table->year('prioritas_tahun');
            $table->string('prioritas_judul');
            $table->text('prioritas_deskripsi')->nullable();

            $table->string('prioritas_status')->default('Aktif');

            $table->unsignedBigInteger('prioritas_created_by')->nullable();
            $table->string('prioritas_created_by_nama')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sadarin_program_prioritas');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sadarin_program_prioritas_rencana', function (Blueprint $table) {
            $table->id('rencana_id');
            $table->uuid('rencana_uid')->unique();

            $table->unsignedBigInteger('rencana_prioritas_id');

            $table->string('rencana_judul');
            $table->text('rencana_target');

            $table->unsignedBigInteger('rencana_user_id')->nullable();
            $table->string('rencana_user_nama')->nullable();
            $table->string('rencana_user_nip')->nullable();

            $table->unsignedBigInteger('rencana_bidang_id')->nullable();
            $table->string('rencana_bidang_nama')->nullable();

            $table->string('rencana_status')->default('Aktif');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('rencana_prioritas_id')
                ->references('prioritas_id')
                ->on('sadarin_program_prioritas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sadarin_program_prioritas_rencana');
    }
};